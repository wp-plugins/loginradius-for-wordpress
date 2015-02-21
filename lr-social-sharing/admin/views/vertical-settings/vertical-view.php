<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * The main class and initialization point of the plugin settings page.
 */
if ( ! class_exists( 'LR_Social_Share_Vertical_Settings' ) ) {

	class LR_Social_Share_Vertical_Settings {

		public function render_options_page() {
			global $loginradius_share_settings;
			?>
			<!-- Vertical Sharing -->
			<div id="lr_options_tab-2" class="lr-tab-frame">
				<!-- Vertical Options -->
				<div class="lr_options_container">

					<!-- Vertical Switch -->
					<div id="lr_vertical_switch" class="lr-row">
						<label for="lr-enable-vertical" class="lr-toggle">
							<input type="checkbox" class="lr-toggle" id="lr-enable-vertical" name="LoginRadius_share_settings[vertical_enable]" value="1" <?php echo ( isset($loginradius_share_settings['vertical_enable']) && $loginradius_share_settings['vertical_enable'] == '1') ? 'checked' : ''; ?> <?php _e( 'Yes', 'LoginRadius' ) ?> />
							<span class="lr-toggle-name">Enable Vertical Widget</span>
						</label>
					</div>

					<div class="lr-option-disabled-vr"></div>
					<div class="lr_vertical_interface lr-row cf">
						<h3>Select the sharing theme</h3>
						<div>
							<input type="radio" id="lr-vertical-32-v" name="LoginRadius_share_settings[vertical_share_interface]" value="32-v" <?php echo ( !isset( $loginradius_share_settings['vertical_share_interface'] ) || $loginradius_share_settings['vertical_share_interface'] == '32-v' ) ? 'checked' : ''; ?> />

							<label class="lr_vertical_interface_img" for="lr-vertical-32-v"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/32-v.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-vertical-16-v" name="LoginRadius_share_settings[vertical_share_interface]" value="16-v" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == '16-v' ) ? 'checked' : ''; ?> />

							<label class="lr_vertical_interface_img" for="lr-vertical-16-v"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/16-v.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-vertical-hybrid-v-v" name="LoginRadius_share_settings[vertical_share_interface]" value="hybrid-v-v" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == 'hybrid-v-v' ) ? 'checked' : ''; ?> />

							<label class="lr_vertical_interface_img" for="lr-vertical-hybrid-v-v"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-v-v.png" ?>" /></label>
						</div>
						<div>
							<input type="radio" id="lr-vertical-hybrid-v-h" name="LoginRadius_share_settings[vertical_share_interface]" value="hybrid-v-h" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == 'hybrid-v-h' ) ? 'checked' : ''; ?> />

							<label class="lr_vertical_interface_img" for="lr-vertical-hybrid-v-h"><img src="<?php echo LR_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-v-h.png" ?>" /></label>
						</div>
					</div>

					<div id="lr_ve_theme_options" class="lr-row cf">
						<h3>
							Select the sharing networks
							<span class="lr-tooltip" data-title="Selected sharing networks will be displayed in the widget">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						<div id="lr_ve_hz_theme_options" class="cf" style="display:block;">
							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Delicious]" value="Delicious" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Delicious']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Delicious'] == 'Delicious' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-delicious">Delicious</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Digg]" value="Digg" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Digg']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Digg'] == 'Digg' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-digg">Digg</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Email]" value="Email" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Email']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Email'] == 'Email' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-email">Email</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Facebook]" value="Facebook" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Facebook']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Facebook'] == 'Facebook' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-facebook">Facebook</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][GooglePlus]" value="GooglePlus" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['GooglePlus']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['GooglePlus'] == 'GooglePlus' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-googleplus">Google&#43;</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Google]" value="Google" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Google']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Google'] == 'Google' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-google">Google</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][LinkedIn]" value="LinkedIn" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['LinkedIn']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['LinkedIn'] == 'LinkedIn' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-linkedin">LinkedIn</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][MySpace]" value="MySpace" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['MySpace']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['MySpace'] == 'MySpace' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-myspace">MySpace</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Pinterest]" value="Pinterest" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Pinterest']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Pinterest'] == 'Pinterest' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-pinterest">Pinterest</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Print]" value="Print" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Print']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Print'] == 'Print' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-print">Print</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Reddit']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-reddit">Reddit</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Tumblr]" value="Tumblr" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Tumblr']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Tumblr'] == 'Tumblr' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-tumblr">Tumblr</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Twitter]" value="Twitter" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Twitter']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Twitter'] == 'Twitter' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-twitter">Twitter</span>
							</label>

							<label class="-lr-share-networks-list">
								<input type="checkbox" class="LoginRadius_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Default][Vkontakte]" value="Vkontakte" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Default']['Vkontakte']) && $loginradius_share_settings['vertical_sharing_providers']['Default']['Vkontakte'] == 'Vkontakte' ) ? 'checked' : ''; ?> />
								<span class="lr-text lr-icon-vkontakte">Vkontakte</span>
							</label>
							<div id="loginRadiusVerticalSharingLimit" class="lr-alert-box" style="display:none; margin-bottom: 5px;"><?php _e( 'You can select only eight providers', 'LoginRadius' ) ?>.</div>
							<p class="lr-footnote">*All other icons will be included in the pop-up</p>
						</div>

						<!-- Other than square sharing -->
						<div id="lr_ve_ve_theme_options" style="display:none;">

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Like]" value="Facebook Like" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Like']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Like'] == 'Facebook Like' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Facebook Like</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Twitter Tweet]" value="Twitter Tweet" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Twitter Tweet']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Twitter Tweet'] == 'Twitter Tweet' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Twitter Tweet</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][StumbleUpon Badge]" value="StumbleUpon Badge" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['StumbleUpon Badge']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['StumbleUpon Badge'] == 'StumbleUpon Badge' ) ? 'checked' : ''; ?> />

								<span class="lr-text">StumbleUpon Badge</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Google+ Share]" value="Google+ Share" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ Share']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ Share'] == 'Google+ Share' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Google+ Share</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Recommend]" value="Facebook Recommend" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Recommend']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Recommend'] == 'Facebook Recommend' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Facebook Recommend</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Pinterest Pin it]" value="Pinterest Pin it" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Pinterest Pin it']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Pinterest Pin it'] == 'Pinterest Pin it' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Pinterest Pin it</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Reddit']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Reddit</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Hybridshare]" value="Hybridshare" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Hybridshare']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Hybridshare'] == 'Hybridshare' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Hybridshare</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Send]" value="Facebook Send" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Send']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Send'] == 'Facebook Send' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Facebook Send</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][LinkedIn Share]" value="LinkedIn Share" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['LinkedIn Share']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['LinkedIn Share'] == 'LinkedIn Share' ) ? 'checked' : ''; ?> />

								<span class="lr-text">LinkedIn Share</span>
							</label>

							<label>
								<input type="checkbox" class="LoginRadius_ve_ve_share_providers" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Google+ +1]" value="Google+ +1" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ +1']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ +1'] == 'Google+ +1' ) ? 'checked' : ''; ?> />

								<span class="lr-text">Google +1</span>
							</label>
							<div id="loginRadiusVerticalVerticalSharingLimit" class="lr-alert-box" style="display:none; margin-bottom: 5px;">
								<?php _e( 'You can select only eight providers', 'LoginRadius' ) ?>.
							</div>
							<p class="lr-footnote"></p>
						</div>
					</div>

					<div class="lr-row cf" id="login_radius_vertical_rearrange_container">
						<h3 class="lr-column2">
							<?php _e( 'Select the sharing networks order', 'LoginRadius' ) ?>
							<span class="lr-tooltip" data-title="Drag the icons around to set the order">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						<div class="lr-column2 lr-vr-sortable">
							<ul id="loginRadiusVerticalSortable" class="cf">
								<?php
								if ( isset( $loginradius_share_settings['vertical_rearrange_providers'] ) && count( $loginradius_share_settings['vertical_rearrange_providers'] ) > 0 ) {
									foreach ( $loginradius_share_settings['vertical_rearrange_providers'] as $provider ) {
										?>
										<li title="<?php echo $provider ?>" id="loginRadiusVerticalLI<?php echo $provider ?>" class="lrshare_iconsprite32 lr-icon-<?php echo strtolower( $provider ) ?>">
											<input type="hidden" name="LoginRadius_share_settings[vertical_rearrange_providers][]" value="<?php echo $provider ?>" />
										</li>
										<?php
									}
								}
								?>
							</ul>
							<ul class="lr-static">
								<li title="More" id="loginRadiusHorizontalLImore" class="lr-pin lr-icon-more lrshare_evenmore">
								</li>
							</ul>
						</div>
					</div>

					<div class="lr-row">
						<h3>
							<?php _e( 'Choose the location(s) to display the widget', 'LoginRadius' ) ?>
							<span class="lr-tooltip" data-title="Sharing widget will be displayed at the selected location(s)">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</h3>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-vr-home" name="LoginRadius_share_settings[lr-clicker-vr-home]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-vr-home']) && $loginradius_share_settings['lr-clicker-vr-home'] == '1') ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-vr-home">
								Home Page
								<span class="lr-tooltip" data-title="Home page of your blog">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>

							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-vr-home-options default" name="LoginRadius_share_settings[vertical_position][Home][Top Left]" value="Top Left" <?php echo ( isset($loginradius_share_settings['vertical_position']['Home']['Top Left'] ) && $loginradius_share_settings['vertical_position']['Home']['Top Left'] == 'Top Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-home-options" name="LoginRadius_share_settings[vertical_position][Home][Top Right]" value="Top Right" <?php echo ( isset( $loginradius_share_settings['vertical_position']['Home']['Top Right'] ) && $loginradius_share_settings['vertical_position']['Home']['Top Right'] == 'Top Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Right of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-home-options" name="LoginRadius_share_settings[vertical_position][Home][Bottom Left]" value="Bottom Left" <?php echo ( isset( $loginradius_share_settings['vertical_position']['Home']['Bottom Left'] ) && $loginradius_share_settings['vertical_position']['Home']['Bottom Left'] == 'Bottom Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-home-options" name="LoginRadius_share_settings[vertical_position][Home][Bottom Right]" value="Bottom Right" <?php echo ( isset( $loginradius_share_settings['vertical_position']['Home']['Bottom Right'] ) && $loginradius_share_settings['vertical_position']['Home']['Bottom Right'] == 'Bottom Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Right of the content</span>

								</label>
							</div>
						</div>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-vr-post" name="LoginRadius_share_settings[lr-clicker-vr-post]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-vr-post'] ) && $loginradius_share_settings['lr-clicker-vr-post'] == '1' ) ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-vr-post">
								Blog Posts
								<span class="lr-tooltip" data-title="Each post of your blog">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>

							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-vr-post-options default" name="LoginRadius_share_settings[vertical_position][Posts][Top Left]" value="Top Left" <?php echo ( isset($loginradius_share_settings['vertical_position']['Posts']['Top Left'] ) && $loginradius_share_settings['vertical_position']['Posts']['Top Left'] == 'Top Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-post-options" name="LoginRadius_share_settings[vertical_position][Posts][Top Right]" value="Top Right" <?php echo ( isset($loginradius_share_settings['vertical_position']['Posts']['Top Right'] ) && $loginradius_share_settings['vertical_position']['Posts']['Top Right'] == 'Top Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Right of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-post-options" name="LoginRadius_share_settings[vertical_position][Posts][Bottom Left]" value="Bottom Left" <?php echo ( isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Left'] ) && $loginradius_share_settings['vertical_position']['Posts']['Bottom Left'] == 'Bottom Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-post-options" name="LoginRadius_share_settings[vertical_position][Posts][Bottom Right]" value="Bottom Right" <?php echo ( isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Right'] ) && $loginradius_share_settings['vertical_position']['Posts']['Bottom Right'] == 'Bottom Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Right of the content</span>
								</label>
							</div>
						</div>
						<div>
							<input type="checkbox" class="lr-toggle" id="lr-clicker-vr-static" name="LoginRadius_share_settings[lr-clicker-vr-static]" value="1" <?php echo ( isset($loginradius_share_settings['lr-clicker-vr-static'] ) && $loginradius_share_settings['lr-clicker-vr-static'] == '1' ) ? 'checked' : ''; ?> />
							<label class="lr-show-toggle" for="lr-clicker-vr-static">
								Static Pages
								<span class="lr-tooltip" data-title="Static pages of your blog (e.g &ndash; about, contact, etc.)">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</label>
							<div class="lr-show-options">
								<label>
									<input type="checkbox" class="lr-clicker-vr-static-options" name="LoginRadius_share_settings[vertical_position][Pages][Top Left]" value="Top Left" <?php echo ( isset($loginradius_share_settings['vertical_position']['Pages']['Top Left'] ) && $loginradius_share_settings['vertical_position']['Pages']['Top Left'] == 'Top Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-static-options default" name="LoginRadius_share_settings[vertical_position][Pages][Top Right]" value="Top Right" <?php echo ( isset($loginradius_share_settings['vertical_position']['Pages']['Top Right'] ) && $loginradius_share_settings['vertical_position']['Pages']['Top Right'] == 'Top Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Top-Right of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-static-options" name="LoginRadius_share_settings[vertical_position][Pages][Bottom Left]" value="Bottom Left" <?php echo ( isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Left'] ) && $loginradius_share_settings['vertical_position']['Pages']['Bottom Left'] == 'Bottom Left' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Left of the content</span>

								</label>
								<label>
									<input type="checkbox" class="lr-clicker-vr-static-options" name="LoginRadius_share_settings[vertical_position][Pages][Bottom Right]" value="Bottom Right" <?php echo ( isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Right'] ) && $loginradius_share_settings['vertical_position']['Pages']['Bottom Right'] == 'Bottom Right' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Bottom-Right of the content</span>
								</label>
							</div><!-- show options -->
						</div><!-- unnamed div -->
					</div>
				</div><!-- Container -->
				<?php submit_button('Save changes'); ?>
			</div><!-- End Vertical Sharing -->

			<?php
		}
	}
}