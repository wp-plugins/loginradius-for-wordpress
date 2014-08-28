<?php
if ( !class_exists( 'Admin_Helper' ) ) {

    class Admin_Helper {

        /*
         * Display notice on plugin page, if LR API Key and Secret are empty
         */
        public static function display_notice_to_insert_api_and_secret() {
            ?>
            <div id="loginRadiusKeySecretNotification" style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin-bottom:5px; width: 1050px;">
                <?php _e( 'To activate the <strong>Social Login</strong>, insert LoginRadius API Key and Secret in the <strong>API Settings</strong> section below. <strong>Social Sharing does not require API Key and Secret</strong>.', 'LoginRadius' ); ?>
            </div>
            <?php
        }

        /**
         * Check if LoginRadius API Key and Secret are saved
         *
         * global $loginRadiusSettings
         */
        public static function loginradius_api_secret_saved() {
            global $loginRadiusSettings;
            if ( !isset( $loginRadiusSettings['LoginRadius_apikey'] ) || trim( $loginRadiusSettings['LoginRadius_apikey'] ) == '' || !isset( $loginRadiusSettings['LoginRadius_secret'] ) || trim( $loginRadiusSettings['LoginRadius_secret'] ) == '' ) {
                return false;
            }
            return true;
        }
        /**
         * Add provider column on users list page
         *
         * global $loginRadiusSettings
         */
        public static function add_provider_column_in_users_list( $columns ) {
            global $loginRadiusSettings;
            if ( isset( $loginRadiusSettings['LoginRadius_noProvider'] ) && $loginRadiusSettings['LoginRadius_noProvider'] == '1' ) {
                $columns['loginradius_provider'] = 'LoginRadius Provider';
            }
            if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
                // Add active/inactive Staus column on users list page
                $columns['loginradius_status'] = 'Status';
            }
            return $columns;
        }

        /**
         * show social ID provider in the provider column
         *
         * global $loginRadiusSettings
         */
        public static function login_radius_show_provider( $value, $columnName, $userId ) {
            global $loginRadiusSettings;
            if ( isset( $loginRadiusSettings['LoginRadius_noProvider'] ) && $loginRadiusSettings['LoginRadius_noProvider'] == '1' ) {
                $lrProviderMeta = get_user_meta( $userId, 'loginradius_provider', true );
                $lrProvider = ( $lrProviderMeta == false ) ? '-' : $lrProviderMeta;
                if ( 'loginradius_provider' == $columnName ) {
                    return ucfirst( $lrProvider );
                }
            }
            if ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == '1' ) {
                if ( $userId == 1 ) {
                    return;
                }
                if ( ( $lrStatus = get_user_meta( $userId, 'loginradius_status', true ) ) == '' || $lrStatus == '1' ) {
                    $lrStatus = '1';
                } else {
                    $lrStatus = '0';
                }
                if ( 'loginradius_status' == $columnName ) {
                    if ( $lrStatus == '1' ) {
                        return '<span id="loginRadiusStatus' . $userId . '"><a alt="Active ( Click to Disable ) " title="Active ( Click to Disable ) " href="javascript:void ( 0 ) " onclick="loginRadiusChangeStatus ( ' . $userId . ', ' . $lrStatus . ' ) " ><img height="20" width="20" src="' . LOGINRADIUS_PLUGIN_URL . 'assets/images/enable.png' . '" /></a></span>';
                    } else {
                        return '<span id="loginRadiusStatus' . $userId . '"><a alt="Inactive ( Click to Enable ) " title="Inactive ( Click to Enable ) " href="javascript:void ( 0 ) " onclick="loginRadiusChangeStatus ( ' . $userId . ', ' . $lrStatus . ' ) " ><img height="20" width="20" src="' . LOGINRADIUS_PLUGIN_URL . 'assets/images/disable.png' . '" /></a></span>';
                    }
                }
            }
        }

        /**
         * add javascript on users.php in admin for ajax call to activate/deactivate users
         *
         * global $parent_file;
         */
        public static function add_script_for_users_page() {
            global $parent_file;
            if ( $parent_file == 'users.php' ) {
                ?>
                <script type="text/javascript">
                    function loginRadiusChangeStatus(userId, currentStatus) {
                        jQuery('#loginRadiusStatus' + userId).html('<img width="20" height="20" title="<?php _e( 'Please wait', 'LoginRadius' ) ?>..." src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" />');
                        jQuery.ajax({
                            type: 'POST',
                            url: '<?php echo get_admin_url() ?>admin-ajax.php',
                            data: {
                                action: 'login_radius_change_user_status',
                                user_id: userId,
                                current_status: currentStatus
                            },
                            success: function(data) {
                                if (data == 'done') {
                                    if (currentStatus == 0) {
                                        jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 1 ) " ><img width="20" height="20" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/enable.png'; ?>" /></a></span>');
                                    } else if (currentStatus == 1) {
                                        jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Inactive ( Click to Enable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Inactive ( Click to Enable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 0 ) " ><img width="20" height="20" src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/disable.png'; ?>" /></a></span>');
                                    }
                                } else if (data == 'error') {
                                    jQuery('#loginRadiusStatus' + userId).html('<span id="loginRadiusStatus' + userId + '"><a href="javascript:void ( 0 ) " alt="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" title="<?php _e( 'Active ( Click to Disable ) ', 'LoginRadius' ) ?>" onclick="loginRadiusChangeStatus ( ' + userId + ', 1 ) " ><img width="20" height="20" src="<?php echo plugins_url( 'images/enable.png', __FILE__ ) ?>" /></a></span>');
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {

                            }
                        });
                    }
                </script>
                <?php
            }
        }

        /**
         * Function for adding script to display theme specific settings dynamically.
         */
        public static function render_admin_ui_script() {
            ?>
            <script type="text/javascript">var islrsharing = true;
                var islrsocialcounter = true;</script>
            <script type = "text/javascript"  src = '//share.loginradius.com/Content/js/LoginRadius.js'></script>
            <script type="text/javascript">

                function loginRadiusAdminUI2() {
                    var selectedHorizontalSharingProviders = <?php echo Admin_Helper:: get_sharing_providers_josn_arrays( 'horizontal' ); ?>;
                    var selectedVerticalSharingProviders = <?php echo Admin_Helper:: get_sharing_providers_josn_arrays( 'vertical' ); ?>;
                    var selectedHorizontalCounterProviders = <?php echo Admin_Helper:: get_counters_providers_json_array( 'horizontal' ); ?>;
                    var selectedVerticalCounterProviders = <?php echo Admin_Helper:: get_counters_providers_json_array( 'vertical' ); ?>;

                    var loginRadiusSharingHtml = '';
                    var checked = false;

                    // prepare HTML to be shown as Horizontal Sharing Providers
                    for (var i = 0; i < $SS.Providers.More.length; i++) {
                        checked = loginRadiusCheckElement(selectedHorizontalSharingProviders, $SS.Providers.More[i]);
                        loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusSharingLimit( this, \'horizontal\' ); loginRadiusRearrangeProviderList( this, \'Horizontal\' ) " ';
                        if (checked) {
                            loginRadiusSharingHtml += 'checked="' + checked + '" ';
                        }
                        loginRadiusSharingHtml += 'name="LoginRadius_settings[horizontal_sharing_providers][]" value="' + $SS.Providers.More[i] + '"> <label>' + $SS.Providers.More[i] + '</label></div>';
                    }

                    // show horizontal sharing providers list
                    jQuery('#login_radius_horizontal_sharing_providers_container').html(loginRadiusSharingHtml);

                    loginRadiusSharingHtml = '';
                    checked = false;
                    // prepare HTML to be shown as Vertical Sharing Providers
                    for (var i = 0; i < $SS.Providers.More.length; i++) {
                        checked = loginRadiusCheckElement(selectedVerticalSharingProviders, $SS.Providers.More[i]);
                        loginRadiusSharingHtml += '<div class="loginRadiusProviders"><input type="checkbox" onchange="loginRadiusSharingLimit( this, \'vertical\' ); loginRadiusRearrangeProviderList( this, \'Vertical\' ) " ';
                        if (checked) {
                            loginRadiusSharingHtml += 'checked="' + checked + '" ';
                        }
                        loginRadiusSharingHtml += 'name="LoginRadius_settings[vertical_sharing_providers][]" value="' + $SS.Providers.More[i] + '"> <label>' + $SS.Providers.More[i] + '</label></div>';
                    }
                    // show vertical sharing providers list
                    jQuery('#login_radius_vertical_sharing_providers_container').html(loginRadiusSharingHtml);
                    loginRadiusSharingHtml = '';
                    checked = false;
                    // prepare HTML to be shown as Horizontal Counter Providers
                    for (var i = 0; i < $SC.Providers.All.length; i++) {
                        checked = loginRadiusCheckElement(selectedHorizontalCounterProviders, $SC.Providers.All[i]);
                        loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
                        if (checked) {
                            loginRadiusSharingHtml += 'checked="' + checked + '" ';
                        }
                        loginRadiusSharingHtml += 'name="LoginRadius_settings[horizontal_counter_providers][]" value="' + $SC.Providers.All[i] + '"> <label>' + $SC.Providers.All[i] + '</label></div>';
                    }
                    // show horizontal counter providers list
                    jQuery('#login_radius_horizontal_counter_providers_container').html(loginRadiusSharingHtml);

                    loginRadiusSharingHtml = '';
                    checked = false;
                    // prepare HTML to be shown as Vertical Counter Providers
                    for (var i = 0; i < $SC.Providers.All.length; i++) {
                        checked = loginRadiusCheckElement(selectedVerticalCounterProviders, $SC.Providers.All[i]);
                        loginRadiusSharingHtml += '<div class="loginRadiusCounterProviders"><input type="checkbox" ';
                        if (checked) {
                            loginRadiusSharingHtml += 'checked="' + checked + '" ';
                        }
                        loginRadiusSharingHtml += 'name="LoginRadius_settings[vertical_counter_providers][]" value="' + $SC.Providers.All[i] + '"> <label>' + $SC.Providers.All[i] + '</label></div>';
                    }
                    // show vertical counter providers list
                    jQuery('#login_radius_vertical_counter_providers_container').html(loginRadiusSharingHtml);
                }
            </script>
            <?php
        }

        /**
         * function returns json array of sharing providers on the basis of theme( provided as argument).
         *
         * global $loginRadiusSettings
         */
        public static function get_sharing_providers_josn_arrays( $themeType ) {
            global $loginRadiusSettings;

            switch ( $themeType ) {
                case 'vertical':
                    if ( isset( $loginRadiusSettings['vertical_rearrange_providers'] ) && is_array( $loginRadiusSettings['vertical_rearrange_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                        return json_encode( $loginRadiusSettings['vertical_rearrange_providers'] );
                    } else {
                        return self:: get_default_sharing_providers_josn_array();
                    }
                    break;

                case 'horizontal':
                    if ( isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) && is_array( $loginRadiusSettings['horizontal_rearrange_providers'] ) && count( $loginRadiusSettings['horizontal_rearrange_providers'] ) > 0 ) {
                        return json_encode( $loginRadiusSettings['horizontal_rearrange_providers'] );
                    } else {
                        return self:: get_default_sharing_providers_josn_array();
                    }
                    break;
            }
        }

        /**
         * function returns json array of counter providers on the basis of theme( provided as argument).
         *
         * global $loginRadiusSettings;
         */
        public static function get_counters_providers_json_array( $themeType ) {
            global $loginRadiusSettings;

            switch ( $themeType ) {
                case 'horizontal':
                    if ( isset( $loginRadiusSettings['horizontal_counter_providers'] ) && is_array( $loginRadiusSettings['horizontal_counter_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                        return json_encode( $loginRadiusSettings['horizontal_counter_providers'] );
                    } else {
                        return self:: get_default_counters_providers_josn_array();
                    }
                    break;

                case 'vertical':
                    if ( isset( $loginRadiusSettings['vertical_counter_providers'] ) && is_array( $loginRadiusSettings['vertical_counter_providers'] ) && count( $loginRadiusSettings['vertical_rearrange_providers'] ) > 0 ) {
                        return json_encode( $loginRadiusSettings['vertical_counter_providers'] );
                    } else {
                        return self:: get_default_counters_providers_josn_array();
                    }
                    break;
            }
        }

        /**
         * function returns default json array of sharing providers.
         */
        public static function get_default_sharing_providers_josn_array() {
            return '["Facebook", "Twitter", "Pinterest", "Email", "Print"]';
        }

        /**
         * function returns default json array of counter providers.
         */
        public static function get_default_counters_providers_josn_array() {
            return '["Facebook Like", "Google+ +1", "Pinterest Pin it", "LinkedIn Share", "Hybridshare"]';
        }

        /**
         * Encoding LoginRadius Plugin settings
         */
        public static function get_encoded_settings_string( $loginRadiusSettings ) {

            $string = '~' . '2|';
            $string .= $loginRadiusSettings['LoginRadius_redirect'] . '|';
            if ( $loginRadiusSettings['LoginRadius_redirect'] == "custom" ) {
                $string .= $loginRadiusSettings['custom_redirect'] . '|';
            }
            $string .= $loginRadiusSettings['LoginRadius_regRedirect'] . '|';
            if ( $loginRadiusSettings['LoginRadius_regRedirect'] == "custom" ) {
                $string .= $loginRadiusSettings['custom_regRedirect'] . '|';
            }
            $string .= $loginRadiusSettings['LoginRadius_loutRedirect'] . '|';
            if ( $loginRadiusSettings['LoginRadius_loutRedirect'] == "custom" ) {
                $string .= $loginRadiusSettings['custom_loutRedirect'] . '|';
            }

            $string .= '~3|';
            $string .= $loginRadiusSettings['horizontal_shareEnable'] . '|';
            $string .= isset( $loginRadiusSettings['horizontalSharing_theme'] ) ? $loginRadiusSettings['horizontalSharing_theme'] : '32';
            //generating string for horizontal sharing providers, counter providers and rearrange providers
            $string .= self:: get_horizontal_networks_providers( $loginRadiusSettings );
            $string .= isset( $loginRadiusSettings['horizontal_shareTop'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['horizontal_shareBottom'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['horizontal_sharehome'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['horizontal_sharepost'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['horizontal_sharepage'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) ? '1|' : '0|';
            //Starting Vertical Sharing string encodeing
            $string .= $loginRadiusSettings['vertical_shareEnable'] . '|' . $loginRadiusSettings['verticalSharing_theme'];
            //generating string for vertical sharing providers, counter providers and reaarange providers
            $string .= self:: get_vertical_networks_providers( $loginRadiusSettings );
            $string .= '|' . $loginRadiusSettings['sharing_verticalPosition'] . '|';
            $string .= isset( $loginRadiusSettings['vertical_sharehome'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['vertical_sharepost'] ) ? '1|' : '0|';
            $string .= isset( $loginRadiusSettings['vertical_sharepage'] ) ? '1|' : '0|';

            $string .= '~4|';
            $string .= $loginRadiusSettings['LoginRadius_commentEnable'] . '|' . $loginRadiusSettings['LoginRadius_commentInterfacePosition'] . '|';

            $string .= '~5|';
            $string .= $loginRadiusSettings['LoginRadius_title'] . '|';
            $string .= isset( $loginRadiusSettings['LoginRadius_interfaceSize'] ) ?  $loginRadiusSettings['LoginRadius_numColumns'] . '|' : ' ' . '|';
            if ( isset( $loginRadiusSettings['LoginRadius_backgroundColor'] ) && !empty( $loginRadiusSettings['LoginRadius_backgroundColor'] ) ) {
                $string .= $loginRadiusSettings['LoginRadius_backgroundColor'] . '|';
            }
            $string .= $loginRadiusSettings['LoginRadius_loginform'] . '|' . $loginRadiusSettings['LoginRadius_regform'] . '|' . $loginRadiusSettings['LoginRadius_regformPosition'] . '|';
            $string .= $loginRadiusSettings['scripts_in_footer'] . '|' . $loginRadiusSettings['LoginRadius_sendemail'] . '|' ;
            $string .= $loginRadiusSettings['msg_email'] . '|' . $loginRadiusSettings['msg_existemail'] . '|';
            $string .= $loginRadiusSettings['username_separator'] . '|';
            $string .= $loginRadiusSettings['LoginRadius_enableUserActivation'] . '|' . $loginRadiusSettings['LoginRadius_defaultUserStatus'] . '|';
            $string .= $loginRadiusSettings['LoginRadius_noProvider'] . '|';
            $string .= $loginRadiusSettings['profileDataUpdate'] . '|' . $loginRadiusSettings['LoginRadius_socialavatar'] . '|';
            $string .= $loginRadiusSettings['LoginRadius_socialLinking'] . '|';
            $string .= $loginRadiusSettings['enable_degugging'] . '|' . $loginRadiusSettings['delete_options'] . '|';

            return $string;

        }

        /**
         * Get comma seperated horizontal network providers
         */
        public static function get_horizontal_networks_providers( $loginRadiusSettings ) {
            $string = '';
            if ( isset( $loginRadiusSettings['horizontal_sharing_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['horizontal_sharing_providers'] );
            }
            if ( isset( $loginRadiusSettings['horizontal_counter_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['horizontal_counter_providers'] );
            }
            if ( isset( $loginRadiusSettings['horizontal_rearrange_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['horizontal_rearrange_providers'] );
            }
            return $string . '|';
        }
        /**
         * Get comma seperated vertical network providers
         */
        public static function get_vertical_networks_providers( $loginRadiusSettings ) {
            $string = '';

            if ( isset( $loginRadiusSettings['vertical_sharing_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['vertical_sharing_providers'] );
            }
            if ( isset( $loginRadiusSettings['vertical_counter_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['vertical_counter_providers'] );
            }
            if ( isset( $loginRadiusSettings['vertical_rearrange_providers'] ) ) {
                $string .= self:: imploading_arrays( $loginRadiusSettings['vertical_rearrange_providers'] );
            }
            return $string . '|';
        }

        /**
         * Changing array to comma seperated string
         */
        public static function imploading_arrays( $array ) {
            $string = '|["' . implode( '","', $array ) . '"]';
            return $string;
        }

        /**
         * Display error notice to admin.
         */
        public static function display_admin_settings_errors() {
            settings_errors( 'LoginRadius_settings' );
        }

        /**
         * This function return checked="checked" if LoginRadius setting $optionName is the value of  $tempArray[$settingName],
         * else return blank string
         *
         * @global $loginRadiusSettings
         */
        public static function is_radio_checked( $settingName, $optionName ) {
            global $loginRadiusSettings;

            $tempArray = array(
                'login' => 'LoginRadius_redirect',
                'register' => 'LoginRadius_regRedirect',
                'avatar' => 'LoginRadius_socialavatar',
                'seperator' => 'username_separator',
                'send_email' => 'LoginRadius_sendemail',
                'dummy_email' => 'LoginRadius_dummyemail',
                'logoutUrl' => 'LoginRadius_loutRedirect'
            );

            if ( $loginRadiusSettings[$tempArray[$settingName]] == $optionName ) {
                return 'checked="checked"';
            } else {
                return '';
            }
        }

    }

}