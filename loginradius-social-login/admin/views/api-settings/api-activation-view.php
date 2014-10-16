<?php

function loginradius() {
    global $loginradius_api_settings;
    $loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
    ?>
    <!-- LR-wrap -->
    <div class="wrap lr-wrap cf">
        <header>
            <h2 class="logo"><a href="//loginradius.com" target="_blank">LoginRadius</a><em>Activation</em></h2>
        </header>
    <?php
        if ( !isset( $loginradius_api_settings['LoginRadius_apikey'] ) || !isset( $loginradius_api_settings['LoginRadius_secret'] ) || trim( $loginradius_api_settings['LoginRadius_apikey'] ) == '' || trim( $loginradius_api_settings['LoginRadius_secret'] ) == '' ) {
            Admin_Helper:: display_notice_to_insert_api_and_secret();
        }
    ?>
    <form action="options.php" method="post">
        <?php
            settings_fields( 'loginradius_api_settings' );
            settings_errors();
        ?>
        <div class="lr_options_container">
            <div class="lr-row">
                <h3>To activate Social Login, insert the LoginRadius API Key and Secret in the section below. If you don't have them, please follow the <a href="http://support.loginradius.com/hc/en-us/articles/201894526-How-do-I-get-a-LoginRadius-API-key-and-secret-" target="_blank">instructions</a>.</h3>
                <label>
                    <span class="lr_property_title"><?php _e( 'LoginRadius API Key', 'LoginRadius' ); ?>
                        <span class="lr-tooltip" data-title="Your unique LoginRadius API Key">
                                    <span class="dashicons dashicons-editor-help"></span>
                        </span>
                    </span>
                    <input type="text" id="login_radius_api_key" name="LoginRadius_API_settings[LoginRadius_apikey]" value="<?php echo ( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty($loginradius_api_settings['LoginRadius_apikey']) ) ? $loginradius_api_settings['LoginRadius_apikey'] : ''; ?>" autofill='off' autocomplete='off' />
                </label>
                
                <label >
                    <span class="lr_property_title"><?php _e( 'LoginRadius API Secret', 'LoginRadius' ); ?>
                        <span class="lr-tooltip" data-title="Your unique LoginRadius API Secret">
                                    <span class="dashicons dashicons-editor-help"></span>
                        </span>
                    </span>
                    <input type="text" id="login_radius_api_secret" name="LoginRadius_API_settings[LoginRadius_secret]" value="<?php echo $loginradius_api_settings['LoginRadius_secret']; ?>" autofill='off' autocomplete='off' />
                </label>
            </div>
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
            <input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e( 'Verify and Save', 'LoginRadius' ); ?>" />
            <a href="<?php echo $preview_link; ?>" class="thickbox thickbox-preview" id="preview" ><?php _e( 'Preview', 'LoginRadius' ); ?></a>
        </p>
    </form>
    </div>
    <?php
}
