<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

if ( !defined('LOGINRADIUS_SHARE_PLUGIN_DIR') ) {
	define( 'LOGINRADIUS_SHARE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( !defined('LOGINRADIUS_SHARE_PLUGIN_URL') ) {
	define( 'LOGINRADIUS_SHARE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Initialize share in footer bool.
$loginradius_share_load_js_in_footer = false;

// Load LoginRadius files.
require_once( 'initialize.php' );
require_once( 'admin/loginradius_simplified_social_share_admin_options.php' );
require_once( 'includes/horizontal/loginradius_simplified_social_share_horizontal.php' );
require_once( 'includes/vertical/loginradius_simplified_social_share_vertical.php' );
require_once( 'includes/widgets/loginradius_horizontal_share_widget.php' );
require_once( 'includes/widgets/loginradius_vertical_share_widget.php' );
require_once( 'includes/shortcode/shortcode.php' );

/**
 * Add a settings link to the Plugins page,
 * so people can go straight from the plugin page to the settings page.
 */
function loginradius_share_setting_links( $links, $file ) {
	static $thisPlugin = '';
	if ( empty( $thisPlugin ) ) {
		$thisPlugin = plugin_basename( __FILE__ );
	}
	if ( $file == $thisPlugin ) {
		$settingsLink = '<a href="admin.php?page=loginradius_share">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settingsLink );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'loginradius_share_setting_links', 10, 2 );