<?php

/**
 * Function for rendering Social Login tab on plugin on settings page
 */
function login_radius_render_social_login_options() {
    global $loginRadiusSettings, $loginRadiusLoginIsBpActive;
    ?>
    <div class="menu_containt_div" id="tabs-2">
        <div class="stuffbox">
            <h3><label><?php _e( 'Redirection Settings', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Redirection settings after login ', 'LoginRadius' ); ?></div>
                            <input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="samepage" <?php echo Admin_Helper:: is_radio_checked( 'login', 'samepage' ); ?>/> <?php _e( 'Redirect to the \'Same Page\' where the user logged in', 'LoginRadius' ); ?><br />
                            <input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'login', 'homepage' ); ?> /> <?php _e( 'Redirect to \'Home Page\' of your WP site', 'LoginRadius' ); ?><br />
                            <input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="dashboard" <?php echo Admin_Helper:: is_radio_checked( 'login', 'dashboard' ); ?>/> <?php _e( 'Redirect to \'Account Dashboard\'', 'LoginRadius' ); ?> <br />
                            <?php
                            if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
                                ?>
                                <input type="radio" name="LoginRadius_settings[LoginRadius_redirect]" value="bp" <?php echo Admin_Helper:: is_radio_checked( 'login', 'bp' ); ?>/> <?php _e( 'Redirect to Buddypress profile page', 'LoginRadius' ); ?><br />
                                <?php
                            }
                            ?>
                            <input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'login', 'custom' ); ?> /> <?php _e( 'Redirect to \'Custom URL\'', 'LoginRadius' ); ?><br />

                            <?php
                            if ( isset( $loginRadiusSettings['LoginRadius_redirect'] ) && $loginRadiusSettings['LoginRadius_redirect'] == 'custom' ) {
                                $inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_redirect'] );
                            } else {
                                $inputBoxValue = site_url();
                            }
                            ?>
                            <input type="text" id="loginRadiusCustomLoginUrl" name="LoginRadius_settings[custom_redirect]" size="60" value="<?php echo $inputBoxValue; ?>" />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Redirection settings after registration', 'LoginRadius' ); ?></div>
                            <input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="samepage" <?php echo Admin_Helper:: is_radio_checked( 'register', 'samepage' ); ?>/> <?php _e( 'Redirect to the \'Same page\' where the user registered', 'LoginRadius' ); ?><br />
                            <input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'register', 'homepage' ); ?> /> <?php _e( 'Redirect to \'Home Page\' of your WP site', 'LoginRadius' ); ?><br />
                            <input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="dashboard" <?php echo Admin_Helper:: is_radio_checked( 'register', 'dashboard' ); ?>/> <?php _e( 'Redirect to \'Account Dashboard\'', 'LoginRadius' ); ?><br />
                            <?php
                            if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
                                ?>
                                <input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="bp" <?php echo Admin_Helper:: is_radio_checked( 'register', 'bp' ); ?>/> <?php _e( 'Redirect to Buddypress profile page', 'LoginRadius' ); ?><br />
                                <?php
                            }
                            ?>
                            <input type="radio" class="registerRedirectionRadio" id="loginRadiusCustomRegRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'register', 'custom' ); ?> /><?php _e( 'Redirect to \'Custom URL\'', 'LoginRadius' ); ?><br />
                            <?php
                            if ( isset( $loginRadiusSettings['custom_regRedirect'] ) && $loginRadiusSettings['LoginRadius_regRedirect'] == 'custom' ) {
                                $inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_regRedirect'] );
                            } else {
                                $inputBoxValue = site_url();
                            }
                            ?>

                            <input type="text" id="loginRadiusCustomRegistrationUrl" name="LoginRadius_settings[custom_regRedirect]" size="60" value="<?php echo $inputBoxValue; ?>" />
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Redirection settings after logging out', 'LoginRadius' ) ?></div>
                            <strong><?php _e( "Note: Logout function works only when clicking 'logout' in the social login widget area. In all other cases, WordPress's default logout function will be applied.", 'LoginRadius' ); ?></strong><br />
                            <input type="radio" class="logoutRedirectionRadio" name="LoginRadius_settings[LoginRadius_loutRedirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'logoutUrl', 'homepage' ); ?>/> <?php _e( 'Redirect to \'Home Page\'', 'LoginRadius' ); ?><br />
                            <input type="radio" class="logoutRedirectionRadio" name="LoginRadius_settings[LoginRadius_loutRedirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'logoutUrl', 'custom' ); ?> /> <?php _e( 'Redirect to \'Custom URL\'', 'LoginRadius' ); ?><br />
                            <?php
                            if ( isset( $loginRadiusSettings['LoginRadius_loutRedirect'] ) && $loginRadiusSettings['LoginRadius_loutRedirect'] == 'custom' ) {
                                $inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_loutRedirect'] );
                            } else {
                                $inputBoxValue = site_url();
                            }
                            ?>
                            <input type="text" id="loginRadiusCustomLogoutUrl" name="LoginRadius_settings[custom_loutRedirect]" size="60" value="<?php echo $inputBoxValue; ?>" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}
