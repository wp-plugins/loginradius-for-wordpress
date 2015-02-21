<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'Login_Helper' ) ) {

	/**
	 * Helper class for Social Login functionality
	 */
	class Login_Helper {

		/**
		 * Verify user when user clicks on verfification link or paste that link in browser
		 */
		public static function verify_user_after_email_confirmation() {
			global $wpdb, $loginRadiusSettings;
			$verificationKey = esc_sql( trim( $_GET['loginRadiusVk'] ) );
			if ( isset( $_GET['loginRadiusProvider'] ) && trim( $_GET['loginRadiusProvider'] ) != '' ) {
				$provider = esc_sql( trim( $_GET['loginRadiusProvider'] ) );
				$userId = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key = '" . $provider . "LoginRadiusVkey' and meta_value = %s", $verificationKey ) );
				if ( !empty( $userId ) ) {
					update_user_meta( $userId, $provider . 'LrVerified', '1' );
					delete_user_meta( $userId, $provider . 'LoginRadiusVkey', $verificationKey );
				} else {
					wp_redirect( site_url() );
					exit();
				}
			} else {
				$userId = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key = 'loginradius_verification_key' and meta_value = %s", $verificationKey ) );
				if ( !empty( $userId ) ) {
					update_user_meta( $userId, 'loginradius_isVerified', '1' );
					delete_user_meta( $userId, 'loginradius_verification_key', $verificationKey );
				} else {
					wp_redirect( site_url() );
					exit();
				}
			}
			// new user notification
			if ( isset( $loginRadiusSettings['LoginRadius_sendemail'] ) && $loginRadiusSettings['LoginRadius_sendemail'] == 'sendemail' ) {
				$userPassword = wp_generate_password();
				wp_update_user( array('ID' => $userId, 'user_pass' => $userPassword) );
				wp_new_user_notification( $userId, $userPassword );
			} else {
				// notification to admin
				LR_Common::login_radius_send_verification_email( trim( get_option( 'admin_email' ) ), '', '', 'admin notification', $userId );
			}
			if ( get_user_meta( $userId, 'loginradius_status', true ) === '0' ) {
				self::login_radius_notify( __( 'Your account is currently inactive. You will be notified through email, once Administrator activates your account.', 'LoginRadius' ), 'isAccountInactive' );
			} else {
				self::login_radius_notify( __( 'Your email has been successfully verified. Now you can login into your account.', 'LoginRadius' ), 'isEmailVerified' );
			}
		}

		/**
		 * Function for displaying Front end notification
		 */
		public static function login_radius_notify( $loginRadiusMsg, $noticeType = '', $redirection = '' ) {
				$key = mt_rand();
				update_user_meta( $key, 'loginradius_tmpKey', $loginRadiusMsg );
				if ( $redirection ) {
					update_user_meta( $key, 'loginradius_tmpRedirection', $redirection );
				}
				$queryString = '?' . $noticeType . '=1&loginRadiusKey=' . $key;
				wp_redirect( site_url() . $queryString );
				exit();
		}


		/**
		 * Check if user account associated with the ID passed is verified or not.
		 */
		public static function is_socialid_exists_in_wordpress( $socialId, $provider ) {
			global $wpdb;
			$userId = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='" . $provider . "Lrid' AND meta_value = %s", $socialId ) );
			if ( !empty( $userId ) ) {     // id exists
				return $userId;
			} else {                  // id doesn't exist
				return false;
			}
		}

		/**
		 * Provide loin to user, if user is inactive, then provide notification to user
		 */
		public static function login_user( $userId, $socialId, $firstLogin = false, $isRegister = false ) {
			if ( get_user_meta( $userId, 'loginradius_status', true ) === '0' ) {
				self::login_radius_notify( __( 'Your account is currently inactive. You will be notified through email, once Administrator activates your account.', 'LoginRadius' ), 'isAccountInactive' );
				return;
			}
			// update user profile data if option is set
			if ( !$firstLogin ) {
				global $loginRadiusSettings;
				if ( isset( $loginRadiusSettings['profileDataUpdate'] ) && $loginRadiusSettings['profileDataUpdate'] == '1' ) {
					self::update_profile_data( $userId );
				}
			}
			// set the current social login id
			update_user_meta( $userId, 'loginradius_current_id', $socialId );
			self::set_cookies( $userId );
			// WP login hook
			$_user = get_user_by( 'id', $userId );
			do_action( 'wp_login', $_user->user_login, $_user );
			self::login_radius_redirect( $userId, $isRegister );
		}

		/**
		 * Register new WordPress user using data fetched from social networks.
		 */
		public static function register_user( $profileData, $loginRadiusPopup = false ) {
			global $loginRadiusSettings;

			$dummyEmail = $loginRadiusSettings['LoginRadius_dummyemail'];
			$userPassword = wp_generate_password();
			$bio = $profileData['Bio'];
			$profileUrl = $profileData['ProfileUrl'];
			$socialId = $profileData['SocialId'];
			$thumbnail = $profileData['ThumbnailImageUrl'];
			if ( isset( $socialId ) && !empty( $socialId ) ) {

				if ( !empty( $profileData['Email'] ) ) {
					$email = $profileData['Email'];
				}

				// Create username, firstname and lastname
				$usernameFirstnameLastname = explode( '|LR|', self::create_user_name( $profileData ) );
				$userName  = $usernameFirstnameLastname[0];
				$firstName = $usernameFirstnameLastname[1];
				$lastName  = $usernameFirstnameLastname[2];

				$role = get_option( 'default_role' );
				$sendemail = $loginRadiusSettings['LoginRadius_sendemail'];

				//look for user with username match
				$seperator = array(
					"dash" => '-',
					"dot" => '.',
					"space" => ' '
				);
				if ( isset( $loginRadiusSettings['username_separator'] ) ) {
					$userName = str_replace( ' ', $seperator[$loginRadiusSettings['username_separator']], $userName );
				} else {
					$userName = str_replace( ' ', '-', $userName );
				}
				$username = self::create_another_username_if_already_exists( $userName );
				$userdata = array(
					'user_login'    => $username,
					'user_pass'     => $userPassword,
					'user_nicename' => sanitize_title( $firstName ),
					'user_email'    => $email,
					'display_name'  => $firstName,
					'nickname'      => $firstName,
					'first_name'    => $firstName,
					'last_name'     => $lastName,
					'description'   => $bio,
					'user_url'      => $profileUrl,
					'role'          => $role
				);
				$user_id = wp_insert_user( $userdata );
				
				// check if error due to empty user_login
				if ( isset( $user_id->errors ) && isset( $user_id->errors['empty_user_login'] ) ) {
					$userdata['user_login'] = strtoupper( $profileData['Provider'] ) . $socialId;
					$user_id = wp_insert_user( $userdata );
				}

				// Social Profile Data
				if ( class_exists( 'LR_Social_Profile_Data_Function' ) ) {
					$social_profile_data_object = new LR_Social_Profile_Data_Function();
					$social_profile_data_object->save_profile_data( $user_id, $profileData );
				}

				// Mailchimp
				if ( file_exists( LR_ROOT_DIR . 'lr-mailchimp/lr-mailchimp.php' ) ) {
					include( LR_MAILCHIMP_DIR . 'includes/display/mailchimp.php' );
				}

				// Delete temporary data.
				self::login_radius_delete_temporary_data( $profileData );

				if ( ! is_wp_error( $user_id ) ) {
					if ( !empty( $socialId ) ) {
						update_user_meta( $user_id, 'loginradius_provider_id', $socialId );
					}
					if ( !empty( $thumbnail ) ) {
						update_user_meta( $user_id, 'loginradius_thumbnail', $thumbnail );
					}
					if ( !empty( $profileData['PictureUrl'] ) ) {
						update_user_meta( $user_id, 'loginradius_picture', $profileData['PictureUrl'] );
					}
					if ( !empty( $profileData['Provider'] ) ) {
						update_user_meta( $user_id, 'loginradius_provider', $profileData['Provider'] );
					}
					if ( $loginRadiusPopup ) {
						$loginRadiusKey = $user_id . time() . mt_rand();
						update_user_meta( $user_id, 'loginradius_verification_key', $loginRadiusKey );
						update_user_meta( $user_id, 'loginradius_isVerified', '0' );
						LR_Common::login_radius_send_verification_email( $email, $loginRadiusKey );
						// set status
						if ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == '0' ) {
							update_user_meta( $user_id, 'loginradius_status', '0' );
						} else {
							update_user_meta( $user_id, 'loginradius_status', '1' );
						}
						self::login_radius_notify( __( 'Confirmation link has been sent to your email address. Please verify your email by clicking on confirmation link.', 'LoginRadius' ), 'isConfirmationLinkSent' );
						return;
					}
					if ( ( $sendemail == 'sendemail' ) ) {
						if ( ( $dummyEmail == 'notdummyemail' ) && ( $loginRadiusPopup == true ) ) {

						} else {
							wp_new_user_notification( $user_id, $userPassword );
						}
					} else {
						// notification to admin
						LR_Common::login_radius_send_verification_email( trim( get_option( 'admin_email' ) ), '', '', 'admin notification', $user_id );
					}
					// set status if option is enabled
					if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
						if ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == '0' ) {
							update_user_meta( $user_id, 'loginradius_status', '0' );
							self::login_radius_notify( __( 'Your account is currently inactive. You will be notified through email, once Administrator activates your account.', 'LoginRadius' ), 'isAccountInactive' );
							exit();
						} else {
							update_user_meta( $user_id, 'loginradius_status', '1' );
						}
					}
					self::login_user( $user_id, $socialId, true, true );
				} else {
					self::login_radius_redirect( $user_id );
				}
			}
		}

		/**
		 * Adding index to username if username already exists in WordPress
		 */
		public static function create_another_username_if_already_exists( $name ) {
			$isUserNameExists = true;
			$index = 0;
			while ( $isUserNameExists == true ) {
				if ( username_exists( $name ) != 0 ) {
					$index++;
					$name = $name . $index;
				} else {
					$isUserNameExists = false;
				}
			}
			return $name;
		}

		/**
		 * Update user profile data after login each time, if option for updating profile is selected
		 */
		public static function update_profile_data( $userId ) {
			// create username, firstname and lastname
			$usernameFirstnameLastname = explode( '|LR|', self::create_user_name( Social_Login::$loginRadiusProfileData ) );
			$firstName = $usernameFirstnameLastname[1];
			$lastName = $usernameFirstnameLastname[2];
			// fields going to be updated
			$profileData = array(
				'ID' => $userId,
				'first_name' => $firstName,
				'last_name' => $lastName,
				'description' => Social_Login:: $loginRadiusProfileData['Bio'],
				'user_url' => Social_Login:: $loginRadiusProfileData['ProfileUrl'],
			);
			if ( wp_update_user( $profileData ) ) {
				update_user_meta( $userId, 'loginradius_thumbnail', Social_Login:: $loginRadiusProfileData['ThumbnailImageUrl'] );
				update_user_meta( $userId, 'loginradius_picture', Social_Login:: $loginRadiusProfileData['ImageUrl'] );
			}
		}

		/**
		 * Generate a dummy email if auto generate email option is selected as plugin settings
		 */
		public static function generate_dummy_email( $profileData ) {
			$tempArray = array('twitter', 'linkedin', 'renren');
			if ( in_array( $profileData['Provider'], $tempArray ) ) {
				return $profileData['SocialId'] . '@' . $profileData['Provider'] . '.com';
			} else {
				$email = substr( $profileData['SocialId'], 7 );
				$tempEmail = str_replace( '/', '_', $email );
				return str_replace( '.', '_', $tempEmail ) . '@' . $profileData['Provider'] . '.com';
			}
		}

		/**
		 * Store temporary data in database before displaying email popup
		 */
		public static function store_temporary_data( $profileData ) {

			$tmpdata = array();
			$tmpdata['tmpsession']            = isset( $profileData['UniqueId'] ) ? $profileData['UniqueId'] : '';
			$tmpdata['tmpid']                 = isset( $profileData['SocialId'] ) ? $profileData['SocialId'] : '';
			$tmpdata['tmpFullName']           = isset( $profileData['FullName'] ) ? $profileData['FullName'] : '';
			$tmpdata['tmpProfileName']        = isset( $profileData['ProfileName'] ) ? $profileData['ProfileName'] : '';
			$tmpdata['tmpNickName']           = isset( $profileData['NickName'] ) ? $profileData['NickName'] : '';
			$tmpdata['tmpFname']              = isset( $profileData['FirstName'] ) ? $profileData['FirstName'] : '';
			$tmpdata['tmpLname']              = isset( $profileData['LastName'] ) ? $profileData['LastName'] : '';
			$tmpdata['tmpProvider']           = isset( $profileData['Provider'] ) ? $profileData['Provider'] : '';
			$tmpdata['tmpThumbnailImageUrl']  = isset( $profileData['ThumbnailImageUrl'] ) ? $profileData['ThumbnailImageUrl'] : '';
			$tmpdata['tmpaboutme']            = isset( $profileData['Bio'] ) ? $profileData['Bio'] : '';
			$tmpdata['tmpwebsite']            = isset( $profileData['ProfileUrl'] ) ? $profileData['ProfileUrl'] : '';
			$tmpdata['tmpEmail']              = isset( $profileData['Email'] ) ? $profileData['Email'] : '';
			$tmpdata['tmpGender']             = isset( $profileData['Gender'] ) ? $profileData['Gender'] : '';
			$tmpdata['tmpBirthDate']          = isset( $profileData['BirthDate'] ) ? $profileData['BirthDate'] : '';
			$tmpdata['tmpPhoneNumber']        = isset( $profileData['PhoneNumber'] ) ? $profileData['PhoneNumber'] : '';
			$tmpdata['tmpRelationshipStatus'] = isset( $profileData['RelationshipStatus'] ) ? $profileData['RelationshipStatus'] : '';
			$tmpdata['tmpCity']               = isset( $profileData['City'] ) ? $profileData['City'] : '';
			$tmpdata['tmpPostalCode']         = isset( $profileData['PostalCode'] ) ? $profileData['PostalCode'] : '';
			$tmpdata['tmpToken']              = isset( $_REQUEST['token'] ) ? $_REQUEST['token'] : '';

			$uni_id = $tmpdata['tmpsession'];
			$uniqu_id = explode( '.', $uni_id );
			$unique_id = $uniqu_id[1];
			if ( !is_numeric( $unique_id ) ) {
				$unique_id = rand();
			}
			foreach ( $tmpdata as $key => $value ) {
				update_user_meta( $unique_id, $key, $value );
			}
			return $profileData['UniqueId'];
		}

		/**
		 * Delete temporary data, which was saved in case email was not provided by Social Network
		 */
		public static function login_radius_delete_temporary_data( $profileData ) {
			$uni_id = $profileData['UniqueId'];
			$uniqu_id = explode( '.', $uni_id );
			$unique_id = $uniqu_id[1];
			$keys = array( 'tmpsession', 'tmpid', 'tmpFullName', 'tmpProfileName', 'tmpNickName', 'tmpFname', 'tmpLname', 'tmpProvider', 'tmpThumbnailImageUrl', 'tmpaboutme', 'tmpwebsite', 'tmpEmail', 'tmpGender', 'tmpBirthDate ', 'tmpPhoneNumber', 'tmpRelationshipStatus', 'tmpCity', 'tmpPostalCode', 'tmpToken' );
			foreach ( $keys as $key ) {
				delete_user_meta( $unique_id, $key );
			}
		}

		/**
		 * Get redirection URL based on Social Login settings.
		 */
		public static function get_redirect_url( $user_id, $register = false ) {
			global $loginRadiusSettings, $loginRadiusLoginIsBpActive, $wp;
			
			if ( $register ) {
				$loginRedirect = $loginRadiusSettings['LoginRadius_regRedirect'];
				$customRedirectUrl = isset( $loginRadiusSettings['custom_regRedirect'] ) ? trim( $loginRadiusSettings['custom_regRedirect'] ) : '';
			} else {
				$loginRedirect = $loginRadiusSettings['LoginRadius_redirect'];
				$customRedirectUrl = isset( $loginRadiusSettings['custom_redirect'] ) ? trim( $loginRadiusSettings['custom_redirect'] ) : '';
			}

			$redirectionUrl = site_url();

			if ( ! empty( $_GET['redirect_to'] ) ) {
				$redirectionUrl = $_GET['redirect_to'];
			} else {
				if ( isset( $loginRedirect ) ) {
					switch ( strtolower( $loginRedirect ) ) {
						case 'homepage':
							$redirectionUrl = site_url() . '/';
							break;
						case 'dashboard':
							$redirectionUrl = admin_url();
							break;
						case 'bp':
							if ( $loginRadiusLoginIsBpActive ) {
								$redirectionUrl = bp_core_get_user_domain( $user_id );
							} else {
								$redirectionUrl = admin_url();
							}
							break;
						case 'custom':
							if ( isset( $loginRedirect ) && strlen( $customRedirectUrl ) > 0 ) {
								$redirectionUrl = trim( $customRedirectUrl );

								if( strpos($redirectionUrl, 'http' ) === false ) {
									$redirectionUrl = 'http://' . $redirectionUrl;
								}
							} else {
								$redirectionUrl = site_url() . '/';
							}
							break;
						case 'samepage':
						default:
							$redirectionUrl = LR_Common:: get_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
							break;
					}
				}
			}
			return $redirectionUrl;
		}

		/**
		 * Redirect users after login and register according to plugin settings.
		 */
		public static function login_radius_redirect( $user_id, $register ) {
			
			$redirectionUrl = self::get_redirect_url( $user_id, $register = false );
			wp_redirect( $redirectionUrl );
			exit();
		}

		/**
		 * Get callback parameter of the social login iframe.
		 */
		public static function get_callback_url_for_redirection( $http ) {
			$loc = urlencode( $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			if ( urldecode( $loc ) == wp_login_url()OR urldecode( $loc ) == site_url() . '/wp-login.php?action=register' OR urldecode( $loc ) == site_url() . '/wp-login.php?loggedout=true' ) {
				$loc = site_url() . '/';
			} elseif ( isset( $_GET['redirect_to'] ) && ( urldecode( $_GET['redirect_to'] ) == admin_url() ) ) {
				$loc = site_url() . '/';
			} elseif ( isset( $_GET['redirect_to'] ) ) {
				if ( self:: validate_url( urldecode( $_GET['redirect_to'] ) ) && ( strpos( urldecode( $_GET['redirect_to'] ), 'http://' ) !== false || strpos( urldecode( $_GET['redirect_to'] ), 'https://' ) !== false ) ) {
					$loc = $_GET['redirect_to'];
				} else {
					$loc = site_url() . '/';
				}
			} else {
				$loc = urlencode( $http . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
			}
			return $loc;
		}

		/**
		 * Create username, firstname and lastname with profile fetched from Social Networks
		 */
		public static function create_user_name( $profileData ) {
			$username = '';
			$firstName = '';
			$lastName = '';
			if ( !empty( $profileData['FirstName'] ) && !empty( $profileData['LastName'] ) ) {
				$username = $profileData['FirstName'] . ' ' . $profileData['LastName'];
				$firstName = $profileData['FirstName'];
				$lastName = $profileData['LastName'];
			} elseif ( !empty( $profileData['FullName'] ) ) {
				$username = $profileData['FullName'];
				$firstName = $profileData['FullName'];
			} elseif ( !empty( $profileData['ProfileName'] ) ) {
				$username = $profileData['ProfileName'];
				$firstName = $profileData['ProfileName'];
			} elseif ( !empty( $profileData['NickName'] ) ) {
				$username = $profileData['NickName'];
				$firstName = $profileData['NickName'];
			} elseif ( !empty( $profileData['Email'] ) ) {
				$user_name = explode( '@', $profileData['Email'] );
				$username = $user_name[0];
				$firstName = str_replace( '_', ' ', $user_name[0] );
			} else {
				$username = $profileData['SocialId'];
				$firstName = $profileData['SocialId'];
			}
			return $username . '|LR|' . $firstName . '|LR|' . $lastName;
		}

		/**
		 * Set cookies to provide login to user.
		 */
		public static function set_cookies( $userId = 0, $remember = true ) {
			wp_clear_auth_cookie();
			wp_set_auth_cookie( $userId, $remember );
			wp_set_current_user( $userId );
			return true;
		}

		/**
		 * Filter the data fetched from LoginRadius.
		 */
		public static function filter_loginradius_data_for_wordpress_use( $userProfileObject ) {

			$profileData['UniqueId']           = uniqid( 'LoginRadius_', true );
			$profileData['SocialId']           = isset( $userProfileObject->ID ) ? $userProfileObject->ID : '';
			$profileData['ProfileName']        = isset( $userProfileObject->ProfileName ) && $userProfileObject->ProfileName != 'null' ? $userProfileObject->ProfileName : '';
			$profileData['NickName']           = isset( $userProfileObject->NickName ) && $userProfileObject->NickName != 'null' ? $userProfileObject->NickName : '';
			$profileData['FirstName']          = isset( $userProfileObject->FirstName ) && $userProfileObject->FirstName != 'null' ? $userProfileObject->FirstName : '';
			$profileData['LastName']           = isset( $userProfileObject->LastName ) && $userProfileObject->LastName != 'null' ? $userProfileObject->LastName : '';
			$profileData['Provider']           = isset( $userProfileObject->Provider ) && $userProfileObject->Provider != 'null' ? $userProfileObject->Provider : '';
			$profileData['ThumbnailImageUrl']  = isset( $userProfileObject->ThumbnailImageUrl ) && $userProfileObject->ThumbnailImageUrl != 'null' ? trim( $userProfileObject->ThumbnailImageUrl ) : '';
			$profileData['ImageUrl']           = isset( $userProfileObject->ImageUrl ) && $userProfileObject->ImageUrl != 'null' ? trim( $userProfileObject->ImageUrl ) : '';
			$profileData['Bio']                = isset( $userProfileObject->About ) && $userProfileObject->About != 'null' ? $userProfileObject->About : '';
			$profileData['ProfileUrl']         = isset( $userProfileObject->ProfileUrl ) && $userProfileObject->ProfileUrl != 'null' ? $userProfileObject->ProfileUrl : '';

			if ( empty( $profileData['ThumbnailImageUrl'] ) && $profileData['Provider'] == 'facebook' ) {
				$profileData['ThumbnailImageUrl'] = self::facebook_profile_pic_creation( $profileData['SocialId'] );
			}

			// Special Data used for popup form (Email, Gender, BirthDate, RelationshipStatus, PhoneNumber, PostalCode, City)
			
			$profileData['Gender']             = isset( $userProfileObject->Gender ) && $userProfileObject->Gender != 'null' ? $userProfileObject->Gender : '';
			$profileData['BirthDate']          = isset( $userProfileObject->BirthDate ) && $userProfileObject->BirthDate != 'null' ? $userProfileObject->BirthDate : '';
			$profileData['RelationshipStatus'] = isset( $userProfileObject->RelationshipStatus ) && $userProfileObject->RelationshipStatus != 'null' ? $userProfileObject->RelationshipStatus : '';
			$profileData['Email']              = isset( $userProfileObject->Email[0]->Value ) && $userProfileObject->Email[0]->Value != 'null' ? $userProfileObject->Email[0]->Value : '';
			$profileData['PhoneNumber']        = isset( $userProfileObject->PhoneNumbers[0]->PhoneNumber ) && $userProfileObject->PhoneNumbers[0]->PhoneNumber != 'null' ? $userProfileObject->PhoneNumbers[0]->PhoneNumber : '';
			$profileData['PostalCode']         = isset( $userProfileObject->Addresses[0]->PostalCode ) && $userProfileObject->Addresses[0]->PostalCode != 'null' ? $userProfileObject->Addresses[0]->PostalCode : '';
			if( isset( $userProfileObject->Addresses[0]->City ) && $userProfileObject->Addresses[0]->City != 'null' ) {
				$profileData['City'] = $userProfileObject->Addresses[0]->City;
			} else {
				$profileData['City'] = ! empty( $userProfileObject->City ) && $userProfileObject->City != 'null' && $userProfileObject->City != 'unknown' ? $userProfileObject->City : '';
			}

			return $profileData;
		}

		/**
		 * create facebook profile pic link for using as avatar
		 */
		public static function facebook_profile_pic_creation( $socialId ) {
			$fbThumbnail = 'http://graph.facebook.com/' . $socialId . '/picture?type=square';
			return $fbThumbnail;
		}

		/**
		 * Validate url.
		 */
		public static function validate_url( $url ) {
			$validUrlExpression = '/^ ( http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/| ) ?[a-z0-9_\-]+[a-z0-9_\-\.]+\.[a-z]{2,4} ( \/+[a-z0-9_\.\-\/]* ) ?$/i';
			return ( bool ) preg_match( $validUrlExpression, $url );
		}

		/**
		 * Display Social Login interface.
		 */
		public static function display_social_login_interface( $newInterface = false ) {
			global $loginRadiusSettings, $loginradius_api_settings;
			$title = isset( $loginRadiusSettings['LoginRadius_title'] ) ? $loginRadiusSettings['LoginRadius_title'] : '';
			if ( !is_user_logged_in() ) {
				if ( $newInterface ) {
					$result = "<div style='margin-bottom: 3px;'>";
					if ( trim( $loginradius_api_settings['LoginRadius_apikey'] ) != '' && trim( $loginradius_api_settings['LoginRadius_secret'] ) != '' ) {
						$result .= '<label>' . $title . '</label>';
					}
					$result .= '</div>' . self:: get_loginradius_interface_container( $newInterface );
					return $result;
				} else {
					?>
					<div>
						<div style='margin-bottom: 3px;'><?php if ( trim( $loginradius_api_settings['LoginRadius_apikey'] ) != '' && trim( $loginradius_api_settings['LoginRadius_secret'] ) != '' ) { ?><label><?php _e( $title, 'LoginRadius' ) ?></label><?php } ?></div>
					<?php
					self::get_loginradius_interface_container( $newInterface );
					?>
					</div>
					<?php
				}
			}
		}

		/**
		 * Add container for Social Login Interface
		 */
		public static function get_loginradius_interface_container( $isLinkingWidget = false ) {
			global $loginRadiusSettings, $loginRadiusObject, $loginradius_api_settings;
			$loginRadiusApiKey = isset( $loginradius_api_settings['LoginRadius_apikey'] ) ? trim( $loginradius_api_settings['LoginRadius_apikey'] ) : '';
			$loginRadiusSecret = isset( $loginradius_api_settings['LoginRadius_secret'] ) ? trim( $loginradius_api_settings['LoginRadius_secret'] ) : '';
			$loginRadiusError = "<div style='background-color: #FFFFE0;border:1px solid #E6DB55;padding:5px;'><p style ='color:red;'>Your LoginRadius API key or secret is not valid, please correct it or contact LoginRadius support at <b><a href ='http://www.loginradius.com' target = '_blank'>www.LoginRadius.com</a></b></p></div>";
			
			if ( empty( $loginRadiusSecret ) ) {
				$loginRadiusResult = '';
			} elseif ( !$loginRadiusObject->loginradius_validate_key( $loginRadiusApiKey ) || !$loginRadiusObject->loginradius_validate_key( $loginRadiusSecret ) ) {
				$loginRadiusResult = $loginRadiusError;
			} else {
				$loginRadiusResult = "<div class='interfacecontainerdiv'></div>";
			}
			// return/print interface HTML
			if ( !$isLinkingWidget ) {
				echo $loginRadiusResult;
			} else {
				return $loginRadiusResult;
			}
		}

		/**
		 * Unlink social provider
		 */
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
			location.href = "<?php echo LR_Common:: get_protocol(). $_SERVER['HTTP_HOST'] . remove_query_arg( array( 'lrlinked', 'loginradius_linking', 'loginradius_post', 'loginradius_invite', 'loginRadiusMappingProvider', 'loginRadiusMap', 'loginRadiusMain' )  ) ?>";
			</script>
			<?php
			die;
		}

		/**
		 * Delete the field holding current provider information.
		 */
		public static function delete_social_login_meta() {
			global $user_ID;
			delete_user_meta( $user_ID, 'loginradius_current_id' );
		}

		/*
		 * Function is called if buddypress is active, it sets
		 * LoginRadius global variable $loginRadiusLoginIsBpActive to true
		 */
		public static function set_budddy_press_status_variable() {
			global $loginRadiusLoginIsBpActive;
			$loginRadiusLoginIsBpActive = true;
		}

		/*
		 * Function is called when email popup is submitted.
		 * Takes appropriate action for submit and cancel button
		 */

		public static function response_to_popup_submission() {
			global $wpdb, $loginRadiusSettings, $lr_social_profile_data_settings;

			if ( $_POST['LoginRadius_popupSubmit'] == 'Submit' ) {

			 	$popupenable = false;

                if ( isset($lr_social_profile_data_settings['show_email'] ) && $lr_social_profile_data_settings['show_email'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_gender'] ) && $lr_social_profile_data_settings['show_gender'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_birthdate'] ) && $lr_social_profile_data_settings['show_birthdate'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_phonenumber'] ) && $lr_social_profile_data_settings['show_phonenumber'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_city'] ) && $lr_social_profile_data_settings['show_city'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_postalcode'] ) && $lr_social_profile_data_settings['show_postalcode'] == '1' ) {
                    $popupenable = true;
                }elseif ( isset($lr_social_profile_data_settings['show_relationshipstatus'] ) && $lr_social_profile_data_settings['show_relationshipstatus'] == '1' ) {
                    $popupenable = true;
                }

			    if( isset($lr_social_profile_data_settings['enable_custom_popup'] ) && $lr_social_profile_data_settings['enable_custom_popup'] == '1' && $popupenable ) {
					
			    	$session = isset( $_POST['session'] ) ? trim( $_POST['session'] ) : '';
			    	$split_session = explode( '.', $session );
			    	$unique_id = $split_session[1];

			    	$valid = true;
			    	if ( isset($lr_social_profile_data_settings['show_email'] ) && $lr_social_profile_data_settings['show_email'] == '1' ) {
                        $email = trim( $_POST['email'] );
                        if( empty( $email ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpEmail', $email );
                    }

                    if ( isset($lr_social_profile_data_settings['show_gender'] ) && $lr_social_profile_data_settings['show_gender'] == '1' ) {
                        $gender = isset( $_POST['gender'] ) ? $_POST['gender'] : '';
                        if( empty( $gender ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpGender', $gender );
                    }

                    if ( isset($lr_social_profile_data_settings['show_birthdate'] ) && $lr_social_profile_data_settings['show_birthdate'] == '1' ) {
                        $birthdate = isset( $_POST['birthdate'] ) ? $_POST['birthdate'] : '';
                        if( empty( $birthdate ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpBirthDate', $birthdate );
                    }

                    if ( isset($lr_social_profile_data_settings['show_phonenumber'] ) && $lr_social_profile_data_settings['show_phonenumber'] == '1' ) {
                        $phonenumber = isset( $_POST['phonenumber'] ) ? trim( $_POST['phonenumber'] ) : '';
                        if( empty( $phonenumber ) ){
                        	$valid = false;
                        }
                    	update_user_meta( $unique_id, 'tmpPhoneNumber', $phonenumber );
                    }

                    if ( isset($lr_social_profile_data_settings['show_city'] ) && $lr_social_profile_data_settings['show_city'] == '1' ) {
                        $city = isset( $_POST['city'] ) ? trim( $_POST['city'] ) : '';
                        if( empty( $city ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpCity', $city );
                    }

                    if ( isset($lr_social_profile_data_settings['show_postalcode'] ) && $lr_social_profile_data_settings['show_postalcode'] == '1' ) {
                        $postalcode = isset( $_POST['postalcode'] ) ? trim( $_POST['postalcode'] ) : '';
                        if( empty( $postalcode ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpPostalCode', $postalcode );
                    }

                    if ( isset($lr_social_profile_data_settings['show_relationshipstatus'] ) && $lr_social_profile_data_settings['show_relationshipstatus'] == '1' ) {
                        $relationshipstatus = isset( $_POST['relationshipstatus'] ) ? trim( $_POST['relationshipstatus'] ) : '';
                        if( empty( $relationshipstatus ) ){
                        	$valid = false;
                        }
                        update_user_meta( $unique_id, 'tmpRelationshipStatus', $relationshipstatus );
                    }

                    $loginRadiusTempUserId = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key=\'tmpsession\' AND meta_value = %s', $_POST['session'] ) );

                    if ( $valid ) {
                
                    	$profileData = self::fetch_temp_data_from_usermeta( $loginRadiusTempUserId );
                    	$loginRadiusProvider = get_user_meta( $loginRadiusTempUserId, 'tmpProvider', true );

						if ( ! empty( $session ) ) {
							
							// Check for existing registered account
							if ( $loginRadiusUserId = email_exists( $profileData['Email'] ) ) {

								// Email exists and matches registered provider - Re-register user
								if ( get_user_meta( $loginRadiusUserId, 'loginradius_provider', true ) == $loginRadiusProvider ) {

									$directorySeparator = DIRECTORY_SEPARATOR;
									require_once( getcwd() . $directorySeparator . 'wp-admin' . $directorySeparator . 'inc' . $directorySeparator . 'user.php' );
									wp_delete_user( $loginRadiusUserId );
									// New user.
									self::register_user( $profileData, true );
									return;
								} else {
									// Email is already registered!
									$queryString = '?lrid=' . $session;
									wp_redirect( site_url() . $queryString . '&LoginRadiusMessage="emailExists"' );
									exit();
								}
							} else {
								// New user.
								self::login_radius_delete_temporary_data( array('UniqueId' => trim( $_POST['session'] )) );
								self::register_user( $profileData, true );
							}
						}
					} else {

						$queryString = '?lrid=' . $session;
						wp_redirect( site_url() . $queryString . '&LoginRadiusMessage="formData"' );
						exit();
					}
			    } else {
			    		// Normal Flow - Get required email from popup.
					    // If submit button is clicked.
						$loginRadiusEmail = esc_sql( trim( $_POST['email'] ) );

						$profileData = array();
						$loginRadiusTempUserId = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key=\'tmpsession\' AND meta_value = %s', $_POST['session'] ) );
						$profileData['UniqueId'] = get_user_meta( $loginRadiusTempUserId, 'tmpsession', true );
						$loginRadiusProvider = get_user_meta( $loginRadiusTempUserId, 'tmpProvider', true );
						update_user_meta( $loginRadiusTempUserId, 'tmpEmail', $loginRadiusEmail);

						if ( isset( $profileData['UniqueId'] ) && isset( $_POST['session'] ) && $profileData['UniqueId'] == $_POST['session'] ) {
							if ( $loginRadiusUserId = email_exists( $loginRadiusEmail ) ) {

								if ( get_user_meta( $loginRadiusUserId, 'loginradius_provider', true ) == $loginRadiusProvider ) {

									require_once( getcwd() . '\wp-admin\includes\user.php' );
									wp_delete_user( $loginRadiusUserId );
									
									// New user.
									$profileData = self::fetch_temp_data_from_usermeta( $loginRadiusTempUserId );

									self::register_user( $profileData, true );
									return;
								} else {
									// Email is already registered!
									$queryString = '?lrid=' . $profileData['UniqueId'];
									wp_redirect( site_url() . $queryString . '&LoginRadiusMessage="emailExists"' );
									exit();
								}
							} else {
								// New user.
								$profileData = self:: fetch_temp_data_from_usermeta( $loginRadiusTempUserId );
								self::register_user( $profileData, true );
							}
						}
				}	
			} else {
				self::login_radius_delete_temporary_data( array('UniqueId' => trim( $_POST['session'] )) );
				wp_redirect( site_url() );
				exit();
			}	
		}

		/*
		 * Fetch temporary data, which was saved in case email was not provided by Social Network
		 */
		public static function fetch_temp_data_from_usermeta( $loginRadiusTempUserId ) {
			$profileData['UniqueId']           = get_user_meta( $loginRadiusTempUserId, 'tmpsession', true );
			$profileData['SocialId']           = get_user_meta( $loginRadiusTempUserId, 'tmpid', true );
			$profileData['FullName']           = get_user_meta( $loginRadiusTempUserId, 'tmpFullName', true );
			$profileData['ProfileName']        = get_user_meta( $loginRadiusTempUserId, 'tmpProfileName', true );
			$profileData['NickName']           = get_user_meta( $loginRadiusTempUserId, 'tmpNickName', true );
			$profileData['FirstName']          = get_user_meta( $loginRadiusTempUserId, 'tmpFname', true );
			$profileData['LastName']           = get_user_meta( $loginRadiusTempUserId, 'tmpLname', true );
			$profileData['Provider']           = get_user_meta( $loginRadiusTempUserId, 'tmpProvider', true );
			$profileData['ThumbnailImageUrl']  = get_user_meta( $loginRadiusTempUserId, 'tmpThumbnailImageUrl', true );
			$profileData['Bio']                = get_user_meta( $loginRadiusTempUserId, 'tmpaboutme', true );
			$profileData['ProfileUrl']         = get_user_meta( $loginRadiusTempUserId, 'tmpwebsite', true );
			$profileData['Email']              = get_user_meta( $loginRadiusTempUserId, 'tmpEmail', true );
			$profileData['Gender']             = get_user_meta( $loginRadiusTempUserId, 'tmpGender', true );
			$profileData['BirthDate']          = get_user_meta( $loginRadiusTempUserId, 'tmpBirthDate', true );
			$profileData['PhoneNumber']        = get_user_meta( $loginRadiusTempUserId, 'tmpPhoneNumber', true );
			$profileData['RelationshipStatus'] = get_user_meta( $loginRadiusTempUserId, 'tmpRelationshipStatus', true );
			$profileData['City']               = get_user_meta( $loginRadiusTempUserId, 'tmpCity', true );
			$profileData['PostalCode']         = get_user_meta( $loginRadiusTempUserId, 'tmpPostalCode', true );
			$profileData['Token']              = get_user_meta( $loginRadiusTempUserId, 'tmpToken', true );
			return $profileData;
		}

		/**
		 * This function renders SOcial Login Interface on WordPress registration page
		 */
		public static function social_login_interface_beside_registration() {
			global $loginRadiusSettings;
			$lrLogin = ( $loginRadiusSettings['LoginRadius_loginform'] == 1 ) && ( $loginRadiusSettings['LoginRadius_loginformPosition'] == 'beside' );
			$lrRegister = ( $loginRadiusSettings['LoginRadius_regform'] == 1 ) && ( $loginRadiusSettings['LoginRadius_regformPosition'] == 'beside' );
			$script = '<script type="text/javascript">';
			$script .= 'jQuery ( document ) .ready ( function(){ ' .
					'var loginDiv = jQuery ( "div#login" );';
			if ( $lrLogin && $lrRegister ) {
				$script .= 'if ( jQuery ( "#loginform" ) .length || jQuery ( "#registerform" ) .length || jQuery ( "#lostpasswordform" ) .length )
			{
				jQuery ( "#loginform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "#lostpasswordform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "#registerform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "div#login" ) .css ( \'width\', \'910px\' );
				loginDiv.append ( "<div class=\"login-sep-text float-left\"><h3>OR</h3></div>" );

				if ( jQuery ( "#registerform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
				} else if ( jQuery ( "#lostpasswordform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#lostpasswordform" ) .css ( "height", "270px" );
				} else if ( jQuery ( "#loginform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#loginform" ) .css ( "height", "270px" );
				}
			}';
			} elseif ( $lrLogin ) {
				$script .= 'if ( jQuery ( "#loginform" ) .length || jQuery ( "#lostpasswordform" ) .length ) {
				jQuery ( "#loginform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "#lostpasswordform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "div#login" ) .css ( \'width\', \'910px\' );
				loginDiv.append ( "<div class=\"login-sep-text float-left\"><h3>OR</h3></div>" );

				if ( jQuery ( "#lostpasswordform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#lostpasswordform" ) .css ( "height", "270px" );
				} else if ( jQuery ( "#loginform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#loginform" ) .css ( "height", "270px" );
				}
			}';
			} elseif ( $lrRegister ) {
				$script .= 'if ( jQuery ( "#registerform" ) .length || jQuery ( "#lostpasswordform" ) .length ) {
				jQuery ( "#registerform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "#lostpasswordform" ) .wrap ( "<div style=\'float:left; width:400px\'></div>" ) .after ( jQuery ( "#nav" )  ) .after ( jQuery ( "#backtoblog" )  );
				jQuery ( "div#login" ) .css ( \'width\', \'910px\' );
				loginDiv.append ( "<div class=\"login-sep-text float-left\"><h3>OR</h3></div>" );

				if ( jQuery ( "#lostpasswordform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#lostpasswordform" ) .css ( "height", "270px" );
				} else if ( jQuery ( "#registerform" ) .length ) {
					loginDiv.append ( "<div class=\"login-panel-lr\" style=\"min-height:270px\" >' . self:: display_social_login_interface( true ) . '</div>" );
					jQuery ( "#loginform" ) .css ( "height", "270px" );
				}
			}';
			}

			$script .= ' } ); </script>';
			echo $script;
		}
	}		
}