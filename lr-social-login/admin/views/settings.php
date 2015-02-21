<?php
/**
 * @file
 * The Admin Panel and related tasks are handled in this file.
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the plugin settings page.
 */
if ( !class_exists( 'LR_Social_Login_Admin_Settings' ) ) {

	class LR_Social_Login_Admin_Settings {
		
		/**
		 * Render settings page
		 */
		public static function render_options_page() {
			global $loginRadiusSettings, $loginradius_api_settings, $loginRadiusLoginIsBpActive;
			
			if( isset($_POST['reset']) ){
				LR_Social_Login_Install::reset_loginradius_login_options();
				echo '<p style="display:none;" class="lr-alert-box lr-notif">Login settings have been reset and default values loaded</p>';
				echo '<script type="text/javascript">jQuery(function(){jQuery(".lr-notif").slideDown().delay(3000).slideUp();});</script>';
			}

			$loginRadiusSettings = get_option( 'LoginRadius_settings' );
			
			$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );

			if ( !isset( $loginradius_api_settings['LoginRadius_apikey'] ) || !isset( $loginradius_api_settings['LoginRadius_secret'] ) || trim( $loginradius_api_settings['LoginRadius_apikey'] ) == '' || trim( $loginradius_api_settings['LoginRadius_secret'] ) == '' ) {
				Admin_Helper:: display_notice_to_insert_api_and_secret();
			}

			?>
			<div class="wrap lr-wrap cf">
				<header>
					<h2 class="logo"><a href="//loginradius.com" target="_blank">LoginRadius</a><em>Social Login</em></h2>
				</header>

				<div id="lr_options_tabs" class="cf">
						<div class="cf">
								<ul class="lr-options-tab-btns">
									<li class="nav-tab lr-active" data-tab="lr_options_tab-1"><?php _e( 'Social Login', 'LoginRadius' ) ?></li>
									<?php if ( ! class_exists( 'LR_Disqus' ) && ! class_exists( 'LR_Commenting' ) ) { ?>
										<li class="nav-tab" data-tab="lr_options_tab-2"><?php _e( 'Social Commenting', 'LoginRadius' ) ?></li>
									<?php } ?>
									<li class="nav-tab" data-tab="lr_options_tab-3"><?php _e( 'Customization Settings', 'LoginRadius' ) ?></li>
									<li class="nav-tab" data-tab="lr_options_tab-4"><?php _e( 'Advanced Settings', 'LoginRadius' ) ?></li>
								</ul>
							<form action="options.php" method="post">
								<?php
									settings_fields( 'LoginRadius_setting_options' );
									settings_errors();

									include 'social-login/social-login-view.php';
									lr_render_social_login_options();

									if ( ! class_exists( 'LR_Disqus' ) && ! class_exists( 'LR_Commenting' ) ) {
										include 'social-commenting/social-commenting-view.php';
										lr_render_social_commenting_options( $loginRadiusSettings );
									}

									include 'customization-settings/customization-view.php';
									lr_render_social_customization_options( $loginRadiusSettings );
								?>

								<div id="lr_options_tab-4" class="lr-tab-frame">
									<div class="lr_options_container">
										<div class="lr-row">
											<h3>
												Short Code for Social Login
												<span class="lr-tooltip tip-bottom" data-title="Copy and paste the following shortcode into a page or post to display a social login interface">
													<span class="dashicons dashicons-editor-help"></span>
												</span>
											</h3>
											<div>
												<textarea rows="1" onclick="this.select()" spellcheck="false" class="lr-shortcode" readonly="readonly">[LoginRadius_Login]</textarea>
											</div>
											<span>Additional shortcode examples can be found <a target="_blank" href='http://ish.re/BENH/#shortcode' >Here</a></span>
										</div><!-- lr-row -->
									</div>
									<!-- Social Login Email Settings -->
									<div class="lr_options_container">
										<div class="lr-row">
											<h3><?php _e( 'Social Login Email Settings', 'LoginRadius' ); ?></h3>
											<div>
												<h4>
													<?php _e( 'A few Social Networks do not supply user email address as part of user profile data. Do you want users to provide their email before completing the registration process?', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Turn on, if you would like to prompt these users for their email address in a separate pop-up">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<div>
													<input type="checkbox" class="lr-toggle" id="lr-clicker-get-email" name="LoginRadius_settings[LoginRadius_dummyemail]" value="notdummyemail" <?php echo ( isset( $loginRadiusSettings['LoginRadius_dummyemail'] ) && $loginRadiusSettings['LoginRadius_dummyemail'] == 'notdummyemail') ? 'checked="checked"' : ''; ?> />
													<label class="lr-show-toggle" for="lr-clicker-get-email">
													</label>
												</div>
											</div>
											<div class="lr-get-email-messages">
												<h4>
													<?php _e( 'Please enter the title of the pop-up asking users to enter their email address', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="The name of the social provider will be automatically filled in if you use @provider">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<textarea name="LoginRadius_settings[msg_email]" cols="100" rows="3" ><?php echo $loginRadiusSettings['msg_email']; ?></textarea>
												</label>
											</div>
											<div class="lr-get-email-messages">
												<h4>
													<?php _e( 'Please enter the message to be shown to the user if the email address is already registered', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="This is the message that will be displayed to the user if the email address they are registering with is already taken">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<textarea name="LoginRadius_settings[msg_existemail]" cols="100" rows="3"><?php echo $loginRadiusSettings['msg_existemail']; ?></textarea>
												</label>
											</div>
										</div>
									</div>

									<!-- Social Login User Settings -->
									<div class="lr_options_container">
										<div class="lr-row">
											<h3><?php _e( 'Social Login User Settings', 'LoginRadius' ); ?></h3>
											<div>
												<h4>
													<?php _e( 'Select how you would like the WordPress username to be generated', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="During account creation, it automatically adds a separator between the user's first name and last name">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo !isset( $loginRadiusSettings['username_separator'] ) ? 'checked="checked"' : Admin_Helper:: is_radio_checked( 'seperator', 'dash' ); ?> value="dash" />
													<span><?php _e( 'Dash: Firstname-Lastname [Ex: John-Doe]', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'seperator', 'dot' ); ?> value="dot"/>
													<span><?php _e( 'Dot: Firstname.Lastname [Ex: John.Doe]', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input name="LoginRadius_settings[username_separator]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'seperator', 'space' ); ?> value='space'/>
													<span><?php _e( 'Space: Firstname Lastname [Ex: John Doe]', 'LoginRadius' ); ?></span>
												</label>
											</div>
											<div>
												<h4>
													<?php _e( 'Select whether you would like to control account activation and deactivation', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="You can enable/disable the user from the Status column on the Users page in WordPress admin screens">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input type="radio" id="controlActivationYes" name="LoginRadius_settings[LoginRadius_enableUserActivation]" value='1' <?php echo ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == 1 ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Yes, display activate/deactivate option in the ', 'LoginRadius' ) ?> <a href="<?php echo get_admin_url() ?>users.php" target="_blank" ><?php _e( 'User list', 'LoginRadius' ); ?></a></span>
												</label>
												<label>
													<input type="radio" id="controlActivationNo" name="LoginRadius_settings[LoginRadius_enableUserActivation]" value="0" <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) && $loginRadiusSettings['LoginRadius_enableUserActivation'] == 0 ) ) || !isset( $loginRadiusSettings['LoginRadius_enableUserActivation'] ) ? 'checked' : ''; ?> /> 
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
												<div id="loginRadiusDefaultStatus" class="lr-row">
													<h5>
														<?php _e( 'Select the default status of the user when he/she registers on your website', 'LoginRadius' ); ?>
														<span class="lr-tooltip" data-title="Select whether you would like the user to be set to an active or inactive user after the initial registration process">
															<span class="dashicons dashicons-editor-help"></span>
														</span>
													</h5>
													<label>
														<input type="radio" name="LoginRadius_settings[LoginRadius_defaultUserStatus]" value='1' <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == 1 ) ) || !isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) ? 'checked' : ''; ?> />
														<span><?php _e( 'Active', 'LoginRadius' ); ?></span>
													</label>
													<label>
														<input type="radio" name="LoginRadius_settings[LoginRadius_defaultUserStatus]" value="0" <?php echo ( isset( $loginRadiusSettings['LoginRadius_defaultUserStatus'] ) && $loginRadiusSettings['LoginRadius_defaultUserStatus'] == 0 ) ? 'checked' : ''; ?>/>
														<span><?php _e( 'Inactive', 'LoginRadius' ); ?></span>
													</label>
												</div>
											</div>
											
											<div>
												<h4>
													<?php _e( 'Select whether to display the social network(s) the user is connected with in the user list', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Select Yes, if you want to see the list of social providers the user account is linked with (in the user list)">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input type="radio" name="LoginRadius_settings[LoginRadius_noProvider]" value="1" <?php echo ( $loginRadiusSettings['LoginRadius_noProvider'] == 1 ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Yes, display the social network(s) that the user connected with (in the user list)', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input type="radio" name="LoginRadius_settings[LoginRadius_noProvider]" value='0' <?php echo ( $loginRadiusSettings['LoginRadius_noProvider'] == 0 ) ? 'checked' : ''; ?> />
													<span><?php _e( 'No, do not display the social network(s) that the user connected with', 'LoginRadius' ); ?></span>
												</label>
											</div>

											<div>
												<h4>
													<?php _e( 'Select whether the user profile data should be updated in your WordPress database, every time a user logs in', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="If you disable this option, the user profile data will be saved only once when the user logs in for the first time on your website, and this data will not be updated again in your WordPress database, even if the user updates their social account.">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input type="radio" name="LoginRadius_settings[profileDataUpdate]" value='1' <?php echo ( !isset( $loginRadiusSettings['profileDataUpdate'] ) || $loginRadiusSettings['profileDataUpdate'] == 1 ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Yes', 'LoginRadius' ) ?></span>
												</label>
												<label>
													<input type="radio" name="LoginRadius_settings[profileDataUpdate]" value="0" <?php echo ( isset( $loginRadiusSettings['profileDataUpdate'] ) && $loginRadiusSettings['profileDataUpdate'] == 0 ) ? 'checked' : ''; ?> />
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
											</div>

											<div>
												<h4>
													<?php _e( 'Select whether to let users use their social profile picture as an avatar on your website', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Select Yes, if you want to let users use their profile picture from their linked social account as an avatar on your website">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input name ="LoginRadius_settings[LoginRadius_socialavatar]" type="radio"  <?php echo Admin_Helper:: is_radio_checked( 'avatar', 'socialavatar' ); ?> value="socialavatar" />
													<span><?php _e( 'Yes', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input name ="LoginRadius_settings[LoginRadius_socialavatar]" type="radio" <?php echo Admin_Helper:: is_radio_checked( 'avatar', 'defaultavatar' ); ?> value="defaultavatar" />
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
											</div>

											<div>
												<h4>
													<?php _e( "Enable account linking", 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Select Yes, If you want to enable social account linking. This option will also shows users' the linking interface on the wordpress dashboard that allows users to link their other social providers">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input type="radio" name="LoginRadius_settings[LoginRadius_socialLinking]" value='1' <?php echo ( ( isset( $loginRadiusSettings['LoginRadius_socialLinking'] ) && $loginRadiusSettings['LoginRadius_socialLinking'] == 1 ) || !isset( $loginRadiusSettings['LoginRadius_socialLinking'] ) ) ? 'checked' : ''; ?> />
													<span><?php _e( 'Yes', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input type="radio" name="LoginRadius_settings[LoginRadius_socialLinking]" value="0" <?php checked( '0', @$loginRadiusSettings['LoginRadius_socialLinking'] ); ?> />
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
											</div>
											<div>
												<h4>
													<?php _e( 'Send email to user with their username and password after registration', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Choose Yes, if you want the user to receive an email notification about their WordPress username and password after registration">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input name="LoginRadius_settings[LoginRadius_sendemail]" type="radio"  value="sendemail" <?php echo Admin_Helper:: is_radio_checked( 'send_email', 'sendemail' ); ?> />
													<span><?php _e( 'Yes', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input name="LoginRadius_settings[LoginRadius_sendemail]" type="radio" value="notsendemail" <?php echo Admin_Helper:: is_radio_checked( 'send_email', 'notsendemail' ); ?> />
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
											</div>
										</div><!-- lr-row -->
									</div>

									<!-- Plugin Debug option. -->
									<div class="lr_options_container">
										<div class="lr-row">
											<h3><?php _e( 'Debug', 'LoginRadius' ); ?></h3>
											<div>
												<h4>
													<?php _e( 'Do you want to enable LoginRadius error reporting?', 'LoginRadius' ); ?>
													<span class="lr-tooltip" data-title="Select Yes, if you want to Social Login errors reported">
														<span class="dashicons dashicons-editor-help"></span>
													</span>
												</h4>
												<label>
													<input name="LoginRadius_settings[enable_degugging]" type="radio"  value="1" <?php echo ( isset( $loginRadiusSettings['enable_degugging'] ) && $loginRadiusSettings['enable_degugging'] == '1' ) ? 'checked = "checked"' : ''; ?> />
													<span><?php _e( 'Yes', 'LoginRadius' ); ?></span>
												</label>
												<label>
													<input name="LoginRadius_settings[enable_degugging]" type="radio" value="0" <?php echo ( !isset( $loginRadiusSettings['enable_degugging'] ) || $loginRadiusSettings['enable_degugging'] == '0' ) ? 'checked="checked"' : ''; ?> />
													<span><?php _e( 'No', 'LoginRadius' ); ?></span>
												</label>
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
							</form>

									<div class="lr_options_container">
										<div class="lr-row">
											<h5>
												Reset all the login options to the recommended settings
												<span class="lr-tooltip" data-title="This option will reset all the settings to the default plugin settings">
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
								</div><!-- Tab-4 Content -->
						</div><!-- Unnamed Tabs Content -->
				</div><!-- LR Options Tabs -->
				<?php
				include 'help/help-view.php';
				login_radius_render_help_options(); ?>
			</div><!-- lr-wrap -->
			<?php
		}

	}

}


