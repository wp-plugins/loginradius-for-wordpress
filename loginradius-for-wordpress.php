<?php
/**
 * Plugin Name: Social Login for wordpress
 * Plugin URI: http://www.loginradius.com
 * Description: Let your users log in, comment and share via their social accounts with Facebook, Google, Amazon, Twitter, LinkedIn, Vkontakte, QQ and over 25 more!
 * Version: 6.1.2
 * Author: LoginRadius Team
 * Author URI: http://www.loginradius.com
 * License: GPL2+
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * Create menu.
 */
function create_loginradius_menu() {
	// Create Menu.
	add_menu_page('LoginRadius', 'LoginRadius', 'manage_options', 'LoginRadius', 'loginradius', plugin_dir_url( __FILE__ ) . '/assets/images/favicon.ico', 69.1 );
}
add_action ( 'admin_menu', 'create_loginradius_menu' );

require_once( 'loginradius-social-login/LoginRadius.php' );
require_once( 'loginradius-social-sharing/loginradius_simplified_social_share.php' );
require_once( 'loginradius-social-login/admin/views/api-settings/api-activation-view.php' );

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
		$settingsLink = '<a href="admin.php?page=LoginRadius">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settingsLink );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'loginradius_login_setting_links', 10, 2 );