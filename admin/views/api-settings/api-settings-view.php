<?php

function login_radius_render_api_settings_options( $loginRadiusSettings ) {
    ?>
    <div class="menu_containt_div" id="tabs-1">
        <div class="inside">
            <table class="form-table editcomment menu_content_table">
                <tr>
                    <td>
                        <p class="loginradiusKeysLabel"><?php _e( 'API Key', 'LoginRadius' ); ?></p>
                        <input type="text" id="login_radius_api_key" name="LoginRadius_settings[LoginRadius_apikey]" value="<?php echo ( isset( $loginRadiusSettings['LoginRadius_apikey'] ) ? htmlspecialchars( $loginRadiusSettings['LoginRadius_apikey'] ) : '' ); ?>" autofill='off' autocomplete='off'  />
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="loginradiusKeysLabel"><?php _e( 'API Secret', 'LoginRadius' ); ?></p>
                        <input type="text" id="login_radius_api_secret" name="LoginRadius_settings[LoginRadius_secret]" value="<?php echo ( isset( $loginRadiusSettings['LoginRadius_secret'] ) ? htmlspecialchars( $loginRadiusSettings['LoginRadius_secret'] ) : '' ); ?>" autofill='off' autocomplete='off'  />
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
