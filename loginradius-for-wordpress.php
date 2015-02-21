<?php
/**
 * Plugin Name: Social Login for wordpress
 * Plugin URI: http://www.loginradius.com
 * Description: Let your users log in, comment and share via their social accounts with Facebook, Google, Amazon, Twitter, LinkedIn, Vkontakte, QQ and over 25 more!
 * Version: 6.5
 * Author: LoginRadius Team
 * Author URI: http://www.loginradius.com
 * License: GPL2+
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

define( 'LR_ROOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'LR_ROOT_URL', plugin_dir_url( __FILE__ ) );

// Core Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-core/lr-core.php' ) ){
	require_once( 'lr-core/lr-core.php' );
}

// Login Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-login/lr-social-login.php' ) ) {
	require_once( 'lr-social-login/lr-social-login.php' );
}

// Raas Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-raas/lr-raas.php' ) && ! file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-login/lr-social-login.php' ) ) {
	require_once( 'lr-raas/lr-raas.php' );
}

// Custom Interface Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-custom-interface/lr-custom-interface.php' ) ){
	require_once( 'lr-custom-interface/lr-custom-interface.php' );
}

// Social Sharing Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-sharing/lr-social-sharing.php' ) ){
	require_once( 'lr-social-sharing/lr-social-sharing.php' );
}

// Social Profile Data Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-profile-data/lr-social-profile-data.php' ) ){
	require_once( 'lr-social-profile-data/lr-social-profile-data.php' );
}

// Commenting Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-commenting/lr-commenting.php' ) ){
	require_once( 'lr-commenting/lr-commenting.php' );
}

// Disqus Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-disqus-addon/lr-disqus-addon.php' ) ){
	require_once( 'lr-disqus-addon/lr-disqus-addon.php' );
}

// Social Invite Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-social-invite/lr-social-invite.php' ) ){
	require_once( 'lr-social-invite/lr-social-invite.php' );
}

// Mailchimp Module
if ( file_exists( plugin_dir_path( __FILE__ ) . 'lr-mailchimp/lr-mailchimp.php' ) ){
	require_once( 'lr-mailchimp/lr-mailchimp.php' );
}

/**
 * Add a settings link to the Plugins page,
 * so people can go straight from the plugin page to the settings page.
 */
function loginradius_login_setting_links( $links, $file ) {
	static $thisPlugin = '';
	if ( empty( $thisPlugin ) ) {
		$thisPlugin = plugin_basename( __FILE__ );
	}
	if ( $file == $thisPlugin ) {
		
		if( ! class_exists('LR_Social_Login') && ! class_exists('LR_Raas') ) {
			$settingsLink = '<a href="admin.php?page=loginradius_share">' . __( 'Settings' ) . '</a>';
		} else {
			$settingsLink = '<a href="admin.php?page=LoginRadius">' . __( 'Settings' ) . '</a>';
		}
		
		array_unshift( $links, $settingsLink );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'loginradius_login_setting_links', 10, 2 );