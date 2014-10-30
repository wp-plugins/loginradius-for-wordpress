<?php
/**
 * Function for rendering Help tab on plugin on settings page
 */
function login_radius_render_help_options() {
    ?>
    <div class="lr-sidebar">
        <div class="lr-frame">
            <h4><?php _e( 'Help & Documentations', 'LoginRadius' ); ?></h4>
            <div>
                    <a target="_blank" href="http://ish.re/BENH"><?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/9VBI"><?php _e( 'How to get LoginRadius API Key & Secret', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/BGT3"><?php _e( 'WP Multisite Feature', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/8PG2"><?php _e( 'Discussion Forum', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/96M7"><?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/8PG5"><?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/C8E7"><?php _e( 'Social Plugins', 'LoginRadius' ) ?></a>
                    <a target="_blank" href="http://ish.re/6JMW"><?php _e( 'Social SDKs', 'LoginRadius' ) ?></a>
            </div>
        </div>
    </div>
    <?php
}
