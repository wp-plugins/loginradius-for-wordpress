<?php
/**
 * Function for rendering Help tab on plugin on settings page
 */
function login_radius_render_help_options() {
    ?>
    <div class="menu_containt_div" id="tabs-6">
        <div class="stuffbox">
            <h3><label><?php _e( 'Help & Documentations', 'LoginRadius' ); ?></label></h3>
            <div class="inside">
                <table class="form-table editcomment menu_content_table">
                    <tr id="login_radius_vertical_position_counter">
                        <td>
                            <ul style="float:left; margin-right:86px">
                                <li><a target="_blank" href="http://ish.re/BENH"><?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/9VBI"><?php _e( 'How to get LoginRadius API Key & Secret', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/BGT3"><?php _e( 'WP Multisite Feature', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/8PG2"><?php _e( 'Discussion Forum', 'LoginRadius' ) ?></a></li>
                            </ul>
                            <ul style="float:left">
                                <li><a target="_blank" href="http://ish.re/96M7"><?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/8PG5"><?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/8PG8"><?php _e( 'Social Plugins', 'LoginRadius' ) ?></a></li>
                                <li><a target="_blank" href="http://ish.re/6JMW"><?php _e( 'Social SDKs', 'LoginRadius' ) ?></a></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php
}
