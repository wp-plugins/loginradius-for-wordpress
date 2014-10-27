<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * Verify Api Key From Database.
 */
function loginradius_share_verify_apikey() {
	global $loginradius_api_settings;
	// Get LoginRadius plugin settings.
	$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
	if (preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $loginradius_api_settings['LoginRadius_apikey'] )) {
		return true;
	} else {
		return false;
	}
}

/**
 * Save LoginRadius API key in the database
 */
function loginradius_share_save_apikey() {
	if ( isset( $_POST['apikey'] ) && trim( $_POST['apikey'] ) != '' ) {
		$options = get_option( 'LoginRadius_API_settings' );
		$options['LoginRadius_apikey'] = trim( $_POST['apikey'] );
		if(update_option( 'LoginRadius_API_settings', $options ) ){
			die('success');
		}
	}
	die('error');
}
add_action( 'wp_ajax_lr_save_apikey', 'loginradius_share_save_apikey' );

function loginradius_share_select_admin() {
	global $loginradius_api_settings, $loginradius_share_settings;
	if( isset( $_POST['reset'] ) ){
		reset_loginradius_share_options();
		echo '<p style="display:none;" class="lr-alert-box lr-notif">Sharing settings have been reset and default values loaded</p>';
		echo '<script type="text/javascript">jQuery(function(){jQuery(".lr-notif").slideDown().delay(3000).slideUp();});</script>';
	}

	// Get LoginRadius plugin settings.
	$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
	$loginradius_share_settings = get_option( 'LoginRadius_share_settings' );

	if( !isset($loginradius_api_settings['LoginRadius_apikey']) || empty( $loginradius_api_settings['LoginRadius_apikey'] ) || loginradius_share_verify_apikey() == false){
		if( has_action( 'admin_menu', 'create_loginradius_menu' ) ) {
			loginradius_share_admin_options();
		}else{
			lr_render_aia();
		}
	}else{
		loginradius_share_admin_options();
	}
}

/**
 * Admin Options.
 */
function loginradius_share_admin_options() {
	global $loginradius_share_settings, $loginradius_api_settings;
	?>
	<!-- LR-wrap -->
	<div class="wrap lr-wrap cf">
		<header>
			<h2 class="logo"><a href="//loginradius.com" target="_blank">LoginRadius</a><em>Simplified Social Share</em></h2>
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
								<label class="lr_horizontal_interface_img" for="lr-horizontal-lrg"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/32-h.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-horizontal-sml" name="LoginRadius_share_settings[horizontal_share_interface]" value="16-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == '16-h' ) ? 'checked' : ''; ?> />

								<label class="lr_horizontal_interface_img" for="lr-horizontal-sml"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/16-h.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-single-lg-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="single-lg-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'single-lg-h' ) ? 'checked' : ''; ?> />

								<label class="lr_horizontal_interface_img" for="lr-single-lg-h"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/single-lg-h.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-single-sm-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="single-sm-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'single-sm-h' ) ? 'checked' : ''; ?> />

								<label class="lr_horizontal_interface_img" for="lr-single-sm-h"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/single-sm-h.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-sharing/hybrid-h-h" name="LoginRadius_share_settings[horizontal_share_interface]" value="hybrid-h-h" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'hybrid-h-h' ) ? 'checked' : ''; ?> />

								<label class="lr_horizontal_interface_img" for="lr-sharing/hybrid-h-h"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-h-h.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-hybrid-h-v" name="LoginRadius_share_settings[horizontal_share_interface]" value="hybrid-h-v" <?php echo ( $loginradius_share_settings['horizontal_share_interface'] == 'hybrid-h-v' ) ? 'checked' : ''; ?> />

								<label class="lr_horizontal_interface_img" for="lr-hybrid-h-v"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-h-v.png" ?>" /></label>
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
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Like]" value="Facebook Like" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Like']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Like'] == 'Facebook Like' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Facebook Like</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Twitter Tweet]" value="Twitter Tweet" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Twitter Tweet']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Twitter Tweet'] == 'Twitter Tweet' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Twitter Tweet</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][StumbleUpon Badge]" value="StumbleUpon Badge" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['StumbleUpon Badge']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['StumbleUpon Badge'] == 'StumbleUpon Badge' ) ? 'checked' : ''; ?> />
									<span class="lr-text">StumbleUpon Badge</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Google+ Share]" value="Google+ Share" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ Share']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ Share'] == 'Google+ Share' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Google+ Share</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Recommend]" value="Facebook Recommend" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Recommend']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Recommend'] == 'Facebook Recommend' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Facebook Recommend</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Pinterest Pin it]" value="Pinterest Pin it" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Pinterest Pin it']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Pinterest Pin it'] == 'Pinterest Pin it' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Pinterest Pin it</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Reddit']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Reddit</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Hybridshare]" value="Hybridshare" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Hybridshare']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Hybridshare'] == 'Hybridshare' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Hybridshare</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Facebook Send]" value="Facebook Send" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Send']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Facebook Send'] == 'Facebook Send' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Facebook Send</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][LinkedIn Share]" value="LinkedIn Share" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['LinkedIn Share']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['LinkedIn Share'] == 'LinkedIn Share' ) ? 'checked' : ''; ?> />
									<span class="lr-text">LinkedIn Share</span>
								</label>

								<label class="lr-sharing-cb">
									<input type="checkbox" name="LoginRadius_share_settings[horizontal_sharing_providers][Hybrid][Google+ +1]" value="Google+ +1" <?php echo ( isset($loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ +1']) && $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']['Google+ +1'] == 'Google+ +1' ) ? 'checked' : ''; ?> />
									<span class="lr-text">Google+ +1</span>
								</label>
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
				</div><!-- option tab 1 -->

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

								<label class="lr_vertical_interface_img" for="lr-vertical-32-v"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/32-v.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-vertical-16-v" name="LoginRadius_share_settings[vertical_share_interface]" value="16-v" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == '16-v' ) ? 'checked' : ''; ?> />

								<label class="lr_vertical_interface_img" for="lr-vertical-16-v"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/16-v.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-vertical-hybrid-v-v" name="LoginRadius_share_settings[vertical_share_interface]" value="hybrid-v-v" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == 'hybrid-v-v' ) ? 'checked' : ''; ?> />

								<label class="lr_vertical_interface_img" for="lr-vertical-hybrid-v-v"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-v-v.png" ?>" /></label>
							</div>
							<div>
								<input type="radio" id="lr-vertical-hybrid-v-h" name="LoginRadius_share_settings[vertical_share_interface]" value="hybrid-v-h" <?php echo ( $loginradius_share_settings['vertical_share_interface'] == 'hybrid-v-h' ) ? 'checked' : ''; ?> />

								<label class="lr_vertical_interface_img" for="lr-vertical-hybrid-v-h"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . "/assets/images/sharing/hybrid-v-h.png" ?>" /></label>
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
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Like]" value="Facebook Like" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Like']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Like'] == 'Facebook Like' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Facebook Like</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Twitter Tweet]" value="Twitter Tweet" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Twitter Tweet']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Twitter Tweet'] == 'Twitter Tweet' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Twitter Tweet</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][StumbleUpon Badge]" value="StumbleUpon Badge" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['StumbleUpon Badge']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['StumbleUpon Badge'] == 'StumbleUpon Badge' ) ? 'checked' : ''; ?> />

									<span class="lr-text">StumbleUpon Badge</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Google+ Share]" value="Google+ Share" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ Share']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ Share'] == 'Google+ Share' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Google+ Share</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Recommend]" value="Facebook Recommend" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Recommend']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Recommend'] == 'Facebook Recommend' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Facebook Recommend</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Pinterest Pin it]" value="Pinterest Pin it" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Pinterest Pin it']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Pinterest Pin it'] == 'Pinterest Pin it' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Pinterest Pin it</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Reddit]" value="Reddit" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Reddit']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Reddit'] == 'Reddit' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Reddit</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Hybridshare]" value="Hybridshare" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Hybridshare']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Hybridshare'] == 'Hybridshare' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Hybridshare</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Facebook Send]" value="Facebook Send" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Send']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Facebook Send'] == 'Facebook Send' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Facebook Send</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][LinkedIn Share]" value="LinkedIn Share" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['LinkedIn Share']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['LinkedIn Share'] == 'LinkedIn Share' ) ? 'checked' : ''; ?> />

									<span class="lr-text">LinkedIn Share</span>
								</label>

								<label>
									<input type="checkbox" name="LoginRadius_share_settings[vertical_sharing_providers][Hybrid][Google+ +1]" value="Google+ +1" <?php echo ( isset($loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ +1']) && $loginradius_share_settings['vertical_sharing_providers']['Hybrid']['Google+ +1'] == 'Google+ +1' ) ? 'checked' : ''; ?> />

									<span class="lr-text">Google +1</span>
								</label>
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
				</div><!-- Vertical Sharing -->
			</form>

			<!-- Settings -->
			
			<!-- Advanced Options -->
			<div id="lr_options_tab-3" class="lr-tab-frame">
					<form method="post" action="options.php">
					<?php settings_fields( 'loginradius_api_settings' ); ?>
				<div id="lr_api_options" class="lr_options_container">
						<?php if( ! function_exists('loginradius') ){ ?>
						<div class="lr-row">
							<h3>
								Your loginradius sharing API key
								<span class="lr-tooltip tip-bottom" data-title="This is your unique API key for the LoginRadius sharing plugin">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</h3>
							<input type="text" readonly="readonly" name="LoginRadius_API_settings[LoginRadius_apikey]" value="<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>" onclick="this.select()"/>
						</div>
						<?php } ?>
						<div class="lr-row">
							<h3>
								Short Code for Sharing widget
								<span class="lr-tooltip tip-bottom" data-title="Copy and paste the following shortcode into a page or post to display a horizontal sharing widget">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</h3>
							<div>
								<textarea rows="1" onclick="this.select()" spellcheck="false" class="lr-shortcode">[LoginRadius_Share]</textarea>
							</div>
							<span>Additional shortcode examples can be found <a target="_blank" href='http://ish.re/9WBX' >Here</a></span>
						</div><!-- lr-frame -->

						<div class="lr-row" id="lr_js_options">
							<label class="lr-toggle">
								<input type="checkbox" class="lr-toggle" name="LoginRadius_API_settings[scripts_in_footer]" value="1" <?php echo ( isset($loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == '1' ) ? 'checked' : ''; ?> />
								<span class="lr-toggle-name">
									For faster loading, do you want to load sharing javascript in the footer?
									<span class="lr-tooltip" data-title="The JavaScript will load in the footer by default, please disable it if your theme doesn't support footers or has issues with the sharing interface">
										<span class="dashicons dashicons-editor-help"></span>
									</span>
								</span>
							</label>
						</div><!-- lr-frame -->
						<div class="lr-row lr-advance">
							<?php submit_button( 'Save changes' ); ?>
						</div>
					</form>
						<div class="lr-row">
							<h3>
								Reset all the sharing options to the recommended settings
								<span class="lr-tooltip" data-title="This option will reset all the settings to the default sharing plugin settings">
									<span class="dashicons dashicons-editor-help"></span>
								</span>
							</h3>
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
						<a target="_blank" href="http://ish.re/9WBX">
							<?php _e( 'Plugin Installation, Configuration and Troubleshooting', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/BENQ">
							<?php _e( 'How to get LoginRadius API Key', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/8PG2">
							<?php _e( 'Community Forum', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/8PJ7">
							<?php _e( 'About LoginRadius', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/5P2D">
							<?php _e( 'LoginRadius Products', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/C8E7">
							<?php _e( 'CMS Plugins', 'LoginRadius' ) ?></a>
						<a target="_blank" href="http://ish.re/C9F7">
							<?php _e( 'API Libraries', 'LoginRadius' ) ?></a>
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
	</div>
<?php }

/**
 * Adding script for login/registration from LoginRadius plugin setting page.
 */
function lr_render_aia() {
	?>
	<script>
		// Hooks on start and end of process.
		LoginRadiusAIA.$hooks.setProcessHook(function() {
			jQuery('.loginradius-aia-submit').next('#loginradius_error_msg').remove();
			jQuery('.loginradius-aia-submit').after('<div id = "loginradius_error_msg" class="lr-proccess"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" /><span>Please wait. This may take few seconds...</span></div>');
		}, function() {
		});
		var siteSaveAjaxOptions ={
				type: 'POST',
				url: '<?php echo get_admin_url() ?>admin-ajax.php',

				success: function(data) {
					if ( data.trim() == 'error') {
						jQuery('#loginradius_error_msg').html('');
						jQuery('#loginRadiusMessage').html('Unexpected error occurred, Please refresh the page and try again..').css('color', 'red');
						return;
					} else {
						// Options updated successfully, refresh location.
						location.reload();
						return;
					}
				},
				error: function() {
					jQuery('#loginradius_error_msg').html('');
						jQuery('#loginRadiusMessage').html('Unexpected error occurred, Please refresh the page and try again..').css('color', 'red');
						return;
				}
			};
		// Save selected LR Site API Key.
		function loginRadiusSaveLRSite() {
			// If Site selection is empty.
			if ( jQuery('#lrSites').val().trim() == '') {
				jQuery('#loginRadiusMessage').html('Please select a site');
				return;
			}
			// Processing message.
			jQuery('#loginRadiusLRSiteSave').html('');
			jQuery('#loginradius_error_msg').html('<div id = "loginradius_error_msg" class="lr-proccess"><img src="<?php echo LOGINRADIUS_SHARE_PLUGIN_URL . 'assets/images/loading_icon.gif'; ?>" /><span>Please wait. This may take few seconds...</span></div>');
			siteSaveAjaxOptions.data = {
					action: 'lr_save_apikey',
					apikey: jQuery('#lrSites').val().trim()
				};
			jQuery.ajax(siteSaveAjaxOptions);
		}

		function loginRadiusNotification(message) {
			if( message == "LoginRadius account with this email already exists" ){
				document.getElementById('loginRadiusLoginForm').style.display = 'block';
				document.getElementById('loginRadiusRegisterForm').style.display = 'none';
				message = 'Sharing widget is already activated for your email. Please login below or use <a style="margin-top: 5px; " target="_blank" href="https://secure.loginradius.com/login/forgotten">Forgot your password?</a>';
				document.getElementById('loginRadiusFormTitle').innerHTML = 'Login to your account';
			}
			jQuery('#loginRadiusMessage').remove();
			jQuery('#loginRadiusFormTitle').after("<div id='loginRadiusMessage' class='lr-alert-box'>" + message + "</div>");

			if (jQuery('#loginRadiusMessage').is(':empty')) {
				jQuery(this).hide();
			} else {
				jQuery(this).show();
			};
		}

		$SL.util.ready(function() {
			<?php global $current_user; get_currentuserinfo(); ?>
			var aiaOptions = {};
			aiaOptions.inFormvalidationMessage = true;

			aiaOptions.website = '<?php echo site_url(); ?>';
			aiaOptions.siteName = '<?php echo get_bloginfo( "name" ); ?>';
			aiaOptions.WebTechnology = 'wordpress';
			aiaOptions.Emailid = '<?php echo $current_user->user_email ?>';

			// Registering LoginRadius Account through plugin admin.
			LoginRadiusAIA.init(aiaOptions, 'registration', function(response) {

				jQuery('.loginradius-aia-submit').next('div').remove();
				if ( response[0].apikey ) {
					console.log(responce);
					// If valid API Key is returned, Make ajax request for saving API Key.
					siteSaveAjaxOptions.data =  {
							action: 'lr_save_apikey',
							apikey: response[0].apikey,
						}

					jQuery.ajax( siteSaveAjaxOptions );
				}
			}, function( errors ) {
				if ( errors[0].description != null ) {
					loginRadiusNotification(errors[0].description);
				}
				jQuery('.loginradius-aia-submit').next('div').remove();
			}, "loginRadiusRegisterForm");

			// Logging into LoginRadius Account through plugin admin.
			LoginRadiusAIA.init(aiaOptions, 'login', function(response) {
				jQuery('.loginradius-aia-submit').next('div').remove();
				if ( response == '' ) {
					loginRadiusNotification('You have not created any Site on www.loginradius.com. Please Login to create a Site');
					return;
				}
				jQuery('#loginRadiusMessage').html('');
				jQuery('#login-registration-links').remove();

				if ( response[0].apikey ) {
					// Display the LoginRadius Site list.
					jQuery('#loginRadiusFormTitle').html('Site Selection');
					var html = '<div id="loginRadiusMessage"></div><table class="form-table"><tbody><tr><th><label for="lrSites"><?php _e( 'Select a LoginRadius site', 'LoginRadius' ) ?></label></th><td><select id="lrSites"><option value="">--Select a Site--</option>';
					for (var i = 0; i < response.length; i++) {
						html += '<option value="' + response[i].apikey + '">' + response[i].appName + '</option>';
					}
					html += '</select>';
					html += '</td></tr><tr><td colspan="2"><input type="submit" id="loginRadiusLRSiteSave" class="button button-primary" value="<?php _e( 'Save', 'LoginRadius' ) ?>" /></td></tr><tr><td colspan="2"><div id = "loginradius_error_msg" class="lr-proccess"></div></td></tr>';
					jQuery('#loginRadiusLoginForm').html(html);
					document.getElementById('loginRadiusLRSiteSave').onclick = function() {
						loginRadiusSaveLRSite();
					};
				} else {
					loginRadiusNotification('You have not created any Site on www.loginradius.com. Please Login to create a Site');
				}
			}, function(errors) {
				if ( errors[0].description != null ) {
					loginRadiusNotification( errors[0].description );
				}
				jQuery('.loginradius-aia-submit').next('div').remove();
			}, "loginRadiusLoginForm");
		});
	</script>
	<div class="wrap lr-wrap lr-login">
		<header>
			<h2 class="logo"><a href="//www.loginradius.com" target="_blank">LoginRadius</a></h2>
			<em>Simplified Social Share</em>
		</header>
		<div class="lr-container">
			<h3 id="loginRadiusFormTitle" style="text-align:center;">
				<?php _e( 'Activate Sharing Plugin', 'LoginRadius' ) ?>
			</h3>
			<div id="loginRadiusLoginForm">
			</div>
			<div id="loginRadiusRegisterForm">
			</div>
			<div class = "clr"></div>
		</div><!-- container -->

	</div><!-- lr-wrap -->
	<?php
}