<?php
/**
 * This function renders plugin settings page header cntaing plugin information and help links
 */
function render_admin_header() {
    ?>
    <div class="header_div">
        <h2>LoginRadius <?php _e( 'Social Plugin Settings', 'LoginRadius' ) ?></h2>
        <div id="loginRadiusError" style="background-color: #FFFFE0; border:1px solid #E6DB55; padding:5px; margin-bottom:5px; width: 1024px;">
            <?php _e( 'Please clear your browser cache, if you have trouble loading the plugin interface. For more information', 'LoginRadius' ) ?> <a target="_blank" href="http://ish.re/BBR5" >  <?php _e( 'click here', 'LoginRadius' ) ?> </a>.
        </div>
        <fieldset id = "welcome-message-fieldset">
            <h4 style="color:#000"><strong><?php _e( 'Thank you for installing the LoginRadius Social Plugin!', 'LoginRadius' ) ?></strong></h4>
            <p><?php _e( 'To activate the plugin, you will need to first configure it ( manage your desired social networks, etc. ) from your LoginRadius account. If you do not have an account, click', 'LoginRadius' ) ?> <a target="_blank" href="http://ish.re/4"><?php _e( 'here', 'LoginRadius' ) ?></a> <?php _e( 'and create one for FREE!', 'LoginRadius' ); ?></p>
            <p>
                <?php _e( 'We also offer Social Plugins for ', 'LoginRadius' ) ?> <a href="http://ish.re/8PE6" target="_blank">Joomla</a>, <a href="http://ish.re/8PE9" target="_blank">Drupal</a>, <a href="http://ish.re/8PEC" target="_blank">Magento</a>, <a href="http://ish.re/8PED" target="_blank">vBulletin</a>, <a href="http://ish.re/8PEE" target="_blank">VanillaForum</a>, <a href="http://ish.re/8PEG" target="_blank">osCommerce</a>, <a href="http://ish.re/8PEH" target="_blank">PrestaShop</a>, <a href="http://ish.re/8PFQ" target="_blank">X-Cart</a>, <a href="http://ish.re/8PFR" target="_blank">Zen-Cart</a>, <a href="http://ish.re/8PFS" target="_blank">DotNetNuke</a>, <a href="http://ish.re/8PFT" target="_blank">SMF</a> <?php echo _e( 'and' ) ?> <a href="http://ish.re/8PFV" target="_blank">phpBB</a> !
            </p>
            <?php
            if ( !Admin_Helper:: loginradius_api_secret_saved() ) {
                ?>
                <a style="text-decoration:none;" href="http://ish.re/B45W" target="_blank">
                    <input style="margin-top:10px" class="greenbutton green" type="button" value="<?php _e( 'Enable Plugin Now!', 'LoginRadius' ); ?>" />
                </a><br />
                <?php
            }
            ?>
        </fieldset>
        <fieldset id = "plugin-details">
            <div style="margin:5px 0">
                <strong>Plugin Version: </strong><?php echo LOGINRADIUS_SOCIALLOGIN_VERSION; ?><br/>
                <strong>Author:</strong> LoginRadius<br/>
                <strong>Website:</strong> <a href="https://www.loginradius.com" target="_blank">www.loginradius.com</a> <br/>
                <strong>Community:</strong> <a href="http://community.loginradius.com" target="_blank">community.loginradius.com</a> <br/>
                <div id="sociallogin_get_update" style="float:left;">
                    <b><?php _e( 'Get Updates', 'LoginRadius' ); ?></b><br>
                    <?php _e( 'To receive updates on new features, releases, etc. Please connect to one of our social media pages', 'LoginRadius' ); ?>
                </div>
                <div>
                    <center>
                        <a target="_blank" href="https://www.facebook.com/loginradius"><img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/media-pages/facebook.png'; ?>"></a>
                        <a target="_blank" href="https://twitter.com/LoginRadius"><img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/media-pages/twitter.png'; ?>"></a>
                        <a target="_blank" href="https://plus.google.com/+Loginradius"> <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/media-pages/google.png'; ?>"></a>
                        <a target="_blank" href="http://www.linkedin.com/company/loginradius"> <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/media-pages/linkedin.png'; ?>"></a>
                        <a target="_blank" href="https://www.youtube.com/user/LoginRadius"> <img src="<?php echo LOGINRADIUS_PLUGIN_URL . 'assets/images/media-pages/youtube.png'; ?>"></a>
                    </center>
                </div>
            </div>
        </fieldset>

        <fieldset class="help_div">
            <h4 style="border-bottom:#d7d7d7 1px solid;"><strong><?php _e( 'Help & Documentations', 'LoginRadius' ) ?></strong></h4>
            <ul style="float:left; margin-right:43px">
                <li><a target="_blank" href="http://ish.re/BENH"><?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/9VBI"><?php _e( 'How to get LoginRadius API Key & Secret', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/BGT3"><?php _e( 'WP Multisite Feature', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/8PG8"><?php _e( 'Social Plugins', 'LoginRadius' ) ?></a></li>
            </ul>
            <ul style="float:left; margin-right:43px">
                <li><a target="_blank" href="http://ish.re/8PG2"><?php _e( 'Discussion Forum', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/96M7"><?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/8PG5"><?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a></li>
                <li><a target="_blank" href="http://ish.re/6JMW"><?php _e( 'Social SDKs', 'LoginRadius' ) ?></a></li>
            </ul>
        </fieldset>

        <fieldset id = "support-us-box">
            <h4 style="border-bottom:#d7d7d7 1px solid;"><strong><?php _e( 'Support Us', 'LoginRadius' ) ?></strong></h4>
            <p>
                <?php _e( 'If you liked our FREE open-source plugin, please send your feedback/testimonial to ', 'LoginRadius' ) ?><a href="mailto:feedback@loginradius.com">feedback@loginradius.com</a> !
                <?php _e( 'Please help us to ', 'LoginRadius' ) ?><a target="_blank" href="http://ish.re/8PFX"><?php _e( 'translate', 'LoginRadius' ) ?> </a><?php _e( 'the plugin content in your language.', 'LoginRadius' ) ?>
            </p>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php
}
