<?php
global $login_options;
$login_options = array(
			'LoginRadius_loginform'				=> '1',
			'LoginRadius_loginformPosition'		=> 'embed',
			'LoginRadius_regform'				=> '1',
			'LoginRadius_regformPosition'		=> 'embed',
			'LoginRadius_commentEnable'			=> '0',
			'LoginRadius_numColumns'			=> '4',
			'LoginRadius_noProvider'			=> '1',
			'LoginRadius_enableUserActivation'	=> '1',
			'delete_options'					=> '1',
			'username_separator'				=> 'dash',
			'LoginRadius_redirect'				=> 'samepage',
			'LoginRadius_regRedirect'			=> 'samepage',
			'LoginRadius_loutRedirect'			=> 'homepage',
			'LoginRadius_socialavatar'			=> 'socialavatar',
			'LoginRadius_title'					=> 'Log in via a social account',
			'enable_degugging'					=> '0',
			'LoginRadius_sendemail'				=> 'notsendemail',
			'LoginRadius_dummyemail'			=> 'notdummyemail',
			'msg_email'							=> 'Unfortunately we could not retrieve email from your @provider account. Please enter your email in the form below in order to continue.',
			'msg_existemail'					=> 'This email is already registered. Please log in with this email and link any additional ID providers via account linking on your profile page.'
);

/**
 * class responsible for setting default settings for LoginRadius Social Login and Share plugin
 */
class Login_Radius_Install {

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Function for adding default plugin settings at activation
	 */
	public static function set_default_options() {
		global $login_options;

		if ( version_compare( get_bloginfo('version'), LOGINRADIUS_MIN_WP_VERSION, '<') )  {
			$message = "Plugin could not be activated because ";
			$message .= "WordPress version is lower than ";
			$message .= LOGINRADIUS_MIN_WP_VERSION;
			die( $message );
		}

		if ( ! get_option( 'LoginRadius_settings' ) ) {
			// If plugin loginradius_db_version option not exist, it means plugin is not latest and update options.

			update_option( 'LoginRadius_settings', $login_options );
			update_option( 'loginradius_db_version', LOGINRADIUS_SOCIALLOGIN_VERSION );
		}

		if( ! get_option( 'LoginRadius_API_settings' ) ) {
			$api_options = array(
					'LoginRadius_apikey'        => '',
					'LoginRadius_secret'        => '',
					'scripts_in_footer'         => '1'
			);
			
			if( get_option( 'LoginRadius_sharing_settings' ) ) {
				$loginradius_existing_settings = get_option( 'LoginRadius_sharing_settings' );
				if( isset($loginradius_existing_settings['LoginRadius_apikey']) && !empty($loginradius_existing_settings['LoginRadius_apikey']) ) {
					$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
					
				}
			}

			// Get Existing API key for update.
			if( get_option( 'LoginRadius_settings' ) ) {
				$loginradius_existing_settings = get_option( 'LoginRadius_settings' );
				if( isset( $loginradius_existing_settings['LoginRadius_apikey'] ) && ! empty( $loginradius_existing_settings['LoginRadius_apikey'] ) ) {
					$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
				}
				if( isset( $loginradius_existing_settings['LoginRadius_secret'] ) && ! empty( $loginradius_existing_settings['LoginRadius_secret'] ) ) {
					$api_options['LoginRadius_secret'] = $loginradius_existing_settings['LoginRadius_secret'];
				}
			}
			update_option( 'LoginRadius_API_settings', $api_options );
		}
	}

	/**
	 * Function to reset Social Login options to default.
	 */
	public static function reset_loginradius_login_options() {
		global $loginRadiusSettings, $loginradius_api_settings, $login_options;

		$loginradius_new_api_settings['scripts_in_footer'] = '1';

		update_option( 'LoginRadius_settings', $login_options );
		update_option( 'LoginRadius_API_settings', $loginradius_new_api_settings );

		// Get LoginRadius plugin options
		$loginRadiusSettings = get_option( 'LoginRadius_settings' );
		$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
	}
}
