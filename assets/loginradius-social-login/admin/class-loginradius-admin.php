<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the plugin admin.
 */
if ( !class_exists( 'Login_Radius_Admin' ) ) {

	class Login_Radius_Admin {

		/**
		 * Login_Radius_Admin class instance
		 *
		 * @var string
		 */
		private static $instance;

		/**
		 * Get singleton object for class Login_Radius_Admin
		 *
		 * @return object Login_Radius_Admin
		 */
		public static function get_instance() {
			if ( !isset( self::$instance ) && !( self::$instance instanceof Login_Radius_Admin ) ) {
				self::$instance = new Login_Radius_Admin();
			}
			return self::$instance;
		}

		/*
		 * Constructor for class Login_Radius_Admin
		 */

		public function __construct() {
			if ( !class_exists( 'Admin_Helper' ) ) {
				require_once "helpers/class-admin-helper.php";
			}
			// Registering hooks callback for admin section.
			$this->register_hook_callbacks();
		}

		/*
		 * Register admin hook callbacks
		 */

		public function register_hook_callbacks() {
			global $loginRadiusSettings;
			
			//add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
			add_action( 'admin_menu', array($this, 'admin_menu') );
			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_notices', array($this, 'account_linking_info_on_profile_page') );
			// Filter for changing default WordPress avatar
			if ( isset( $loginRadiusSettings['LoginRadius_socialavatar'] ) && ( $loginRadiusSettings['LoginRadius_socialavatar'] == 'socialavatar' ) ) {
				add_filter( 'get_avatar', array($this, 'get_social_avatar'), 10, 5 );
			}
		}

		/*
		 * Callback for admin_menu hook
		 */

		public function admin_menu() {
			
			add_action( 'admin_print_scripts', array($this, 'load_scripts') );
			add_action( 'admin_print_styles', array($this, 'load_styles') );
			
			if( ! has_action( 'admin_menu', 'create_loginradius_menu' ) ) {
				// Create menu if menu has not been created.
				add_menu_page( 'LoginRadius', '<b>LoginRadius</b>', 'manage_options', 'LoginRadius', 'Login_Radius_Admin::options_page', LOGINRADIUS_PLUGIN_URL . 'assets/images/favicon.ico' , 69.1 );
			}else {
				// Add to existing menu.
				add_submenu_page( 'LoginRadius', 'Settings', 'Activation', 'manage_options', 'LoginRadius', 'loginradius' );
				add_submenu_page( 'LoginRadius', 'Social Login', 'Social Login', 'manage_options', 'SocialLogin', 'Login_Radius_Admin::options_page' );
			}
		}

		/*
		 * Adding javascrip/Jquery for admin settings page
		 */

		public function load_scripts() {
			$scriptLocation = apply_filters( 'LoginRadius_files_uri', LOGINRADIUS_PLUGIN_URL . 'assets/js/loginradius-options-page.js?t=6.1.2' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'LoginRadius_options_page_script', $scriptLocation, array(), false, false );
			wp_enqueue_script( 'LoginRadius_options_page_script2', LOGINRADIUS_PLUGIN_URL . 'assets/js/loginRadiusAdmin.js?t=6.1.2', array(), false, false );
		}

		/*
		 * adding style to plugin setting page
		 */

		public function load_styles() {
			?>
			<!--[if IE]>
				<link href="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/css/loginRadiusOptionsPageIE.css' ?>" rel="stylesheet" type="text/css" />
			<![endif]-->
			<?php
			$styleLocation = apply_filters( 'LoginRadius_files_uri', LOGINRADIUS_PLUGIN_URL . 'assets/css/loginRadiusOptionsPage.css' );
			wp_enqueue_style( 'login_radius_options_page_style', $styleLocation . '?t=4.0.0' );
			wp_enqueue_style( 'thickbox' );
		}

		/**
		 * Callback for admin_menu hook,
		 * Register LoginRadius_settings and its sanitization callback. Add Login Radius meta box to pages and posts.
		 */
		public function admin_init() {
			global $pagenow, $loginRadiusSettings, $loginradius_api_settings;

			register_setting( 'LoginRadius_setting_options', 'LoginRadius_settings', array($this, 'validate_options') );
			register_setting( 'loginradius_share_settings', 'LoginRadius_share_settings' );
			register_setting( 'loginradius_api_settings', 'LoginRadius_API_settings', array($this, 'validate_API_options') );

			// add a callback public function to save any data a user enters in
			$this->meta_box_setup();
			// add a callback public function to save any data a user enters in
			add_action( 'save_post', array(&$this, 'save_meta') );

			if ( $pagenow == 'profile.php' && isset( $_REQUEST['token'] ) ) {
				Login_Radius_Common:: perform_linking_operation();
			}
			if ( ( isset( $loginRadiusSettings['LoginRadius_noProvider'] ) && $loginRadiusSettings['LoginRadius_noProvider'] == '1' ) || ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) ) {
				add_filter( 'manage_users_columns', array('Admin_Helper', 'add_provider_column_in_users_list') );
				add_action( 'manage_users_custom_column', array('Admin_Helper', 'login_radius_show_provider'), 10, 3 );
				if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
					add_filter( 'admin_head', array('Admin_Helper', 'add_script_for_users_page') );
				}
			}
			// replicate Social Login configuration to the subblogs in the multisite network
			if ( is_multisite() && is_main_site() ) {
				add_action( 'wpmu_new_blog', array($this, 'replicate_loginradius_settings_to_new_blog') );
				add_action( 'update_option_LoginRadius_settings', array($this, 'login_radius_update_old_blogs') );
			}
		}
		// replicate the social login config to the new blog created in the multisite network
		public function replicate_loginradius_settings_to_new_blog( $blogId ) {
			global $loginRadiusSettings;
			add_blog_option( $blogId, 'LoginRadius_settings', $loginRadiusSettings );
		}
		// update the social login options in all the old blogs
		public function login_radius_update_old_blogs( $oldConfig ) {
			$newConfig = get_option( 'LoginRadius_settings' );
			if ( isset( $newConfig['multisite_config'] ) && $newConfig['multisite_config'] == '1' ) {
				$blogs = wp_get_sites();
				foreach ( $blogs as $blog ) {
					update_blog_option( $blog['blog_id'], 'LoginRadius_settings', $newConfig );
				}
			}
		}

		/*
		 * adding LoginRadius meta box on each page and post
		 */

		public function meta_box_setup() {
			foreach ( array('post', 'page') as $type ) {
				add_meta_box( 'login_radius_meta', 'LoginRadius', array($this, 'meta_setup'), $type );
			}
		}

		/**
		 * Display  metabox information on page and post
		 */
		public function meta_setup() {
			global $post;
			$postType = $post->post_type;
			$lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );
			?>
			<p>
				<label for="login_radius_sharing">
					<input type="checkbox" name="_login_radius_meta[sharing]" id="login_radius_sharing" value='1' <?php checked( '1', @$lrMeta['sharing'] ); ?> />
					<?php _e( 'Disable Social Sharing on this ' . $postType, 'LoginRadius' ) ?>
				</label>
			</p>
			<?php
			// custom nonce for verification later
			echo '<input type="hidden" name="login_radius_meta_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
		}



		/**
		 * Save login radius meta fields.
		 */
		public function save_meta( $postId ) {
			// make sure data came from our meta box
			if ( !isset( $_POST['login_radius_meta_nonce'] ) || !wp_verify_nonce( $_POST['login_radius_meta_nonce'], __FILE__ ) ) {
				return $postId;
			}
			// check user permissions
			if ( $_POST['post_type'] == 'page' ) {
				if ( !current_user_can( 'edit_page', $postId ) ) {
					return $postId;
				}
			} else {
				if ( !current_user_can( 'edit_post', $postId ) ) {
					return $postId;
				}
			}
			if ( isset( $_POST['_login_radius_meta'] ) ) {
				$newData = $_POST['_login_radius_meta'];
			} else {
				$newData = 0;
			}
			update_post_meta( $postId, '_login_radius_meta', $newData );
			return $postId;
		}

		/*
		 * Callback for add_menu_page,
		 * This is the first function which is called while plugin admin page is requested
		 */

		public static function options_page() {
			include_once "views/settings.php";
			Login_Radius_Admin_Settings:: render_options_page();
		}
		
		/**
		 * Replace default avatar with social avatar
		 */
		public function get_social_avatar( $avatar, $avuser, $size, $default, $alt = '' ) {
			$userId = null;
			$default = null;

			if ( is_numeric( $avuser ) ) {
				if ( $avuser > 0 ) {
					$userId = $avuser;
				}
			} elseif ( is_object( $avuser ) ) {
				if ( property_exists( $avuser, 'user_id' ) && is_numeric( $avuser->user_id ) ) {
					$userId = $avuser->user_id;
				}
			}
			if ( !empty( $userId ) ) {

				// Changes Avatar to Social Login Avatar - Admin Section.
				if ( ( $userAvatar = get_user_meta( $userId, 'loginradius_picture', true ) ) !== false && strlen( trim( $userAvatar ) ) > 0 ) {
					return '<img alt="' . esc_attr( $alt ) . '" src="' . $userAvatar . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '" />';
				} elseif ( ( $userAvatar = get_user_meta( $userId, 'loginradius_thumbnail', true ) ) !== false && strlen( trim( $userAvatar ) ) > 0 ) {
					return '<img alt="' . esc_attr( $alt ) . '" src="' . $userAvatar . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '" />';
				}

			}
			return ;
		}
		
		/**
		 * Add a settings link to the Plugins page, so people can go straight from the plugin page to
		 * settings page.
		 */
		public function plugin_action_links( $links, $file ) {
			$settings_link = '<a href="admin.php?page=LoginRadius">' . esc_html__( 'Settings', 'LoginRadius' ) . '</a>';
			if ( $file == 'loginradius-for-wordpress/LoginRadius.php' )
				array_unshift( $links, $settings_link );

		return $links;
		}

		/**
		 * Validate plugin options,
		 * Function to be called when settings save button is clicked on plugin settings page
		 */
		public static function validate_API_options( $loginradius_api_settings ) {

			$loginradius_api_settings_old = get_option( 'LoginRadius_API_settings' );

			if ( !isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !isset( $loginradius_api_settings['LoginRadius_secret'] ) ){

				if( isset( $loginradius_api_settings_old['LoginRadius_apikey'] ) ) {
					$loginradius_api_settings['LoginRadius_apikey'] = $loginradius_api_settings_old['LoginRadius_apikey'];
				} else {
					$loginradius_api_settings['LoginRadius_apikey'] = '00000000-0000-0000-0000-000000000000';
				}

				if( isset( $loginradius_api_settings_old['LoginRadius_secret'] ) ) {
					$loginradius_api_settings['LoginRadius_secret'] = $loginradius_api_settings_old['LoginRadius_secret'];
				} else {
					$loginradius_api_settings['LoginRadius_secret'] = '';
				}

			}else{

				if( empty( $loginradius_api_settings['LoginRadius_apikey'] ) && isset( $loginradius_api_settings_old['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings_old['LoginRadius_apikey'] ) ) {
					$loginradius_api_settings['LoginRadius_apikey'] = $loginradius_api_settings_old['LoginRadius_apikey'];
				} elseif ( empty( $loginradius_api_settings['LoginRadius_apikey'] ) ) {
					$loginradius_api_settings['LoginRadius_apikey'] = '00000000-0000-0000-0000-000000000000';
				}

				if( isset( $loginradius_api_settings_old['scripts_in_footer'] ) ) {
					$loginradius_api_settings['scripts_in_footer'] = $loginradius_api_settings_old['scripts_in_footer'];
				} else {
					$loginradius_api_settings['scripts_in_footer'] = '';
				}
			}

			if ( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings['LoginRadius_apikey'] ) && isset( $loginradius_api_settings['LoginRadius_secret'] ) && !empty( $loginradius_api_settings['LoginRadius_secret'] ) ) {

				// If neither apikey and secret are not empty
				$apiKey = sanitize_text_field( $loginradius_api_settings['LoginRadius_apikey'] );
				$apiSecret = sanitize_text_field( $loginradius_api_settings['LoginRadius_secret'] );
				$encodeString = Admin_Helper:: get_encoded_settings_string( $loginradius_api_settings );

				if ( self:: api_validation_response( $apiKey, $apiSecret, $encodeString ) ) {
					return $loginradius_api_settings;
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

					return $loginradius_api_settings;
				}
			}else{
				add_settings_error( 'LoginRadius_API_settings', esc_attr( 'settings_updated' ), 'Settings Updated', 'updated' );
				return $loginradius_api_settings;
			}
		}


		/**
		 * Validate plugin options,
		 * Function to be called when settings save button is clicked on plugin settings page
		 */
		public static function validate_options( $loginRadiusSettings ) {
			require_once LOGINRADIUS_PLUGIN_DIR . 'admin/helpers/class-admin-helper.php';

			// Validate settings and return settings to be saved
			$loginradius_api_settings['scripts_in_footer'] = isset($loginRadiusSettings['scripts_in_footer']) ? $loginRadiusSettings['scripts_in_footer'] : '';
			update_option( 'LoginRadius_API_settings', $loginradius_api_settings );


			$loginRadiusSettings['LoginRadius_socialavatar'] = ( ( isset( $loginRadiusSettings['LoginRadius_socialavatar'] ) && in_array( $loginRadiusSettings['LoginRadius_socialavatar'], array('socialavatar', 'largeavatar', 'defaultavatar') ) ) ? $loginRadiusSettings['LoginRadius_socialavatar'] : 'socialavatar' );
			$loginRadiusSettings['LoginRadius_dummyemail'] = ( isset( $loginRadiusSettings['LoginRadius_dummyemail'] ) && $loginRadiusSettings['LoginRadius_dummyemail'] == 'notdummyemail' ) ? 'notdummyemail' : 'dummyemail';

			$loginRadiusSettings['LoginRadius_redirect'] = ( ( isset( $loginRadiusSettings['LoginRadius_redirect'] ) && in_array( $loginRadiusSettings['LoginRadius_redirect'], array('samepage', 'homepage', 'dashboard', 'bp', 'custom') ) ) ? $loginRadiusSettings['LoginRadius_redirect'] : 'samepage' );
			$loginRadiusSettings['LoginRadius_loutRedirect'] = ( ( isset( $loginRadiusSettings['LoginRadius_loutRedirect'] ) && in_array( $loginRadiusSettings['LoginRadius_loutRedirect'], array('homepage', 'custom') ) ) ? $loginRadiusSettings['LoginRadius_loutRedirect'] : 'homepage' );
			$loginRadiusSettings['LoginRadius_loginformPosition'] = ( ( isset( $loginRadiusSettings['LoginRadius_loginformPosition'] ) && in_array( $loginRadiusSettings['LoginRadius_loginformPosition'], array('embed', 'beside') ) ) ? $loginRadiusSettings['LoginRadius_loginformPosition'] : 'embed' );
			$loginRadiusSettings['LoginRadius_regformPosition'] = ( ( isset( $loginRadiusSettings['LoginRadius_regformPosition'] ) && in_array( $loginRadiusSettings['LoginRadius_regformPosition'], array('embed', 'beside') ) ) ? $loginRadiusSettings['LoginRadius_regformPosition'] : 'embed' );
			$loginRadiusSettings['LoginRadius_commentform'] = ( ( isset( $loginRadiusSettings['LoginRadius_commentform'] ) && in_array( $loginRadiusSettings['LoginRadius_commentform'], array('old', 'new') ) ) ? $loginRadiusSettings['LoginRadius_commentform'] : 'new' );
			$loginRadiusSettings['LoginRadius_numColumns'] = ( isset( $loginRadiusSettings['LoginRadius_numColumns'] ) && is_numeric( $loginRadiusSettings['LoginRadius_numColumns'] ) ) ? $loginRadiusSettings['LoginRadius_numColumns'] : '';
			
			return $loginRadiusSettings;
		}

		/**
		 * Get response from LoginRadius api
		 */
		public static function api_validation_response( $apiKey, $apiSecret, $string ) {
			global $currentErrorCode, $currentErrorResponse;

			$url = LOGINRADIUS_VALIDATION_API_URL . '?apikey=' . rawurlencode( $apiKey ) . '&apisecret=' . rawurlencode( $apiSecret );
			$response = wp_remote_post( $url, array(
				'method' => 'POST',
				'timeout' => 15,
				'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
				'body' => array('addon' => 'WordPress', 'version' => LOGINRADIUS_SOCIALLOGIN_VERSION, 'agentstring' => $_SERVER['HTTP_USER_AGENT'], 'clientip' => $_SERVER['REMOTE_ADDR'], 'configuration' => $string),
				'cookies' => array(),
					)
			);

			if ( is_wp_error( $response ) ) {
				$currentErrorCode = '0';
				$currentErrorResponse = "Something went wrong: " . $response->get_error_message();
				return false;
			} else {

				if ( json_decode( $response['body'] )->Status ) {
					return true;
				} else {
					$currentErrorCode = json_decode( $response['body'] )->Messages;
					return false;
				}
			}
		}

		/**
		 * Displaying account linking on profile page
		 */
		public static function account_linking_info_on_profile_page() {
			global $pagenow;

			if ( $pagenow == 'profile.php' ) {
				echo Login_Radius_Common:: check_linking_status_parameters();
				// if remove button clicked
				if ( isset( $_GET['loginRadiusMap'] ) && !empty( $_GET['loginRadiusMap'] ) && isset( $_GET['loginRadiusMappingProvider'] ) && !empty( $_GET['loginRadiusMappingProvider'] ) ) {
					self:: unlink_provider();
				}
				Login_Radius_Common:: link_account_if_possible();
				?>
				<div class="metabox-holder columns-2" id="post-body">
					<div class="stuffbox" style="width:60%; padding-bottom:10px">
						<h3><label><?php _e( 'Link your account', 'LoginRadius' ); ?></label></h3>
						<div class="inside" style='padding:0'>
							<table  class="form-table editcomment">
								<tr>
									<td colspan="2"><?php _e( 'By adding another account, you can log in with the new account as well!', 'LoginRadius' ) ?></td>
								</tr>
								<tr>
									<td colspan="2">
										<?php
										Login_Radius_Common:: load_login_script();
										if ( !class_exists( "Login_Helper" ) ) {
											require_once LOGINRADIUS_PLUGIN_DIR . 'public/inc/login/class-login-helper.php';
										}
										Login_Helper:: get_loginradius_interface_container();
										?>
									</td>
								</tr>
								<?php
								echo Login_Radius_Common:: get_connected_providers_list();
								//echo Login_Radius_Common:: display_currently_connected_provider();
								?>
							</table>
						</div>
					</div>
				</div>
				<?php
			}
		}

		public static function unlink_provider() {
			global $user_ID, $wpdb;
			$loginRadiusMapId = trim( $_GET['loginRadiusMap'] );
			$loginRadiusMapProvider = trim( $_GET['loginRadiusMappingProvider'] );
			// remove account
			delete_user_meta( $user_ID, 'loginradius_provider_id', $loginRadiusMapId );
			if ( isset( $_GET['loginRadiusMain'] ) ) {
				delete_user_meta( $user_ID, 'loginradius_thumbnail' );
				delete_user_meta( $user_ID, 'loginradius_provider' );
			} else {
				delete_user_meta( $user_ID, 'loginradius_' . $loginRadiusMapId . '_thumbnail' );
				$wpdb->query( $wpdb->prepare( 'delete FROM ' . $wpdb->usermeta . ' WHERE user_id = %d AND meta_key = \'loginradius_mapped_provider\' AND meta_value = %s limit 1', $user_ID, $loginRadiusMapProvider ) );
				delete_user_meta( $user_ID, 'loginradius_' . $loginRadiusMapProvider . '_id', $loginRadiusMapId );
			}
		   ?>
			<script type="text/javascript">
			location.href = "<?php echo Login_Radius_Common:: get_protocol(). $_SERVER['HTTP_HOST'] . remove_query_arg( array( 'lrlinked', 'loginradius_linking', 'loginradius_post', 'loginradius_invite', 'loginRadiusMappingProvider', 'loginRadiusMap', 'loginRadiusMain' )  ) ?>";
			</script>
			<?php
			die;
		}

	}

}

Login_Radius_Admin:: get_instance();
