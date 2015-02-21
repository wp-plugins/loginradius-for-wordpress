<?php

/**
 *
 * function for rendering Social Commenting tab on settings page.
 */
function lr_render_social_commenting_options( $loginRadiusSettings ) {
    ?>
    <div id="lr_options_tab-2" class="lr-tab-frame">
        <div class="lr_options_container">
                <div class="lr-row">
                    <h3>
                        <?php _e( 'Enable Social Commenting', 'LoginRadius' ); ?>
                        <span class="lr-tooltip tip-bottom" data-title="Turn on, if you want to enable Social Commenting">
                            <span class="dashicons dashicons-editor-help"></span>
                        </span>
                    </h3>
                    <div>
                        <div>
                            <input type="checkbox" class="lr-toggle" id="lr-clicker-commenting" name="LoginRadius_settings[LoginRadius_commentEnable]" value="1" <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_commentEnable'] ) && $loginRadiusSettings['LoginRadius_commentEnable'] == '1' ) ) ? 'checked' : '' ?> />
                            <label class="lr-show-toggle" for="lr-clicker-commenting">
                            </label>
                        </div>
                        <div class="lr-commenting-options">
                                <h4>
                                    <?php _e( 'Choose where you want the Social Login interface to be displayed on the WordPress commenting form', 'LoginRadius' ); ?>
                                    <span class="lr-tooltip" data-title="Select the position of the Social Login interface on WordPress commenting form">
                                        <span class="dashicons dashicons-editor-help"></span>
                                    </span>
                                </h4>
                                <label>
                                    <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="after_leave_reply" <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'after_leave_reply' ) || !isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) ) ? 'checked="checked"' : '' ?> />
                                    <span><?php _e( "After the 'Leave a Reply' caption", 'LoginRadius' ) ?></span>
                                </label>
                                <label>
                                    <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="very_top" <?php echo ( isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'very_top' ) ? 'checked="checked"' : '' ?> />
                                    <span><?php _e( 'At the very top of the comment form', 'LoginRadius' ) ?></span>
                                </label>
                                <label>
                                    <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="very_bottom" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'very_bottom' ? 'checked="checked"' : '' ?> />
                                    <span><?php _e( 'At the very bottom of the comment form', 'LoginRadius' ) ?></span>
                                </label>
                                <label>
                                    <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="before_fields" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'before_fields' ? 'checked="checked"' : '' ?> />
                                    <span><?php _e( 'Before the comment form input fields', 'LoginRadius' ) ?></span>
                                </label>
                                <label>
                                    <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="after_fields" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'after_fields' ? 'checked="checked"' : '' ?> />
                                    <span><?php _e( 'Before the comment box', 'LoginRadius' ) ?></span>
                                </label>
                        </div>
                    </div>
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
            <input style="margin-left:8px" type="submit" name="save" class="button button-primary" value="<?php _e( 'Save Changes', 'LoginRadius' ); ?>" />
            <a href="<?php echo $preview_link; ?>" class="thickbox thickbox-preview" id="preview" ><?php _e( 'Preview', 'LoginRadius' ); ?></a>
        </p>
    </div>
    <?php
}
