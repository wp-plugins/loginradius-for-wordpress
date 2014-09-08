<?php
/**
 * @file
 * The Admin Panel and related tasks are handled in this file.
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * The main class and initialization point of the plugin settings page.
 */
if ( !class_exists( 'Login_Radius_Admin_Settings' ) ) {

    class Login_Radius_Admin_Settings {
        
        /**
         * Render settings page
         */
        public static function render_options_page() {
            require_once LOGINRADIUS_PLUGIN_DIR . 'admin/views/admin-header.php';

            $loginRadiusSettings = get_option( 'LoginRadius_settings' );
            // rendering LoginRadius plugin admin header
            render_admin_header();
            if ( !isset( $loginRadiusSettings['LoginRadius_apikey'] ) || !isset( $loginRadiusSettings['LoginRadius_secret'] ) || trim( $loginRadiusSettings['LoginRadius_apikey'] ) == '' || trim( $loginRadiusSettings['LoginRadius_secret'] ) == '' ) {
                Admin_Helper:: display_notice_to_insert_api_and_secret();
            }
            // print javascript for plugin settings page 
            Admin_Helper:: render_admin_ui_script();
            ?>
            <div class="wrapper">
                <form action="options.php" method="post">
                    <?php
                    settings_fields( 'LoginRadius_setting_options' );
                    settings_errors();
                    ?>
                    <div class="metabox-holder columns-2" id="post-body">
                        <div class="menu_div" id="tabs">
                            <h2 class="nav-tab-wrapper" style="height:36px">
                                <ul>
                                    <li style="margin-left:9px"><a style="margin:0; height:23px" class="nav-tab" href="#tabs-1"><?php _e( 'API Settings', 'LoginRadius' ) ?></a></li>
                                    <li><a style="margin:0; height:23px" class="nav-tab" href="#tabs-2"><?php _e( 'Social Login', 'LoginRadius' ) ?></a></li>
                                    <li><a style="margin:0; height:23px" class="nav-tab" href="#tabs-3"><?php _e( 'Social Sharing', 'LoginRadius' ) ?></a></li>
                                    <li><a style="margin:0; height:23px" class="nav-tab" href="#tabs-4"><?php _e( 'Social Commenting', 'LoginRadius' ) ?></a></li>
                                    <li><a style="margin:0; height:23px" class="nav-tab" href="#tabs-5"><?php _e( 'Advance Settings', 'LoginRadius' ) ?></a></li>
                                    <li style="float:right; margin-right:8px"><a style="margin:0; height:23px" class="nav-tab" href="#tabs-6"><?php _e( 'Help', 'LoginRadius' ) ?></a></li>
                                </ul>
                            </h2>
                            <?php
                            include 'api-settings/api-settings-view.php';
                            login_radius_render_api_settings_options( $loginRadiusSettings );

                            include 'social-login/social-login-view.php';
                            login_radius_render_social_login_options( $loginRadiusSettings );

                            include 'social-sharing/social-sharing-view.php';
                            login_radius_render_social_sharing_options( $loginRadiusSettings );

                            include 'social-commenting/social-commenting-view.php';
                            login_radius_render_social_commenting_options( $loginRadiusSettings );


                            include 'advance-settings/advance-settings-view.php';
                            login_radius_render_advance_settings_options( $loginRadiusSettings );

                            include 'help/help-view.php';
                            login_radius_render_help_options();
                            ?>
                        </div>
                        <p class="submit">
                            <?php
                            // Build Preview Link
                            $preview_link = get_option( 'home' ) . '/';
                            if ( is_ssl() ) {
                                $preview_link = str_replace( 'http://', 'https://', $preview_link );
                            }
                            $stylesheet = get_option( 'stylesheet' );
                            $template = get_option( 'template' );
                            $preview_link = htmlspecialchars( add_query_arg( array('preview' => 1, 'template' => $template, 'stylesheet' => $stylesheet, 'preview_iframe' => true, 'TB_iframe' => 'true'), $preview_link ) );
                            ?>
                            <input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e( 'Save Changes', 'LoginRadius' ); ?>" />
                            <a href="<?php echo $preview_link; ?>" class="thickbox thickbox-preview" id="preview" ><?php _e( 'Preview', 'LoginRadius' ); ?></a>
                        </p>
                    </div>
                </form>
            </div>
            <?php
        }

    }

}


