<?php

// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}


if ( !class_exists( 'Login_Radius' ) ) {

	/**
	 * The main class and initialization point of the plugin.
	 */
	class Login_Radius {

		/**
		 * Login_Radius class instance
		 *
		 * @var string
		 */
		private static $instance;

		/**
		 * Mininmum required version of WordPress for this plug-in to function correctly.
		 *
		 * @var string
		 */
		public static $wp_min_version = "3.4";

		/**
		 * Get singleton object for class Login_Radius
		 *
		 * @return object Login_Radius
		 */
		public static function get_instance() {

			if ( !isset( self::$instance ) && !( self::$instance instanceof Login_Radius ) ) {
				self::$instance = new Login_Radius();
			}
			return self::$instance;
		}

		/**
		 * Construct and start plug-in's other functionalities
		 */
		public function __construct() {

			if ( !$this->is_requirements_met() ) {
				//Return if requirements are not met.
				return;
			}

			//Declare constants and load dependencies
			$this->define_constants();
			$this->load_dependencies();
			// Register Activation hook callback.
			$this->install();
		}

		/**
		 * Checks that the WordPress setup meets the plugin requirements
		 *
		 * @global string $wp_version
		 *
		 * @return boolean
		 */
		private function is_requirements_met() {
			global $wp_version;

			if ( !version_compare( $wp_version, self:: $wp_min_version, '>=' ) ) {
				add_action( 'admin_notices', array($this, 'notify_admin') );
				return false;
			}
			return true;
		}

		/**
		 * Display admin notice if requirements are not made
		 */
		public static function notify_admin() {
			echo '<div id="message" class="error"><p><strong>';
			echo __( 'Sorry, LoginRadius Social Login and Share requires WordPress ' . self:: $wp_min_version . ' or higher.Please upgrade your WordPress setup', 'LoginRadius' );
			echo '</strong></p></div>';
		}

		/**
		 * Define constants needed across the plug-in.
		 */
		private function define_constants() {

			define( 'LOGINRADIUS_SOCIALLOGIN_VERSION', '6.1.2' );
			define( 'LOGINRADIUS_MIN_WP_VERSION', '3.4' );
			define( 'LOGINRADIUS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'LOGINRADIUS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			define( 'LOGINRADIUS_VALIDATION_API_URL', 'https://api.loginradius.com/api/v2/app/validate' );
		}

		/**
		 * Loads PHP files that required by the plug-in
		 *
		 * @global loginRadiusSettings, loginRadiusObject
		 */
		private function load_dependencies() {
			global $loginRadiusSettings, $loginradius_api_settings, $loginRadiusObject, $loginRadiusLoginIsBpActive;

			//Load required files.
			require_once ('lib/LoginRadiusSDK.php');
			require_once( 'common/class-loginradius-common.php' );
			require_once ('common/loginradius-ajax.php');
			require_once('widgets/loginradius-social-login-widget.php');
			require_once('widgets/loginradius-social-linking-widget.php');
			require_once('public/inc/login/class-login-helper.php');

			// Get objetc for LoginRadius Sdk
			$loginRadiusObject = new Login_Radius_SDK();

			// Get LoginRadius plugin options
			$loginRadiusSettings = get_option( 'LoginRadius_settings' );

			// Get LoginRadius plugin settings.
			$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );

			$loginRadiusLoginIsBpActive = false;

			add_action( 'bp_include', array('Login_Helper', 'set_budddy_press_status_variable') );

			// Admin Panel
			if ( is_admin() ) {
				// load admin functionality
				require_once( 'admin/class-loginradius-admin.php' );
			}
			// Front-End
			if ( !is_admin() ) {
				// Load public functionality
				require_once( 'public/class-loginradius-front.php' );
			}
		}

		/**
		 * Function for setting default options while plgin is activating.
		 */
		public static function install() {
			global $wpdb;
			require_once (dirname( __FILE__ ) . '/install.php');
			if (function_exists('is_multisite') && is_multisite()) {
				// check if it is a network activation - if so, run the activation function for each blog id
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					Login_Radius_Install:: set_default_options();
				}
				switch_to_blog($old_blog);
				return;
			} else {
				Login_Radius_Install:: set_default_options();
			}
		}
	}

}

// return object so that other plugins can use it as global.
$GLOBALS['loginradius'] = Login_Radius::get_instance();
