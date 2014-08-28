<?php

/**
 * Function for rendering Advance Settings tab on plugin on settings page
 */
function login_radius_render_advance_settings_options( $loginRadiusSettings ) {
    ?>
    <div class="menu_containt_div" id="tabs-5">

        <!-- Social Login Interface Customization -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Login Interface Customization', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'What text do you want to display above the Social Login interface?', 'LoginRadius' ); ?></div>
                            <input type="text" name="LoginRadius_settings[LoginRadius_title]" size="60" value= "<?php echo htmlspecialchars( $loginRadiusSettings['LoginRadius_title'] ); ?>" />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Select the icon size to use in the Social Login interface', 'LoginRadius' ); ?></div>
                            <div class="loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[LoginRadius_interfaceSize]" value='large' <?php echo ( !isset( $loginRadiusSettings['LoginRadius_interfaceSize'] ) || $loginRadiusSettings['LoginRadius_interfaceSize'] == 'large' ) ? 'checked' : ''; ?>/> <label><?php _e( 'Large', 'LoginRadius' ); ?></label>
                            </div>
                            <div>
                                <input type="radio" name="LoginRadius_settings[LoginRadius_interfaceSize]" value="small" <?php echo ( isset( $loginRadiusSettings['LoginRadius_interfaceSize'] ) && $loginRadiusSettings['LoginRadius_interfaceSize'] == 'small' ) ? 'checked' : ''; ?>/> <label><?php _e( 'Small', 'LoginRadius' ); ?></label>
                            </div>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'How many social icons would you like to be displayed per row?', 'LoginRadius' ); ?></div>
                            <input type="text" name="LoginRadius_settings[LoginRadius_numColumns]" style="width:50px" maxlength="2" value="<?php
                            if ( isset( $loginRadiusSettings['LoginRadius_numColumns'] ) ) {
                                echo sanitize_text_field( trim( $loginRadiusSettings['LoginRadius_numColumns'] ) );
                            }
                            ?>" />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'What background color would you like to use for the Social Login interface?', 'LoginRadius' ); ?></div>
                            <?php
                            if ( isset( $loginRadiusSettings['LoginRadius_backgroundColor'] ) ) {
                                $colorValue = esc_html( trim( $loginRadiusSettings['LoginRadius_backgroundColor'] ) );
                            } else {
                                $colorValue = '';
                            }
                            ?>
                            <input type="text" name="LoginRadius_settings[LoginRadius_backgroundColor]" value="<?php echo $colorValue; ?>" />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Social Login Interface Display Settings -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Login Interface Display Settings', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to show the Social Login interface on your WordPress login page?', 'LoginRadius' ); ?></div>
                            <div class="loginRadiusYesRadio">
                                <input type="radio" class="login_radius_show_on_login" name="LoginRadius_settings[LoginRadius_loginform]" value='1' <?php echo isset( $loginRadiusSettings['LoginRadius_loginform'] ) && $loginRadiusSettings['LoginRadius_loginform'] == 1 ? 'checked' : ''; ?> /> <label><?php _e( 'Yes', 'LoginRadius' ); ?></label>
                            </div>
                            <div>
                                <input type="radio" class="login_radius_show_on_login" name="LoginRadius_settings[LoginRadius_loginform]" value="0" <?php echo isset( $loginRadiusSettings['LoginRadius_loginform'] ) && $loginRadiusSettings['LoginRadius_loginform'] == 0 ? 'checked' : ''; ?>/> <label><?php _e( 'No', 'LoginRadius' ); ?></label>
                            </div>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to show Social Login interface on your WordPress registration page?', 'LoginRadius' ); ?></div>
                            <div class="loginRadiusYesRadio">
                                <input type="radio" id="showonregistrationpageyes" name="LoginRadius_settings[LoginRadius_regform]" value='1' <?php echo isset( $loginRadiusSettings['LoginRadius_regform'] ) && $loginRadiusSettings['LoginRadius_regform'] == 1 ? 'checked' : ''; ?>/><?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <input type="radio" id="showonregistrationpageno" name="LoginRadius_settings[LoginRadius_regform]" value="0" <?php echo isset( $loginRadiusSettings['LoginRadius_regform'] ) && $loginRadiusSettings['LoginRadius_regform'] == 0 ? 'checked' : ''; ?>/><?php _e( 'No', 'LoginRadius' ); ?>

                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>

                    <tr id = "registration_interface">
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'If Yes, how do you want the Social Login interface to be shown on your wordpress registration page?', 'LoginRadius' ); ?></div>
                            <input type="radio" name="LoginRadius_settings[LoginRadius_regformPosition]" value="embed" <?php echo $loginRadiusSettings['LoginRadius_regformPosition'] == 'embed' ? 'checked = "checked"' : ''; ?>/> <?php _e( 'Show it below the registration form', 'LoginRadius' ); ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_regformPosition]" value="beside" <?php echo $loginRadiusSettings['LoginRadius_regformPosition'] == 'beside' ? 'checked = "checked"' : ''; ?>/> <?php _e( 'Show it beside the registration form', 'LoginRadius' ); ?>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr >
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want the plugin Javascript code to be included in the footer for faster loading of website content?', 'LoginRadius' ); ?><a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'It may break the functionality of the plugin if wp_footer and login_footer hooks do not exist in your theme', 'LoginRadius' ) ?>"> (?) </a> </div>
                            <div class="loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[scripts_in_footer]" value='1' checked <?php echo isset( $loginRadiusSettings['scripts_in_footer'] ) && $loginRadiusSettings['scripts_in_footer'] == 1 ? 'checked' : ''; ?>/><?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <input type="radio" name="LoginRadius_settings[scripts_in_footer]" value="0" <?php echo!isset( $loginRadiusSettings['scripts_in_footer'] ) || $loginRadiusSettings['scripts_in_footer'] == 0 ? 'checked' : ''; ?>/><?php _e( 'No', 'LoginRadius' ); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Social Login Email Settings -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Login Email Settings', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to send emails to users with their username and password after user registration?', 'LoginRadius' ); ?></div>
                            <div class = "loginRadiusYesRadio">
                                <input name="LoginRadius_settings[LoginRadius_sendemail]" type="radio"  value="sendemail" <?php echo Admin_Helper:: is_radio_checked( 'send_email', 'sendemail' ); ?> /><?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <div>
                                <input name="LoginRadius_settings[LoginRadius_sendemail]" type="radio" value="notsendemail" <?php echo Admin_Helper:: is_radio_checked( 'send_email', 'notsendemail' ); ?> /><?php _e( 'No', 'LoginRadius' ); ?>
                                <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'A few Social Networks do not supply user email address as part of user profile data. Do you want users to provide their email before completing the registration process?', 'LoginRadius' ); ?></div>
                            <input id="dummyMailYes" name="LoginRadius_settings[LoginRadius_dummyemail]" type="radio" value="notdummyemail" <?php echo!isset( $loginRadiusSettings['LoginRadius_dummyemail'] ) ? Admin_Helper::is_radio_checked( 'dummy_email', 'notdummyemail' ) : 'checked="checked"'; ?> /><?php _e( 'Yes, get real email IDs from the users (Ask users to enter their email IDs in a pop-up) ', 'LoginRadius' ); ?> <br />
                            <input id="dummyMailNo" name="LoginRadius_settings[LoginRadius_dummyemail]" type="radio" value="dummyemail" <?php echo isset( $loginRadiusSettings['LoginRadius_dummyemail'] ) ? Admin_Helper::is_radio_checked( 'dummy_email', 'dummyemail' ) : ''; ?>/><?php _e( 'No, just auto-generate random email IDs for users', 'LoginRadius' ); ?>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr id="loginRadiusPopupMessage">
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Please enter the title of the pop-up asking users to enter their email address ', 'LoginRadius' ); ?><a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'You may use @provider, it will be replaced by the Provider name.', 'LoginRadius' ) ?>"> (?) </a> </div>
                            <?php
                            if ( isset( $loginRadiusSettings['msg_email'] ) && $loginRadiusSettings['msg_email'] ) {
                                $emailMessageValue = htmlspecialchars( trim( $loginRadiusSettings['msg_email'] ) );
                            } else {
                                $emailMessageValue = 'Unfortunately we could not retrieve email from your @provider account Please enter your email in the form below in order to continue.';
                            }
                            ?>
                            <textarea name="LoginRadius_settings[msg_email]" cols="100" rows="3" ><?php echo $emailMessageValue; ?></textarea>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr id="loginRadiusPopupErrorMessage">
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Please enter the message to be shown to the user in case of an already registered email', 'LoginRadius' ); ?></div>
                            <?php
                            if ( isset( $loginRadiusSettings['msg_existemail'] ) && $loginRadiusSettings['msg_existemail'] ) {
                                $emailExistsMessageValue = htmlspecialchars( trim( $loginRadiusSettings['msg_existemail'] ) );
                            } else {
                                $emailExistsMessageValue = 'This email is already registered. Please choose another one or link this account via account linking on your profile page';
                            }
                            ?>
                            <textarea name="LoginRadius_settings[msg_existemail]" cols="100" rows="3"><?php echo $emailExistsMessageValue; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Social Login User Settings -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Login User Settings', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'How would you like Username to be created? Select your desired composition rule for Username.', 'LoginRadius' ); ?><a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'During account creation, it automatically adds a separator between first name and last name of the user', 'LoginRadius' ); ?>"> (?) </a></div>
                            <input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo!isset( $loginRadiusSettings['username_separator'] ) ? 'checked="checked"' : Admin_Helper:: is_radio_checked( 'seperator', 'dash' ); ?> value="dash" /> <?php _e( 'Firstname-Lastname [Ex: John-Doe]', 'LoginRadius' ); ?> <br />
                            <input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'seperator', 'dot' ); ?> value="dot"/><?php _e( 'Firstname.Lastname [Ex: John.Doe]', 'LoginRadius' ); ?><br />
                            <input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'seperator', 'space' ); ?> value='space'/><?php _e( 'Firstname Lastname [Ex: John Doe]', 'LoginRadius' ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to control user activation/deactivation?', 'LoginRadius' ); ?><a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'You can enable/disable user from Status column on Users page in admin', 'LoginRadius' ); ?>"> (?) </a></div>
                            <input type="radio" id="controlActivationYes" name="LoginRadius_settings[LoginRadius_enableUserActivation]" value='1' <?php echo ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == 1 ) ? 'checked' : ''; ?> /> <?php _e( 'Yes, display activate/deactivate option in the ', 'LoginRadius' ) ?> <a href="<?php echo get_admin_url() ?>users.php" target="_blank" ><?php _e( 'User list', 'LoginRadius' ); ?></a><br />
                            <input type="radio" id="controlActivationNo" name="LoginRadius_settings[LoginRadius_enableUserActivation]" value="0" <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == 0 ) ) || !isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) ? 'checked' : ''; ?> /> <?php _e( 'No', 'LoginRadius' ); ?><br />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr id="loginRadiusDefaultStatus">
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'What would you like to set as the default status of the user when he/she registers to your website?', 'LoginRadius' ); ?></div>
                            <input type="radio" name="LoginRadius_settings[LoginRadius_defaultUserStatus]" value='1' <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == 1 ) ) || !isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) ? 'checked' : ''; ?>/> <?php _e( 'Active', 'LoginRadius' ); ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_defaultUserStatus]" value="0" <?php echo ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == 0 ) ? 'checked' : ''; ?>/> <?php _e( 'Inactive', 'LoginRadius' ); ?>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to display the social network that the user connected with, in the user list', 'LoginRadius' ); ?></div>
                            <input type="radio" name="LoginRadius_settings[LoginRadius_noProvider]" value="1" <?php echo ( $loginRadiusSettings['LoginRadius_noProvider'] == 1 ) ? 'checked' : ''; ?>/> <?php _e( 'Yes, display the social network that the user connected with, in the user list', 'LoginRadius' ); ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_noProvider]" value='0' <?php echo ( $loginRadiusSettings['LoginRadius_noProvider'] == 0 ) ? 'checked' : ''; ?>/> <?php _e( 'No, do not display the social network that the user connected with, in the user list', 'LoginRadius' ); ?>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to update User Profile Data in your Wordpress database, every time user logs into your website?', 'LoginRadius' ); ?><a style="text-decoration:none" href="javascript:void ( 0 ) " title="<?php _e( 'If you disable this option, user profile data will be saved only once when user logs in first time at your website, user profile details will not be updated in your Wordpress database, even if user changes his/her social account details.', 'LoginRadius' ); ?>"> (?) </a></div>
                            <div class = "loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[profileDataUpdate]" value='1' <?php echo (!isset( $loginRadiusSettings['profileDataUpdate'] ) || $loginRadiusSettings['profileDataUpdate'] == 1 ) ? 'checked' : ''; ?> /> <?php _e( 'Yes', 'LoginRadius' ) ?> <br />
                            </div>
                            <div>
                                <input type="radio" name="LoginRadius_settings[profileDataUpdate]" value="0" <?php echo ( isset( $loginRadiusSettings['profileDataUpdate'] ) && $loginRadiusSettings['profileDataUpdate'] == 0 ) ? 'checked' : ''; ?>  /> <?php _e( 'No', 'LoginRadius' ); ?><br />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to let users use their social profile picture as an avatar on your website?', 'LoginRadius' ); ?></div>
                            <div class = "loginRadiusYesRadio">
                                <input name ="LoginRadius_settings[LoginRadius_socialavatar]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'avatar', 'socialavatar' ); ?> value="socialavatar"/><?php _e( 'Yes', 'LoginRadius' ); ?> <br />
                            </div>
                            <div>
                                <input name ="LoginRadius_settings[LoginRadius_socialavatar]" type="radio" <?php echo Admin_Helper:: is_radio_checked( 'avatar', 'defaultavatar' ); ?> value="defaultavatar" /><?php _e( 'No', 'LoginRadius' ); ?>
                            </div>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( "Do you want to automatically link your existing users' accounts to their social accounts if their WP account email address matches the email address associated with their social account?", 'LoginRadius' ); ?></div>
                            <div class = "loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[LoginRadius_socialLinking]" value='1' <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_socialLinking'] ) && $loginRadiusSettings['LoginRadius_socialLinking'] == 1 ) || !isset( $loginRadiusSettings['LoginRadius_socialLinking'] ) ) ? 'checked' : ''; ?>/> <?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <div>
                                <input type="radio" name="LoginRadius_settings[LoginRadius_socialLinking]" value="0" <?php checked( '0', @$loginRadiusSettings['LoginRadius_socialLinking'] ); ?>/> <?php _e( 'No', 'LoginRadius' ); ?>
                            </div>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <?php
                    if ( is_multisite() && is_main_site() ) {
                        ?>
                        <tr>
                            <td>
                                <div class="loginRadiusQuestion"><?php _e( 'Do you want to apply the same changes when you update plugin settings in the main blog of multisite network?', 'LoginRadius' ); ?></div>
                                <input type="radio" name="LoginRadius_settings[multisite_config]" value='1' <?php echo ( ( !isset( $loginRadiusSettings['multisite_config'] ) ) || ( isset( $loginRadiusSettings['multisite_config'] ) && $loginRadiusSettings['multisite_config'] == 1 ) ) ? 'checked' : '' ; ?>/> <?php _e( 'Yes, apply the same changes to plugin settings of each blog in the multisite network when I update plugin settings.', 'LoginRadius' ); ?> <br />
                                <input type="radio" name="LoginRadius_settings[multisite_config]" value="0" <?php echo ( isset( $loginRadiusSettings['multisite_config'] ) && $loginRadiusSettings['multisite_config'] == 0 ) ? 'checked' : ''; ?>/> <?php _e( 'No, do not apply the changes to other blogs when I update plugin settings.', 'LoginRadius' ); ?>
                                <div class="loginRadiusBorder"></div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <!-- Plugin Debug option. -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Debug', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to enable debugging mode?', 'LoginRadius' ); ?></div>
                            <div class = "loginRadiusYesRadio">
                                <input name="LoginRadius_settings[enable_degugging]" type="radio"  value="1" <?php echo ( $loginRadiusSettings['enable_degugging'] == 1) ? 'checked = "checked"' : ''; ?> /><?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <div>
                                <input name="LoginRadius_settings[enable_degugging]" type="radio" value="0" <?php echo ( !isset( $loginRadiusSettings['enable_degugging'] ) || $loginRadiusSettings['enable_degugging'] == 0) ? 'checked="checked"' : ''; ?>/><?php _e( 'No', 'LoginRadius' ); ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- Plugin deletion options -->
        <div class="stuffbox">
            <h3><label><?php _e( 'Plug-in deletion options', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to completely remove the plugin settings and options on plugin deletion ( If you choose Yes, then you will not be able to recover settings again ) ?', 'LoginRadius' ); ?></div>
                            <div class = "loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[delete_options]" value='1' <?php echo (!isset( $loginRadiusSettings['delete_options'] ) || $loginRadiusSettings['delete_options'] == 1 ) ? 'checked' : ''; ?> /> <?php _e( 'Yes', 'LoginRadius' ) ?> <br />
                            </div>
                            <div>
                                <input type="radio" name="LoginRadius_settings[delete_options]" value="0" <?php echo ( isset( $loginRadiusSettings['delete_options'] ) && $loginRadiusSettings['delete_options'] == 0 ) ? 'checked' : ''; ?>  /> <?php _e( 'No', 'LoginRadius' ); ?><br />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}
