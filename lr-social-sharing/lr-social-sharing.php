<?php
// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'LR_Sharing_Data' ) ) {

	/**
	 * The main class and initialization point of the plugin.
	 */
	class LR_Sharing_Data {

		/**
		 * Constructor
		 */
		public function __construct() {

			// Register Activation hook callback.
			$this->install();

			// Declare constants and load dependencies.
			$this->define_constants();
			$this->load_dependencies();

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ), 5 );
		}

		/**
		 * Function for setting default options while plgin is activating.
		 */
		public static function install() {
			global $wpdb;
			require_once ( dirname( __FILE__ ) . '/install.php' );
			if ( function_exists('is_multisite') && is_multisite() ) {
				// check if it is a network activation - if so, run the activation function for each blog id
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					LR_Sharing_Install::set_default_options();
				}
				switch_to_blog($old_blog);
				return;
			} else {
				LR_Sharing_Install::set_default_options();
			}
		}

		/**
		 * Define constants needed across the plug-in.
		 */
		private function define_constants() {
			define( 'LR_SHARE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'LR_SHARE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		public static function enqueue_front_scripts() {
			wp_enqueue_style( 'lr-social-sharing-front', LR_SHARE_PLUGIN_URL . 'assets/css/lr-social-sharing-front.css', array(), '1.0' );
		}

		/**
		 * Loads PHP files that required by the plug-in
		 *
		 * @global loginradius_commenting_settings
		 */
		private function load_dependencies() {

			// Load LoginRadius files.
			require_once( 'admin/lr-social-share-admin.php' );
			require_once( 'includes/horizontal/loginradius_simplified_social_share_horizontal.php' );
			require_once( 'includes/vertical/loginradius_simplified_social_share_vertical.php' );
			require_once( 'includes/widgets/loginradius_horizontal_share_widget.php' );
			require_once( 'includes/widgets/loginradius_vertical_share_widget.php' );
			require_once( 'includes/shortcode/shortcode.php' );
		}
		
	}

}
new LR_Sharing_Data();
