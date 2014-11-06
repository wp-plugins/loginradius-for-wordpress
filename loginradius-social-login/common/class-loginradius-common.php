<?php

// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

if ( !class_exists( 'Login_Radius_Common' ) ) {
    /**
     * This class contains method which are used by admin as well as front
     */
    class Login_Radius_Common {

        /**
         * Check if ID can be link or not. if yes the link account.
         */
        public static function link_account_if_possible() {
            global $loginRadiusObject, $wpdb, $loginradius_api_settings, $user_ID;

            $loginRadiusSecret = isset( $loginradius_api_settings['LoginRadius_secret'] ) ? $loginradius_api_settings['LoginRadius_secret'] : '';
            $loginRadiusMappingData = array();
            if( null == $loginRadiusObject){
                $loginRadiusObject = new Login_Radius_SDK();
            }
            if ( isset( $_REQUEST['token'] ) && is_user_logged_in() ) {

                $loginRadiusUserprofile = $loginRadiusObject->loginradius_get_user_profiledata( $_REQUEST['token'] );
                $loginRadiusMappingData['id'] = (!empty( $loginRadiusUserprofile->ID ) ? $loginRadiusUserprofile->ID : '' );
                $loginRadiusMappingData['provider'] = (!empty( $loginRadiusUserprofile->Provider ) ? $loginRadiusUserprofile->Provider : '' );
                $loginRadiusMappingData['thumbnail'] = (!empty( $loginRadiusUserprofile->ThumbnailImageUrl ) ? trim( $loginRadiusUserprofile->ThumbnailImageUrl ) : '' );
                if ( empty( $loginRadiusMappingData['thumbnail'] ) && $loginRadiusMappingData['provider'] == 'facebook' ) {
                    $loginRadiusMappingData['thumbnail'] = 'http://graph.facebook.com/' . $loginRadiusMappingData['id'] . '/picture?type=large';
                }
                $loginRadiusMappingData['pictureUrl'] = (!empty( $loginRadiusUserprofile->ImageUrl ) ? trim( $loginRadiusUserprofile->ImageUrl ) : '' );
                $wp_user_id = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key="loginradius_provider_id" AND meta_value = %s', $loginRadiusMappingData['id'] ) );
                if ( !empty( $wp_user_id ) ) {
                    // Check if verified field exist or not.
                    $loginRadiusVfyExist = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE user_id = %d AND meta_key = "loginradius_isVerified"', $wp_user_id ) );
                    if ( !empty( $loginRadiusVfyExist ) ) { // if verified field exists
                        $loginRadiusVerify = $wpdb->get_var( $wpdb->prepare( 'SELECT meta_value FROM ' . $wpdb->usermeta . ' WHERE user_id = %d AND meta_key = "loginradius_isVerified"', $wp_user_id ) );
                        if ( $loginRadiusVerify != '1' ) {
                            self:: link_account( $user_ID, $loginRadiusMappingData['id'], $loginRadiusMappingData['provider'], $loginRadiusMappingData['thumbnail'], $loginRadiusMappingData['pictureUrl'] );
                            return true;
                        } else {
                            //account already mapped
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    $loginRadiusMappingProvider = $loginRadiusMappingData['provider'];
                    $wp_user_lrid = $wpdb->get_var( $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->usermeta . ' WHERE meta_key="' . $loginRadiusMappingProvider . 'Lrid" AND meta_value = %s', $loginRadiusMappingData['id'] ) );
                    if ( !empty( $wp_user_lrid ) ) {
                        $lrVerified = get_user_meta( $wp_user_lrid, $loginRadiusMappingProvider . 'LrVerified', true );
                        if ( $lrVerified == '1' ) {  // Check if lrid is the same that verified email.
                            // account already mapped
                            return false;
                        } else {
                            // map account
                            self:: link_account( $user_ID, $loginRadiusMappingData['id'], $loginRadiusMappingData['provider'], $loginRadiusMappingData['thumbnail'], $loginRadiusMappingData['pictureUrl'] );
                            return true;
                        }
                    } else {
                        // map account
                        self:: link_account( $user_ID, $loginRadiusMappingData['id'], $loginRadiusMappingData['provider'], $loginRadiusMappingData['thumbnail'], $loginRadiusMappingData['pictureUrl'] );
                        return true;
                    }
                }
            }
        }

        /**
         * Get current protocol ( http OR https )
         */
        public static function get_protocol() {
            if ( isset( $_SERVER['HTTPS'] ) && !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
                return 'https://';
            } else {
                return 'http://';
            }
        }

        /**
         * Update usermeta to store linked account information
         */
        public static function link_account( $id, $lrid, $provider, $thumb, $pictureUrl ) {

            add_user_meta( $id, 'loginradius_provider_id', $lrid );
            add_user_meta( $id, 'loginradius_mapped_provider', $provider );
            add_user_meta( $id, 'loginradius_'.$provider.'_id', $lrid );
            if ( $thumb != '' ) {
                add_user_meta( $id, 'loginradius_'.$lrid.'_thumbnail', $thumb );
            }
            if ( $pictureUrl != '' ) {
                add_user_meta( $id, 'loginradius_'.$lrid.'_picture', $pictureUrl );
            }
        }


        /**
         * Check if scripts are to be loaded in footer according to plugin option
         */
        public static function scripts_in_footer_enabled() {
            global $loginradius_api_settings;

            if ( isset( $loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == 1 ) {
                return true;
            }
            return false;
        }

        /**
         * perform linking operation and return parameters if account mapped or not accordingly
         */
        public static function perform_linking_operation() {

            // public function call
            if ( Login_Radius_Common:: link_account_if_possible() === true ) {
                $linked = 1;
            } else {
                $linked = 0;
            }

            $redirectionUrl = Login_Radius_Common:: get_protocol() . htmlspecialchars( $_SERVER['HTTP_HOST'] ) . remove_query_arg( 'lrlinked' );
            if ( strpos( $redirectionUrl, '?' ) !== false ) {
                $redirectionUrl .= '&lrlinked=' . $linked;
            } else {
                $redirectionUrl .= '?lrlinked=' . $linked;
            }
            wp_redirect( $redirectionUrl );
            exit();
        }

        /**
         * Loading Login Script for loggedin user to provide account linking
         */
        public static function load_login_script( $isLinkingWidget = false ) {
            global $loginRadiusSettings, $loginradius_api_settings;
            $loginradius_api_settings['LoginRadius_apikey'] = isset( $loginradius_api_settings['LoginRadius_apikey'] ) ? trim( $loginradius_api_settings['LoginRadius_apikey'] ) : '';
            if( ! class_exists( "Login_Helper" ) ) {
                 require_once LOGINRADIUS_PLUGIN_DIR . 'public/inc/login/class-login-helper.php';
            }
            $location = Login_Helper::get_callback_url_for_redirection( Login_Radius_Common::get_protocol() );
            if ( $isLinkingWidget ) {
                $locationWithoutQueryString = urldecode( $location );
                if ( strpos( $location, '?' ) !== false ) {
                    $locationWithoutQueryString .= '&loginradius_linking=1';
                } else {
                    $location .= '?loginradius_linking=1';
                }
                $location = urlencode( $location );
            }
            ?>
            <!-- Script to enable social login -->
            <script src="//hub.loginradius.com/include/js/LoginRadius.js"></script>
            <script src = '<?php echo LOGINRADIUS_PLUGIN_URL . "assets/js/LoginRadiusSDK.2.0.0.js";?>' ></script>
            <script type="text/javascript">
            function detectmob() {
                if (navigator.userAgent.match(/Android/i) || navigator.userAgent
                .match(/webOS/i) || navigator.userAgent.match(/iPhone/i) ||
                navigator.userAgent.match(/iPad/i) || navigator.userAgent
                .match(/iPod/i) || navigator.userAgent.match(
                /BlackBerry/i) || navigator.userAgent.match(
                /Windows Phone/i)) {
                return true;
                } else {
                return false;
                }
            }
            var loginRadiusOptions = {};
            loginRadiusOptions.login = true;
            LoginRadius_SocialLogin.util.ready(function() {
                $ui = LoginRadius_SocialLogin.lr_login_settings;
                $ui.interfacesize = '';
                $ui.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey'] ?>";
                $ui.callback = "<?php echo $location ?>";
                $ui.lrinterfacecontainer = "interfacecontainerdiv";
                if (detectmob()) {
                    $ui.isParentWindowLogin = true;
                } else {
                    $ui.is_access_token = true;
                }

                <?php
                if ( isset( $loginRadiusSettings["LoginRadius_interfaceSize"] ) && $loginRadiusSettings["LoginRadius_interfaceSize"] == "small" ) {
                    echo '$ui.interfacesize ="small";';
                }
                if ( isset( $loginRadiusSettings['LoginRadius_numColumns'] ) && trim( $loginRadiusSettings['LoginRadius_numColumns'] ) != '' ) {
                    echo '$ui.noofcolumns = ' . trim( $loginRadiusSettings['LoginRadius_numColumns'] ) . ';';
                }
                if ( isset( $loginRadiusSettings['LoginRadius_backgroundColor'] ) ) {
                    echo '$ui.lrinterfacebackground = "' . trim( $loginRadiusSettings['LoginRadius_backgroundColor'] ) .'";';
                }
                ?>

                LoginRadius_SocialLogin.init(loginRadiusOptions);
            });
            LoginRadiusSDK.setLoginCallback(function() {
            var form = document.createElement('form');
            form.action = "<?php echo urldecode( $location ); ?>";
            form.method = 'POST';
            var hiddenToken = document.createElement('input');
            hiddenToken.type = 'hidden';
            hiddenToken.value = LoginRadiusSDK.getToken();
            hiddenToken.name = "token";
            form.appendChild(hiddenToken);
            document.body.appendChild(form);
            form.submit();
            });

            </script>
            <?php
            // }
        }

        /**
         * Check linking parameters and display message if account linked successfully or not
         */
        public static function check_linking_status_parameters( ) {
            $html = '';
            if ( isset( $_GET['lrlinked'] ) ) {
                if ( $_GET['lrlinked'] == 1 ) {
                    $html .= '<div id="loginRadiusSuccess" style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin:5px; color: #000">';
                    $html .= __( 'Account mapped successfully', 'LoginRadius' );
                    $html .= '</div>';
                } else {
                    $html .= '<div id="loginRadiusError" style="background-color: #FFEBE8; border:1px solid #CC0000; padding:5px; margin:5px; color: #000;">';
                    $html .= __( 'This account is already mapped', 'LoginRadius' );
                    $html .= '</div>';
                }
                return $html;
            }
        }

        /**
         * Display connectd/linked providers on user wp profile page
         */
        public static function get_connected_providers_list() {
            global $user_ID;
            $html = '';
            $loginRadiusMappings = get_user_meta( $user_ID, 'loginradius_mapped_provider', false );
            $loginRadiusMappings = array_unique( $loginRadiusMappings );
            $connected = false;
            $loginRadiusLoggedIn = get_user_meta( $user_ID, 'loginradius_current_id', true );
            $totalAccounts = get_user_meta( $user_ID, 'loginradius_provider_id' );
            $location = Login_Radius_Common:: get_protocol(). $_SERVER['HTTP_HOST'] . remove_query_arg( array( 'lrlinked', 'loginradius_linking', 'loginradius_post', 'loginradius_invite', 'loginRadiusMappingProvider', 'loginRadiusMap', 'loginRadiusMain' )  );

            if ( count( $loginRadiusMappings ) > 0 ) {
                foreach ( $loginRadiusMappings as $map ) {
                    $loginRadiusMappingId = get_user_meta( $user_ID, 'loginradius_'.$map.'_id' );

                    if ( count( $loginRadiusMappingId ) > 0 ) {
                        foreach ( $loginRadiusMappingId as $tempId ) {
                            $html .= '<tr>';

                            if ( $loginRadiusLoggedIn == $tempId ) {
                                $append    = '<span style=\'color:green\'>Currently </span>';
                                $connected = true;
                            }else {
                                $append = '';
                            }

                            $html .=  '<td>' . $append;
                            $html .=  __( 'Connected with', 'LoginRadius' );
                            $html .= '<strong> ' . ucfirst( $map ) . '</strong> <img src=\'' . LOGINRADIUS_PLUGIN_URL . 'assets/images/linking/' . $map . '.png' . '\' align=\'absmiddle\' style=\'margin-left:5px\' /></td><td>';
                            if ( count( $totalAccounts ) != 1 ) {
                                $html .= '<a href=' . $location . ( strpos( $location,'?' ) !== false ? '&' : '?' ) . 'loginRadiusMap=' . $tempId . '&loginRadiusMappingProvider=' . $map . ' ><input type=\'button\' class=\'button-primary\' value="' . __( 'Remove', 'LoginRadius' ) . '" /></a>';
                            }
                            $html .= '</td>';
                            $html .= '</tr>';
                        }
                    }
                }
            }
            $map = get_user_meta( $user_ID, 'loginradius_provider', true );
            if ( $map != false ) {
                $html .= '<tr>';
                $tempId = $loginRadiusLoggedIn;
                $append = ! $connected ? '<span style=\'color:green\'>Currently </span>' : '';
                $html .=  '<td>' . $append;
                $html .=  __( 'Connected with', 'LoginRadius' );
                $html .=  '<strong> ' . ucfirst( $map ) . '</strong> <img src=\'' . LOGINRADIUS_PLUGIN_URL . 'assets/images/linking/' . $map. '.png' . '\' align=\'absmiddle\' style=\'margin-left:5px\' /></td><td>';
                if ( count( $totalAccounts ) != 1 ) {
                    $html .= '<a href=' . $location . ( strpos( $location,'?' ) !== false ? '&' : '?' ) . 'loginRadiusMain=1&loginRadiusMap=' . $tempId . '&loginRadiusMappingProvider=' . $map . ' ><input type="button" class="button-primary" value="' . __( 'Remove', 'LoginRadius' ) . '" /></a>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
            return $html;
        }

        /**
         * Display provider , user is currently connected with
         */
        public static function display_currently_connected_provider() {
            global $user_ID;
            $loginRadiusLoggedIn = get_user_meta( $user_ID, 'loginradius_current_id', true );
            $totalAccounts = get_user_meta( $user_ID, 'loginradius_provider_id' );
            $location = Login_Radius_Common:: get_protocol() . $_SERVER['HTTP_HOST'] . remove_query_arg( array('lrlinked', 'loginradius_linking', 'loginradius_post', 'loginradius_invite', 'loginRadiusMappingProvider', 'loginRadiusMap', 'loginRadiusMain') );
            $html = '';
            $map = get_user_meta( $user_ID, 'loginradius_provider', true );
            if ( $map != false ) {
                $html .= '<tr>';
                $tempId = $loginRadiusLoggedIn;
                $append = '<span style=\'color:green\'>Currently </span>';
                $html .= '<td>' . $append;
                $html .= __( 'Connected with', 'LoginRadius' );
                $html .= '<strong> ' . ucfirst( $map ) . '</strong> <img src=\'' . LOGINRADIUS_PLUGIN_URL . 'assets/images/linking/' . $map . '.png' . '\' align=\'absmiddle\' style=\'margin-left:5px\' /></td><td>';
                if ( count( $totalAccounts ) != 1 ) {
                    $html .= '<a href=' . $location . ( strpos( $location, '?' ) !== false ? '&' : '?' ) . 'loginRadiusMain=1&loginRadiusMap=' . $tempId . '&loginRadiusMappingProvider=' . $map . ' ><input type="button" class="button-primary" value="' . __( 'Remove', 'LoginRadius' ) . '" /></a>';
                }
                $html .= '</td>';
                $html .= '</tr>';
            }
            return $html;
        }

        /**
         * Function which sends email on user activation to admin and users
         */
        public static function login_radius_send_verification_email( $loginRadiusEmail, $loginRadiusKey, $loginRadiusProvider = '', $emailType = '', $username = '' ) {

            $loginRadiusSubject = '';
            $loginRadiusMessage = '';
            switch ( $emailType ) {
                case "activation":
                    $loginRadiusSubject = '[' . htmlspecialchars( trim( get_option( 'blogname' ) ) ) . '] AccountActivation';
                    $loginRadiusMessage = 'Hi ' . $username . ", \r\n" .
                            'Your account has been activated at ' . site_url() . '. Now you can login to your account.';
                    break;
                case "admin notification":
                    $user = get_userdata( $username );
                    $loginRadiusSubject = '[' . htmlspecialchars( trim( get_option( 'blogname' ) ) ) . '] New User Registration';
                    $loginRadiusMessage = 'New user registration on your site ' . htmlspecialchars( trim( get_option( 'blogname' ) ) ) . ": \r\n" .
                            'Username: ' . $user->user_login . " \r\n" .
                            'E-mail: ' . $user->user_email . '';
                    break;
                default :
                    $loginRadiusSubject = '[' . htmlspecialchars( trim( get_option( 'blogname' ) ) ) . '] Email Verification';
                    $loginRadiusUrl = site_url() . '?loginRadiusVk=' . $loginRadiusKey;
                    if ( !empty( $loginRadiusProvider ) ) {
                        $loginRadiusUrl .= '&loginRadiusProvider=' . $loginRadiusProvider;
                    }
                    $loginRadiusMessage = "Please click on the following link or paste it in browser to verify your email \r\n" . $loginRadiusUrl;
                    break;
            }
            $headers = "MIME-Version: 1.0\n" .
                    "Content-Type: text/plain; charset='" .
                    get_option( 'blog_charset' ) . "\"\n" .
                    'From: <no-reply@loginradius.com>';
            wp_mail( $loginRadiusEmail, $loginRadiusSubject, $loginRadiusMessage, $headers );
        }
    }

}