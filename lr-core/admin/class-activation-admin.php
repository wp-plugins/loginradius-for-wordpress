<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the plugin admin.
 */
if ( ! class_exists( 'LR_Activation_Admin' ) ) {
	
	class LR_Activation_Admin {

		/**
		 * LR_Activation_Admin class instance
		 *
		 * @var string
		 */
		private static $instance;

		/**
		 * Get singleton object for class LR_Activation_Admin
		 *
		 * @return object LR_Activation_Admin
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof LR_Activation_Admin ) ) {
				self::$instance = new LR_Activation_Admin();
			}
			return self::$instance;
		}

		/*
		 * Constructor for class LR_Social_Login_Admin
		 */
		public function __construct() {
			$this->install();
			$this->js_in_footer();
			// Registering hooks callback for admin section.
			$this->register_hook_callbacks();
		}

		// Create Api Options if not already created.
		public function install() {
			global $loginradius_api_settings;
			if( ! get_option( 'LoginRadius_API_settings' ) ) {
				$api_options = array(
						'LoginRadius_apikey' => '',
						'LoginRadius_secret' => '',
						'scripts_in_footer'  => '1',
						'delete_options'     => '0',
						'sitename'           => '',
						'multisite_config'   => '1'
				);
				update_option( 'LoginRadius_API_settings', $api_options );
			}
			$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
		}

		public static function js_in_footer() {
			global $loginradius_api_settings, $lr_js_in_footer;
			
			// Set js in footer bool.
			$lr_js_in_footer = isset( $loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == '1' ? true : false;
		}

		/*
		 * Register admin hook callbacks
		 */
		public function register_hook_callbacks() {
			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_enqueue_scripts', array($this, 'load_scripts' ), 5 );
		}

		/**
		 * Callback for admin_menu hook,
		 * Register LoginRadius_settings and its sanitization callback. Add Login Radius meta box to pages and posts.
		 */
		public function admin_init() {
			global $pagenow, $loginradius_api_settings;
			register_setting( 'loginradius_api_settings', 'LoginRadius_API_settings', array( $this, 'validate_options') );

			// Replicate Social Login configuration to the subblogs in the multisite network
			if ( is_multisite() && is_main_site() ) {
				add_action( 'wpmu_new_blog', array($this, 'replicate_settings_to_new_blog') );
				add_action( 'update_option_LoginRadius_API_settings', array($this, 'login_radius_update_old_blogs') );
			}
		}

		/*
		 * Adding Javascript/Jquery for admin settings page
		 */
		public function load_scripts( $hook ) {
			global $lr_js_in_footer;

			if( $hook != 'toplevel_page_LoginRadius' ) {
				return;
			}
			wp_enqueue_script( 'lr_activation_options', LR_CORE_URL . 'assets/js/lr-activation.js', array('jquery'), '1.0', $lr_js_in_footer );
		}
		
		public static function validate_options( $settings ) {
			global $loginradius_api_settings;
			
			// If neither apikey and secret are not empty
			$settings['LoginRadius_apikey'] = sanitize_text_field( $settings['LoginRadius_apikey'] );
			$settings['LoginRadius_secret'] = sanitize_text_field( $settings['LoginRadius_secret'] );

			if ( isset( $settings['LoginRadius_apikey'] ) && !empty( $settings['LoginRadius_apikey'] ) && isset( $settings['LoginRadius_secret'] ) && !empty( $settings['LoginRadius_secret'] ) ) {

				$encodeString = 'settings';

				if ( LR_Social_Login_Admin::api_validation_response( $settings['LoginRadius_apikey'], $settings['LoginRadius_secret'], $encodeString ) ) {
					return $settings;
				} else {
					// Api or Secret is not valid or something wrong happened while getting response from LoginRadius api
					$message = 'please check your php.ini settings to enable CURL or FSOCKOPEN';
					global $currentErrorCode, $currentErrorResponse;
					
					$errorMessage = array(
						"API_KEY_NOT_VALID" => 'LoginRadius API key is invalid. Get your LoginRadius API key from <a href="http://www.loginradius.com" target="_blank">LoginRadius</a>',
						'API_SECRET_NOT_VALID' => 'LoginRadius API Secret is invalid. Get your LoginRadius API Secret from <a href="http://www.loginradius.com" target="_blank">LoginRadius</a>',
						'API_KEY_NOT_FORMATED' => 'LoginRadius API Key is not formatted correctly.',
						'API_SECRET_NOT_FORMATED' => 'LoginRadius API Secret is not formatted correctly.',
					);
					if ( $currentErrorCode[0] == '0' ) {
						$message = $currentErrorResponse;
					} else {
						$message = $errorMessage[$currentErrorCode[0]];
					}
					add_settings_error( 'LoginRadius_API_settings', esc_attr( 'settings_updated' ), $message, 'error' );

					return $settings;
				}
			}else{
				add_settings_error( 'LoginRadius_API_settings', esc_attr( 'settings_updated' ), 'Settings Updated', 'updated' );
				return $settings;
			}
		}

		// Replicate the social login config to the new blog created in the multisite network
		public function replicate_settings_to_new_blog( $blogId ) {
			global $loginradius_api_settings;
			add_blog_option( $blogId, 'LoginRadius_API_settings', $loginradius_api_settings );
		}

		// Update the social login options in all the old blogs
		public function login_radius_update_old_blogs( $oldConfig ) {
			global $loginradius_api_settings;
			if ( isset( $loginradius_api_settings['multisite_config'] ) && $loginradius_api_settings['multisite_config'] == '1' ) {
				$settings = get_option( 'LoginRadius_API_settings' );
				$blogs = wp_get_sites();
				foreach ( $blogs as $blog ) {
					update_blog_option( $blog['blog_id'], 'LoginRadius_API_settings', $settings );
				}
			}
		}

		/*
		 * Callback for add_menu_page,
		 * This is the first function which is called while plugin admin page is requested
		 */
		public static function options_page() {
			include_once "views/class-activation-settings-view.php";
			LR_Activation_Settings::render_options_page();
		}
	}
}

LR_Activation_Admin::get_instance();
