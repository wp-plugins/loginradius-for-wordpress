<?php
// LR_Social_Sharing_Settings

// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the plugin settings page.
 */
if ( ! class_exists( 'LR_Social_Share_Settings' ) ) {

	class LR_Social_Share_Settings {
		
		/**
		 * Render social sharing settings page.
		 */
		public static function render_options_page() {
			global $loginRadiusObject, $loginradius_api_settings, $loginradius_share_settings;
			
			if( isset( $_POST['reset'] ) ) {
				LR_Sharing_Install::reset_share_options();
				echo '<p style="display:none;" class="lr-alert-box lr-notif">Sharing settings have been reset and default values have been applied to the plug-in</p>';
				echo '<script type="text/javascript">jQuery(function(){jQuery(".lr-notif").slideDown().delay(3000).slideUp();});</script>';
			}

			?>
				<!-- LR-wrap -->
				<div class="wrap lr-wrap cf">
					<header>
						<h2 class="logo"><a href="//www.loginradius.com" target="_blank">LoginRadius</a><em>Simplified Social Share</em></h2>
					</header>
					<div id="lr_options_tabs" class="cf">
						<ul class="lr-options-tab-btns">
							<li class="nav-tab lr-active" data-tab="lr_options_tab-1"><?php _e( 'Horizontal Sharing', 'LoginRadius' ) ?></li>
							<li class="nav-tab" data-tab="lr_options_tab-2"><?php _e( 'Vertical Sharing', 'LoginRadius' ) ?></li>
							<li class="nav-tab" data-tab="lr_options_tab-3"><?php _e( 'Advanced Settings', 'LoginRadius' ) ?></li>
						</ul>
						
						<!-- Settings -->
						<form method="post" action="options.php">
							<?php settings_fields( 'loginradius_share_settings' ); ?>
							
							<?php
								include_once( 'horizontal-settings/horizontal-view.php' );
								$view = new LR_Social_Share_Horizontal_Settings();
								$view->render_options_page();

								include_once( 'vertical-settings/vertical-view.php' );
								$view = new LR_Social_Share_Vertical_Settings();
								$view->render_options_page();
							?>
						<!-- Settings -->
						
						<!-- Advanced Settings -->
						<div id="lr_options_tab-3" class="lr-tab-frame">
							<div class="lr_options_container">
								<div class="lr-row">
									<h3>
										Short Code for Sharing widget
										<span class="lr-tooltip tip-bottom" data-title="Copy and paste the following shortcode into a page or post to display a horizontal sharing widget">
											<span class="dashicons dashicons-editor-help"></span>
										</span>
									</h3>
									<div>
										<textarea rows="1" onclick="this.select()" spellcheck="false" class="lr-shortcode" readonly="readonly">[LoginRadius_Share]</textarea>
									</div>
									<span>Additional shortcode examples can be found <a target="_blank" href='http://ish.re/9WBX/#shortcode' >Here</a></span>
								</div><!-- lr-row -->
								<div class="lr-row">
									<h3>
										Mobile Friendly
										<span class="lr-tooltip tip-bottom" data-title="Enable this option to show a mobile sharing interface to mobile users">
											<span class="dashicons dashicons-editor-help"></span>
										</span>
									</h3>
									<div>
										<label for="lr-enable-mobile-friendly" class="lr-toggle">
											<input type="checkbox" class="lr-toggle" id="lr-enable-mobile-friendly" name="LoginRadius_share_settings[mobile_enable]" value="1" <?php echo ( isset($loginradius_share_settings['mobile_enable']) && $loginradius_share_settings['mobile_enable'] == '1') ? 'checked' : ''; ?> />
											<span class="lr-toggle-name">Enable Mobile Friendly</span>
										</label>
									</div>
								</div>
							</div>
							<?php submit_button('Save changes'); ?>
						</form>
							<div class="lr_options_container">	
								<div class="lr-row">
									<h5>
										Reset all the sharing options to the recommended settings
										<span class="lr-tooltip" data-title="This option will reset all the settings to the default sharing plugin settings">
											<span class="dashicons dashicons-editor-help"></span>
										</span>
									</h5>
									<div>
										<form method="post" action="" class="lr-reset">
											<?php submit_button( 'Reset All Options', 'secondary', 'reset', false ); ?>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div><!-- lr_options_tabs -->
						<div class="lr-sidebar">
							<div class="lr-frame">
								<h4><?php _e( 'Help', 'LoginRadius' ); ?></h4>
								<div>
								<a target="_blank" href="http://ish.re/9WBX"><?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a>
								<a target="_blank" href="http://ish.re/8PG2"><?php _e( 'Community Forum', 'LoginRadius' ) ?></a>
								<a target="_blank" href="http://ish.re/8PJ7"><?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a>
								<a target="_blank" href="http://ish.re/5P2D"><?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a>
								<a target="_blank" href="http://ish.re/C8E7"><?php _e( 'CMS Plugins', 'LoginRadius' ) ?></a>
								<a target="_blank" href="http://ish.re/C9F7"><?php _e( 'API Libraries', 'LoginRadius' ) ?></a>
							</div>
						</div><!-- lr-frame -->
						<div class="lr-frame">
							<h4><?php _e( 'Follow Us', 'LoginRadius' ); ?></h4>
							<div style="text-align: center;">
								<a class="lrshare_iconsprite42 lr-icon-facebook" href="http://www.facebook.com/loginradius" target="_blank"></a>
								<a class="lrshare_iconsprite42 lr-icon-twitter" href="http://twitter.com/LoginRadius" target="_blank"></a>
								<a class="lrshare_iconsprite42 lr-icon-googleplus" href="http://plus.google.com/+Loginradius" target="_blank"></a>
								<a class="lrshare_iconsprite42 lr-icon-linkedin" href="http://www.linkedin.com/company/loginradius" target="_blank"></a>
							</div>
						</div>
					</div><!-- sidebar -->
					<!--[if lt IE 8]>
					<script src="https://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
					<![endif]-->
				</div><!-- End LR-wrap -->

			<?php
		}
	}
}
new LR_Social_Share_Settings();