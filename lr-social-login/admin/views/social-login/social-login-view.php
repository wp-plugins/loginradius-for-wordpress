<?php

/**
 * Function for rendering Social Login tab on plugin on settings page
 */
function lr_render_social_login_options() {
	global $loginRadiusSettings, $loginRadiusLoginIsBpActive;
	?>
	<div id="lr_options_tab-1" class="lr-tab-frame lr-active">
		<!-- Social Login Interface Display Settings -->
		<div class="lr_options_container">
			<div class="lr-row">
				<h3>
					<?php _e( 'Interface Display Settings', 'LoginRadius' ); ?>
				</h3>
				<div>
					<input type="checkbox" class="lr-toggle" id="lr-clicker-login-form" name="LoginRadius_settings[LoginRadius_loginform]" value="1" <?php echo isset( $loginRadiusSettings['LoginRadius_loginform'] ) && $loginRadiusSettings['LoginRadius_loginform'] == '1' ? 'checked' : ''; ?> />
					<label class="lr-show-toggle" for="lr-clicker-login-form">
						Login page of your WordPress site
						<span class="lr-tooltip" data-title="Default login page provided by WordPress">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
						<?php
							if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
								?>
								<div class="lr-reg-form-options">
									<label>
										<input type="radio" name="LoginRadius_settings[LoginRadius_loginformPosition]" value="embed" <?php echo ( isset($loginRadiusSettings['LoginRadius_loginformPosition']) && $loginRadiusSettings['LoginRadius_loginformPosition'] == 'embed') ? 'checked = "checked"' : ''; ?> />
										<span><?php _e( 'Display the Social Login interface below the Wordpress login form', 'LoginRadius' ); ?></span>
									</label>
									<label>
										<input type="radio" name="LoginRadius_settings[LoginRadius_loginformPosition]" value="beside" <?php echo ( isset($loginRadiusSettings['LoginRadius_loginformPosition']) && $loginRadiusSettings['LoginRadius_loginformPosition'] == 'beside') ? 'checked = "checked"' : ''; ?> />
										<span><?php _e( 'Display the Social Login interface beside the Buddypress login form', 'LoginRadius' ); ?></span>
									</label>
								</div>
								<?php
							}
						?>
					</label>
				</div>
				<div>
					<input type="checkbox" class="lr-toggle" id="lr-clicker-reg-form" name="LoginRadius_settings[LoginRadius_regform]" value="1" <?php echo isset( $loginRadiusSettings['LoginRadius_regform'] ) && $loginRadiusSettings['LoginRadius_regform'] == 1 ? 'checked' : ''; ?> />
					<label class="lr-show-toggle" for="lr-clicker-reg-form">
						Registration page of your WordPress site
						<span class="lr-tooltip" data-title="Default registration page provided by WordPress">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
						<?php
							if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
								?>
								<div class="lr-reg-form-options">
									<label>
										<input type="radio" name="LoginRadius_settings[LoginRadius_regformPosition]" value="embed" <?php echo ( isset($loginRadiusSettings['LoginRadius_regformPosition']) && $loginRadiusSettings['LoginRadius_regformPosition'] == 'embed') ? 'checked = "checked"' : ''; ?> />
										<span><?php _e( 'Display the Social Login interface below the Wordpress registration form', 'LoginRadius' ); ?></span>
									</label>
									<label>
										<input type="radio" name="LoginRadius_settings[LoginRadius_regformPosition]" value="beside" <?php echo ( isset($loginRadiusSettings['LoginRadius_regformPosition']) && $loginRadiusSettings['LoginRadius_regformPosition'] == 'beside') ? 'checked = "checked"' : ''; ?> />
										<span><?php _e( 'Display the Social Login interface above the Buddypress registration form', 'LoginRadius' ); ?></span>
									</label>
								</div>
								<?php
							}
						?>
					</label>
				</div>
			</div>
		</div>

		<div class="lr_options_container">
			<div class="lr-row">
				<h3><?php _e( 'Redirection Settings', 'LoginRadius' ); ?></h3>
				<div>
					<h4>
						<?php _e( 'Redirection settings after login ', 'LoginRadius' ); ?>
						<span class="lr-tooltip" data-title="Page the user is redirected to after login">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
					</h4>
					<label>
						<input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="samepage" <?php echo Admin_Helper:: is_radio_checked( 'login', 'samepage' ); ?> /> 
						<span><?php _e( 'Redirect to the same page where the user logged in', 'LoginRadius' ); ?></span>
					</label>
					<label>
						<input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'login', 'homepage' ); ?> /> 
						<span><?php _e( 'Redirect to the home page of your WordPress site', 'LoginRadius' ); ?></span>
					</label>
					<label>
						<input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="dashboard" <?php echo Admin_Helper:: is_radio_checked( 'login', 'dashboard' ); ?> /> 
						<span><?php _e( 'Redirect to the user\'s account dashboard' ); ?></span>
					</label>
					<?php
						if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
							?>
							<label>
								<input type="radio" class="loginRedirectionRadio" name="LoginRadius_settings[LoginRadius_redirect]" value="bp" <?php echo Admin_Helper:: is_radio_checked( 'login', 'bp' ); ?> />
								<span><?php _e( 'Redirect to Buddypress profile page', 'LoginRadius' ); ?></span>
							</label>
							<?php
						}
					?>
					<label>
						<input type="radio" class="loginRedirectionRadio custom" name="LoginRadius_settings[LoginRadius_redirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'login', 'custom' ); ?> />
						<span><?php _e( 'Redirect to a custom URL' ); ?></span>
						<?php
							if ( isset( $loginRadiusSettings['LoginRadius_redirect'] ) && $loginRadiusSettings['LoginRadius_redirect'] == 'custom' ) {
								$inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_redirect'] );
							} else {
								$inputBoxValue = site_url();
							}
						?>
						<input type="text" id="loginRadiusCustomLoginUrl" name="LoginRadius_settings[custom_redirect]" size="60" value="<?php echo $inputBoxValue; ?>">
					</label>
				</div>
				<div>
					<h4>
						<?php _e( 'Redirection settings after registration', 'LoginRadius' ); ?>
						<span class="lr-tooltip" data-title="Page the user is redirected to after registration">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
					</h4>
					<label>
						<input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="samepage" <?php echo Admin_Helper:: is_radio_checked( 'register', 'samepage' ); ?> /> 
						<span><?php _e( 'Redirect to the same page where the user registered', 'LoginRadius' ); ?></span>
					</label>
					<label>
						<input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'register', 'homepage' ); ?> /> 
						<span><?php _e( 'Redirect to the home page of your WordPress site', 'LoginRadius' ); ?></span>
					</label>
					<label>
						<input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="dashboard" <?php echo Admin_Helper:: is_radio_checked( 'register', 'dashboard' ); ?> /> 
						<span><?php _e( 'Redirect to the user\'s account dashboard' ); ?></span>
					</label>
					<?php
						if ( isset( $loginRadiusLoginIsBpActive ) && $loginRadiusLoginIsBpActive ) {
						?>
							<label>
								<input type="radio" class="registerRedirectionRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="bp" <?php echo Admin_Helper:: is_radio_checked( 'register', 'bp' ); ?> />
								<span><?php _e( 'Redirect to Buddypress profile page', 'LoginRadius' ); ?></span>
							</label>
						<?php
						}
					?>
					<label>
						<input type="radio" class="registerRedirectionRadio custom" id="loginRadiusCustomRegRadio" name="LoginRadius_settings[LoginRadius_regRedirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'register', 'custom' ); ?> />
						<span><?php _e( 'Redirect to a custom URL', 'LoginRadius' ); ?></span>
						<?php
							if ( isset( $loginRadiusSettings['custom_regRedirect'] ) && $loginRadiusSettings['LoginRadius_regRedirect'] == 'custom' ) {
								$inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_regRedirect'] );
							} else {
								$inputBoxValue = site_url();
							}
						?>
						<input type="text" id="loginRadiusCustomRegistrationUrl" name="LoginRadius_settings[custom_regRedirect]" size="60" value="<?php echo $inputBoxValue; ?>" />
					</label>
				</div>
				<div>
					<h4>
						<?php _e( 'Redirection settings after logging out with Social Login widget', 'LoginRadius' ) ?>
						<span class="lr-tooltip" data-title="Page the user is redirected to after logout [Note: The logout function only works when clicking 'Logout' in the social login widget area. In all other cases, WordPress' default logout function will be applied.]">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
					</h4>
					<label>
						<input type="radio" class="logoutRedirectionRadio" name="LoginRadius_settings[LoginRadius_loutRedirect]" value="homepage" <?php echo Admin_Helper:: is_radio_checked( 'logoutUrl', 'homepage' ); ?> /> 
						<span><?php _e( 'Redirect to the home page', 'LoginRadius' ); ?></span>
					</label>

					<label>
						<input type="radio" class="logoutRedirectionRadio custom" name="LoginRadius_settings[LoginRadius_loutRedirect]" value="custom" <?php echo Admin_Helper:: is_radio_checked( 'logoutUrl', 'custom' ); ?> />
						<span><?php _e( 'Redirect to a custom URL', 'LoginRadius' ); ?></span>
						<?php
							if ( isset( $loginRadiusSettings['LoginRadius_loutRedirect'] ) && $loginRadiusSettings['LoginRadius_loutRedirect'] == 'custom' ) {
								$inputBoxValue = htmlspecialchars( $loginRadiusSettings['custom_loutRedirect'] );
							} else {
								$inputBoxValue = site_url();
							}
						?>
						<input type="text" id="loginRadiusCustomLogoutUrl" name="LoginRadius_settings[custom_loutRedirect]" size="60" value="<?php echo $inputBoxValue; ?>">
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
	</div>
	<?php
}
