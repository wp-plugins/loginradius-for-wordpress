<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * The main class and initialization point of the plugin settings page.
 */
if ( ! class_exists( 'LR_Social_Share_Horizontal_Settings' ) ) {

	class LR_Social_Share_Horizontal_Settings {
		
		public function render_options_page() {
			global $loginradius_share_settings;
			?>
			<!-- Horizontal Sharing -->
			<div id="lr_options_tab-1" class="lr-tab-frame lr-active">

				<!-- Horizontal Options -->
				<div class="lr_options_container">

					<!-- Horizontal Switch -->
					<div id="lr_horizontal_switch" class="lr-row">
						<label for="lr-enable-horizontal" class="lr-toggle">
							<input type="checkbox" class="lr-toggle" id="lr-enable-horizontal" name="LoginRadius_share_settings[horizontal_enable]" value="1" <?php echo ( isset($loginradius_share_settings['horizontal_enable']) && $loginradius_share_settings['horizontal_enable'] == '1') ? 'checked' : ''; ?> />
							<span class="lr-toggle-name">Enable Horizontal Widget</span>
						</label>
					</div>
					<div class="lr-option-disabled-hr"></div>
					<div class="lr_horizontal_interface lr-row">
						<h3>Select the sharing theme</h3>
						<div>
							<input type="radio" id="lr-horizontal-lrg" name="LoginRadius_share_settings[horizontal_share_interface]" value="32-h" <?php echo ( !isset( $loginradius_share_settings['horizontal_share_interface'] ) || $loginradius_share_settings['horizontal_share_interface'] == '32-h' ) ? 'checked' : ''; ?> />
							<label class="lr_horizontal_interface_img" for="lr-horizontal-lrg"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/32-h.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-horizontal-sml" name="LoginRadius_share_settings[horizontal_share_interface]" value="16-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == '16-h' ) ? 'checked' : ''; ?> />

							<label class="lr_horizontal_interface_img" for="lr-horizontal-sml"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/16-h.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-single-lg-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="single-lg-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'single-lg-h' ) ? 'checked' : ''; ?> />

							<label class="lr_horizontal_interface_img" for="lr-single-lg-h"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/single-lg-h.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-single-sm-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="single-sm-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'single-sm-h' ) ? 'checked' : ''; ?> />

							<label class="lr_horizontal_interface_img" for="lr-single-sm-h"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/single-sm-h.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-sharing/hybrid-h-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="hybrid-h-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'hybrid-h-h' ) ? 'checked' : ''; ?> />

							<label class="lr_horizontal_interface_img" for="lr-sharing/hybrid-h-h"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-h-h.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-hybrid-h-v" name="LoginRadius_share_settings[horizontal_share_interface]" value="hybrid-h-v" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'hybrid-h-v' ) ? 'checked' : ''; ?> />

							<label class="lr_horizontal_interface_img" for="lr-hybrid-h-v"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-h-v.png" ?>" /></label>
						</div>
					</div>
					<div id="lr_hz_theme_options" class="lr-row cf">
						<h3>
							Select the sharing networks
							<span class="lr-tooltip" data-title="Selected sharing networks will be displayed in the widget">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						<div id="lr_hz_hz_theme_options" style="display:block;">
							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Delicious]" value="Delicious" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Delicious']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Delicious'] == 'Delicious' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-delicious">Delicious</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Digg]" value="Digg" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Digg']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Digg'] == 'Digg' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-digg">Digg</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Email]" value="Email" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Email']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Email'] == 'Email' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-email">Email</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Facebook]" value="Facebook" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Facebook']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Facebook'] == 'Facebook' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-facebook">Facebook</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][GooglePlus]" value="GooglePlus" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['GooglePlus']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['GooglePlus'] == 'GooglePlus' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-googleplus">Google&#43;</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Google]" value="Google" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Google']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Google'] == 'Google' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-google">Google</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][LinkedIn]" value="LinkedIn" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['LinkedIn']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['LinkedIn'] == 'LinkedIn' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-linkedin">LinkedIn</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][MySpace]" value="MySpace" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['MySpace']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['MySpace'] == 'MySpace' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-myspace">MySpace</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Pinterest]" value="Pinterest" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Pinterest']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Pinterest'] == 'Pinterest' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-pinterest">Pinterest</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Print]" value="Print" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Print']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Print'] == 'Print' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-print">Print</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Reddit']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-reddit">Reddit</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Tumblr]" value="Tumblr" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Tumblr']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Tumblr'] == 'Tumblr' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-tumblr">Tumblr</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Twitter]" value="Twitter" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Twitter']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Twitter'] == 'Twitter' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-twitter">Twitter</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Default][Vkontakte]" value="Vkontakte" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Default']['Vkontakte']) && $loginradius_share_settings['horizontal_sharing_providers']['Default']['Vkontakte'] == 'Vkontakte' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-vkontakte">Vkontakte</span>
							</label>
							<div id="loginRadiusHorizontalSharingLimit" class="lr-alert-box" style="display:none; margin-bottom: 5px;"><?php _e( 'You can select only eight providers', 'LoginRadius' ) ?>.</div>
							<p class="lr-footnote">*All other icons will be included in the pop-up</p>
						</div>
						<div id="lr_hz_ve_theme_options" style="display:none;">
							<!-- <h5>Vertical Theme</h5> -->
							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Like]" value="Facebook Like" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Like']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Like'] == 'Facebook Like' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Facebook Like</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Twitter Tweet]" value="Twitter Tweet" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Twitter Tweet']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Twitter Tweet'] == 'Twitter Tweet' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Twitter Tweet</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][StumbleUpon Badge]" value="StumbleUpon Badge" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['StumbleUpon Badge']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['StumbleUpon Badge'] == 'StumbleUpon Badge' ) ? 'checked' : ''; ?> />
								<span class="lr-text">StumbleUpon Badge</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Google+ Share]" value="Google+ Share" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ Share']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ Share'] == 'Google+ Share' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Google+ Share</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Recommend]" value="Facebook Recommend" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Recommend']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Recommend'] == 'Facebook Recommend' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Facebook Recommend</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Pinterest Pin it]" value="Pinterest Pin it" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Pinterest Pin it']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Pinterest Pin it'] == 'Pinterest Pin it' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Pinterest Pin it</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Reddit']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Reddit</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Hybridshare]" value="Hybridshare" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Hybridshare']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Hybridshare'] == 'Hybridshare' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Hybridshare</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Send]" value="Facebook Send" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Send']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Send'] == 'Facebook Send' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Facebook Send</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][LinkedIn Share]" value="LinkedIn Share" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['LinkedIn Share']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['LinkedIn Share'] == 'LinkedIn Share' ) ? 'checked' : ''; ?> />
								<span class="lr-text">LinkedIn Share</span>
							</label>

							<label class="lr-sharing-cb">
								<input type="checkbox" class="LoginRadius_hz_ve_share_providers" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Google+ +1]" value="Google+ +1" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ +1']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ +1'] == 'Google+ +1' ) ? 'checked' : ''; ?> />
								<span class="lr-text">Google+ +1</span>
							</label>
							<div id="loginRadiusHorizontalVerticalSharingLimit" class="lr-alert-box" style="display:none; margin-bottom: 5px;">
								<?php _e( 'You can select only eight providers', 'LoginRadius' ) ?>.
							</div>
							<p class="lr-footnote"></p>
						</div>
					</div>
					<div class="lr-row" id="login_radius_horizontal_rearrange_container">
						<h3>
							<?php _e( 'Select the sharing networks order', 'LoginRadius' ) ?>
							<span class="lr-tooltip" data-title="Drag the icons around to set the order">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						
						<ul id="loginRadiusHorizontalSortable" class="cf">
							<?php
							if ( isset( $loginradius_share_settings['horizontal_rearrange_providers'] ) && count( $loginradius_share_settings['horizontal_rearrange_providers'] ) > 0 ) {
								foreach ( $loginradius_share_settings['horizontal_rearrange_providers'] as $provider ) {
									?>
									<li title="<?php echo $provider ?>" id="loginRadiusHorizontalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lr-icon-<?php echo strtolower( $provider ) ?>">
										<input type="hidden" name="LoginRadius_share_settings[horizontal_rearrange_providers][]" value="<?php echo $provider ?>" />
									</li>
									<?php
								}
							}
							?>
						</ul>
						<ul class="lr-static">
							<li title="More" id="loginRadiusHorizontalLImore" class="lr-pin lr-icon-more lrshare_evenmore">
							</li>

							<li title="Counter" id="loginRadiusHorizontalLIcounter" class="lr-pin lr-counter">1.2m
							</li>
						</ul>
					</div>
					<div class="lr-row cf">
						<h3>
							Choose the location(s) to display the widget
							<span class="lr-tooltip" data-title="Sharing widget will be displayed at the selected location(s)">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-hr-home" name="LoginRadius_share_settings[lr-clicker-hr-home]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-hr-home']) && $loginradius_share_settings['lr-clicker-hr-home'] == '1') ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-hr-home">
								Home Page
								<span class="lr-tooltip" data-title="Home page of your blog">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>

							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-hr-home-options default" name="LoginRadius_share_settings[horizontal_position][Home][Top]" value="Top" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Home']['Top']) && $loginradius_share_settings['horizontal_position']['Home']['Top'] == 'Top' ) ? 'checked' : ''; ?> />
									<span>Top of the content</span>
								</label>
								<label>
									<input type="checkbox" class="lr-clicker-hr-home-options" name="LoginRadius_share_settings[horizontal_position][Home][Bottom]" value="Bottom" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Home']['Bottom']) && $loginradius_share_settings['horizontal_position']['Home']['Bottom'] == 'Bottom' ) ? 'checked' : ''; ?> />
									<span>Bottom of the content</span>
								</label>
							</div>
						</div>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-hr-post" name="LoginRadius_share_settings[lr-clicker-hr-post]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-hr-post']) && $loginradius_share_settings['lr-clicker-hr-post'] == '1') ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-hr-post">
								Blog Post
								<span class="lr-tooltip" data-title="Each post of your blog">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>

							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-hr-post-options default" name="LoginRadius_share_settings[horizontal_position][Posts][Top]" value="Top" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Posts']['Top']) && $loginradius_share_settings['horizontal_position']['Posts']['Top'] == 'Top' ) ? 'checked' : ''; ?> />
									<span>Top of the content</span>
								</label>
								<label>
									<input type="checkbox" class="lr-clicker-hr-post-options default" name="LoginRadius_share_settings[horizontal_position][Posts][Bottom]" value="Bottom" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Posts']['Bottom']) && $loginradius_share_settings['horizontal_position']['Posts']['Bottom'] == 'Bottom' ) ? 'checked' : ''; ?> />
									<span>Bottom of the content</span>
								</label>
							</div>
						</div>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-hr-static" name="LoginRadius_share_settings[lr-clicker-hr-static]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-hr-static']) && $loginradius_share_settings['lr-clicker-hr-static'] == '1') ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-hr-static">
								Static Pages
								<span class="lr-tooltip" data-title="Static pages of your blog (e.g &ndash; about, contact, etc.)">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>

							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-hr-static-options default" name="LoginRadius_share_settings[horizontal_position][Pages][Top]" value="Top" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Pages']['Top']) && $loginradius_share_settings['horizontal_position']['Pages']['Top'] == 'Top' ) ? 'checked' : ''; ?> />
									<span>Top of the content</span>
								</label>
								<label>
									<input type="checkbox" class="lr-clicker-hr-static-options" name="LoginRadius_share_settings[horizontal_position][Pages][Bottom]" value="Bottom" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Pages']['Bottom']) && $loginradius_share_settings['horizontal_position']['Pages']['Bottom'] == 'Bottom' ) ? 'checked' : ''; ?> />
									<span>Bottom of the content</span>
								</label>
							</div>
						</div>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-hr-excerpts" name="LoginRadius_share_settings[lr-clicker-hr-excerpts]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-hr-excerpts']) && $loginradius_share_settings['lr-clicker-hr-excerpts'] == '1') ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-hr-excerpts">
								Post Excerpts
								<span class="lr-tooltip" data-title="Post excerpts page">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>
							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-hr-excerpts-options default" name="LoginRadius_share_settings[horizontal_position][Excerpts][Top]" value="Top" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Top']) && $loginradius_share_settings['horizontal_position']['Excerpts']['Top'] == 'Top' ) ? 'checked' : ''; ?> />
									<span>Top of the content</span>
								</label>
								<label>
									<input type="checkbox" class="lr-clicker-hr-excerpts-options" name="LoginRadius_share_settings[horizontal_position][Excerpts][Bottom]" value="Bottom" <?php echo ( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Bottom']) && $loginradius_share_settings['horizontal_position']['Excerpts']['Bottom'] == 'Bottom' ) ? 'checked' : ''; ?> />
									<span>Bottom of the content</span>
								</label>
							</div><!-- options -->
						</div>
					</div><!-- row -->
				</div><!-- Container -->
				<?php submit_button('Save changes'); ?>
			</div><!-- End Horizontal Sharing -->

			<?php
		}
	}
}