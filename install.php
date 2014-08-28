<?php

/**
 * class responsible for setting default settings for LoginRadius Social Login and Share plugin
 */
class Login_Radius_Install {

    /**
     * Function for adding default plugin settings at activation
     */
    public static function set_default_options() {
        if ( !get_option( 'loginradius_db_version' ) ) {
            // If plugin loginradius_db_version option not exist, it means plugin is not latest and update options.
            $options = array(
                'LoginRadius_loginform' => '1',
                'LoginRadius_regform' => '1',
                'LoginRadius_regformPosition' => 'embed',
                'LoginRadius_commentEnable' => '1',
                'horizontalSharing_theme' => '32',
                'horizontal_shareEnable' => '1',
                'horizontal_shareTop' => '1',
                'horizontal_shareBottom' => '1',
                'horizontal_sharehome' => '1',
                'horizontal_sharepost' => '1',
                'horizontal_sharepage' => '1',
                'horizontal_shareexcerpt' => '1',
                'vertical_shareEnable' => '1',
                'verticalSharing_theme' => 'counter_vertical',
                'vertical_sharehome' => '1',
                'vertical_sharepost' => '1',
                'vertical_sharepage' => '1',
                'sharing_verticalPosition' => 'top_left',
                'LoginRadius_noProvider' => '1',
                'LoginRadius_enableUserActivation' => '1',
                'scripts_in_footer' => '0',
                'delete_options' => '1',
                'username_separator' => 'dash',
                'LoginRadius_redirect' => 'samepage',
                'LoginRadius_regRedirect' => 'samepage',
                'LoginRadius_loutRedirect' => 'homepage',
                'LoginRadius_socialavatar' => 'socialavatar',
                'LoginRadius_title' => 'Login with Social ID',
                'enable_degugging' => '0',
                'LoginRadius_sendemail' => 'notsendemail',
                'LoginRadius_dummyemail' => 'notdummyemail'
            );
            delete_option( 'LoginRadius_settings' );
            add_option( 'LoginRadius_settings', $options );
            add_option( 'loginradius_db_version', '6.0' );
        }
    }

}
