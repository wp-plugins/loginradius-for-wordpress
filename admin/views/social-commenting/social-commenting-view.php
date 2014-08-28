<?php

/**
 *
 * function for rendering Social Commenting tab on settings page.
 */
function login_radius_render_social_commenting_options( $loginRadiusSettings ) {
    ?>
    <div class="menu_containt_div" id="tabs-4">
        <div class="stuffbox">
            <h3><label><?php _e( 'Social Commenting Settings', 'LoginRadius' ) ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Do you want to enable social commenting for your website' ); ?>?</div>
                            <div class="loginRadiusYesRadio">
                                <input type="radio" name="LoginRadius_settings[LoginRadius_commentEnable]" value='1' <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_commentEnable'] ) && $loginRadiusSettings['LoginRadius_commentEnable'] == 1 ) || !isset( $loginRadiusSettings['LoginRadius_commentEnable'] ) ) ? 'checked' : '' ?>/> <?php _e( 'Yes', 'LoginRadius' ); ?>
                            </div>
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentEnable]" value="0" <?php checked( '0', @$loginRadiusSettings['LoginRadius_commentEnable'] ); ?>/> <?php _e( 'No', 'LoginRadius' ); ?>
                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="loginRadiusQuestion"><?php _e( 'Where do you want to display the Social login interface on the commenting form?', 'LoginRadius' ); ?></div>
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="after_leave_reply" <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'after_leave_reply' ) || !isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) ) ? 'checked="checked"' : '' ?> ><?php _e( "After the 'Leave a Reply' caption", 'LoginRadius' ) ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="very_top" <?php echo ( isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'very_top' ) ? 'checked="checked"' : '' ?> ><?php _e( 'At the very top of the comment form', 'LoginRadius' ) ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="very_bottom" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'very_bottom' ? 'checked="checked"' : '' ?> ><?php _e( 'At the very bottom of the comment form', 'LoginRadius' ) ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="before_fields" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'before_fields' ? 'checked="checked"' : '' ?> ><?php _e( 'Before the comment form input fields', 'LoginRadius' ) ?><br />
                            <input type="radio" name="LoginRadius_settings[LoginRadius_commentInterfacePosition]" value="after_fields" <?php echo isset( $loginRadiusSettings['LoginRadius_commentInterfacePosition'] ) && $loginRadiusSettings['LoginRadius_commentInterfacePosition'] == 'after_fields' ? 'checked="checked"' : '' ?> ><?php _e( 'Before the comment box', 'LoginRadius' ) ?>

                            <div class="loginRadiusBorder"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}
