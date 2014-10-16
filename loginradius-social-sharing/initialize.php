<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

$options = array(
		'horizontal_enable'          => '1',
		'vertical_enable'            => '',
		'horizontal_share_interface' => '32-h',
		'vertical_share_interface'   => '32-v',
		'horizontal_sharing_providers'=>array(
			'Default' => array(
				'Facebook' => 'Facebook',
				'Email' => 'Email',
				'Print' => 'Print',
				'GooglePlus' => 'GooglePlus',
				'LinkedIn' => 'LinkedIn',
				'Twitter' => 'Twitter',
				'Pinterest' => 'Pinterest'
			),
			'Hybrid'  => array(
				'Facebook Like' => 'Facebook Like',
				'Twitter Tweet' => 'Twitter Tweet',
				'Google+ Share' => 'Google+ Share',
				'Pinterest Pin it' => 'Pinterest Pin it',
				'LinkedIn Share' => 'LinkedIn Share'
			)
		),
		'vertical_sharing_providers'=>array(
			'Default' => array(
				'Facebook' => 'Facebook',
				'Email' => 'Email',
				'Print' => 'Print',
				'GooglePlus' => 'GooglePlus',
				'LinkedIn' => 'LinkedIn',
				'Twitter' => 'Twitter',
				'Pinterest' => 'Pinterest'
			),
			'Hybrid'  => array(
				'Facebook Like' => 'Facebook Like',
				'Twitter Tweet' => 'Twitter Tweet',
				'Google+ Share' => 'Google+ Share',
				'Pinterest Pin it' => 'Pinterest Pin it',
				'LinkedIn Share' => 'LinkedIn Share'
			)
		),
		'lr-clicker-hr-home' => '1',
		'lr-clicker-hr-post' => '1',
		'lr-clicker-hr-static' => '1',
		'lr-clicker-hr-excerpts' => '1',
		'horizontal_position' => array(
			'Home' => array(
				'Top' => 'Top'
			),
			'Posts' => array(
				'Top' => 'Top',
				'Bottom' => 'Bottom'
			),
			'Pages' => array(
				'Top' => 'Top'
			),
			'Excerpts' => array(
				'Top' => 'Top'
			)
		),
		'horizontal_rearrange_providers' => array(
			'Facebook',
			'Twitter',
			'LinkedIn',
			'GooglePlus',
			'Pinterest',
			'Email',
			'Print'
		),
		'vertical_rearrange_providers' => array(
			'Facebook',
			'Twitter',
			'LinkedIn',
			'GooglePlus',
			'Pinterest',
			'Email',
			'Print'
		)
);

$api_options = array(
		'LoginRadius_apikey'		=> '',
		'LoginRadius_secret'		=> '',
		'scripts_in_footer'			=> '1'
);

if( ! get_option( 'LoginRadius_share_settings' ) ) {

	// Adding LoginRadius plugin options if not available.
	update_option( 'LoginRadius_share_settings', $options );
}

if( ! get_option( 'LoginRadius_API_settings' ) ) {
	// Get Existing API key for update.
	if( get_option( 'LoginRadius_settings' ) ) {
		$loginradius_existing_settings = get_option( 'LoginRadius_settings' );
		if( isset($loginradius_existing_settings['LoginRadius_apikey'] ) && ! empty( $loginradius_existing_settings['LoginRadius_apikey'] ) ) {
			$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
		}
	}

	if( get_option( 'LoginRadius_sharing_settings' ) ) {
		$loginradius_existing_settings = get_option( 'LoginRadius_sharing_settings' );
		if( isset( $loginradius_existing_settings['LoginRadius_apikey'] ) && ! empty( $loginradius_existing_settings['LoginRadius_apikey'] ) ) {
			$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
			
		}
	}
	update_option( 'LoginRadius_API_settings', $api_options );
}

// Get LoginRadius plugin settings.
$loginradius_share_settings = get_option( 'LoginRadius_share_settings' );

// Get LoginRadius plugin settings.
$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );

// Set share in footer to true if set.
if( isset( $loginradius_share_settings['js_footer_enable'] ) && $loginradius_share_settings['js_footer_enable'] == '1' ) {
	global $loginradius_share_load_js_in_footer;
	$loginradius_share_load_js_in_footer = true;
}

/**
 * Add stylesheet and JavaScript to admin section.
 */
function loginradius_share_add_stylesheet() {
	global $loginradius_share_load_js_in_footer;
	wp_enqueue_style( 'loginradius_sharing_style', plugins_url('/assets/css/loginradius_sharing_style.css', __FILE__) );
	wp_enqueue_script( 'loginradius_share_admin_javascript', plugins_url( '/assets/js/loginradius_sharing_admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-mouse', 'jquery-touch-punch' ), false, $loginradius_share_load_js_in_footer );
}
add_action( 'admin_enqueue_scripts', 'loginradius_share_add_stylesheet' );

/**
 * Add stylesheet and JavaScript to client sections
 */
function enqueue_loginradius_share_scripts() {
	loginradius_share_add_stylesheet();
	wp_enqueue_script( 'loginradius_javascript_init', plugins_url( '/assets/js/loginradius_sharing.js', __FILE__ ) );
	wp_enqueue_script( 'lr_javascript', '//cdn.loginradius.com/share/v1/LoginRadius.js', array(), '2.5', false );
}
add_action ( 'wp_enqueue_scripts', 'enqueue_loginradius_share_scripts' );

/**
 * Register JavaScripts and create menu.
 */
function register_loginradius_share_admin_menu() {

	// Login and registration form javascript. Required in <head>.
	wp_enqueue_script( 'loginradius_share_admin_reg_javascript', '//cdn.loginradius.com/hub/js/LoginRadius.1.0.js' , array(), '2.5', false );
	wp_enqueue_script( 'loginradius_share_admin_aia_javascript', plugins_url( '/assets/js/loginradius-aia.js', __FILE__), array( 'jquery' ), '2.5', false );

	if( ! has_action( 'admin_menu', 'create_loginradius_menu' ) ) {
		// Create menu if menu has not been created.
		add_menu_page( 'Social Sharing by LoginRadius', 'Social Sharing', 'manage_options', 'loginradius_share', 'loginradius_share_select_admin', plugin_dir_url( __FILE__ ) . '/assets/images/favicon.ico', 69.1 );
	}else {
		// Add to existing menu.
		add_submenu_page( 'LoginRadius', 'Social Sharing by LoginRadius', 'Social Sharing', 'manage_options', 'loginradius_share', 'loginradius_share_select_admin' );
	}
}
add_action ( 'admin_menu', 'register_loginradius_share_admin_menu' );

/**
 * Register Share Settings.
 */
function register_loginradius_share_settings() {
	register_setting( 'loginradius_share_settings', 'LoginRadius_share_settings' );
	register_setting( 'loginradius_api_settings', 'LoginRadius_API_settings' );
}
add_action ( 'admin_init', 'register_loginradius_share_settings' );

/**
 * Reset Sharing Settings.
 */
function reset_loginradius_share_options() {
	global $options;
	
	// Load reset options.
	update_option( 'LoginRadius_share_settings', $options );
}