<?php

/**
 *
 * function for rendering Customization Settings tab on settings page.
 */
function lr_render_social_customization_options( $loginRadiusSettings ) {

	?>
	<div id="lr_options_tab-3" class="lr-tab-frame">

		<!-- Social Login Interface Customization -->
		<div class="lr_options_container">
			<div class="lr-row">
				<h3>
					<?php _e( 'Social Login Interface', 'LoginRadius' ); ?>
				</h3>
				<div>
					
					<label>
						<span class="lr_property_title">
							<?php _e( 'Title', 'LoginRadius' ); ?>
							<span class="lr-tooltip" data-title="Enter the title of the Social Login interface">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</span>
						<input type="text" name="LoginRadius_settings[LoginRadius_title]" style="margin-left:280px;width:300px;" value= "<?php echo htmlspecialchars( $loginRadiusSettings['LoginRadius_title'] ); ?>" />
					</label>
				</div>
				<div>
					<label style="line-height:41px;">
						<span class="lr_property_title" style="margin-top:0px;">
							<?php _e( 'Social Login Icon Size', 'LoginRadius' ); ?>
							<span class="lr-tooltip" data-title="Select the size of the icons in your Social Login interface. This option does not apply to all Social Login themes.">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</span>
						<input type="radio" style="margin-left:280px;" name="LoginRadius_settings[LoginRadius_interfaceSize]" value='large' <?php echo ( !isset( $loginRadiusSettings['LoginRadius_interfaceSize'] ) || $loginRadiusSettings['LoginRadius_interfaceSize'] == 'large' ) ? 'checked' : ''; ?> />
						<span><?php _e( 'Large', 'LoginRadius' ); ?></span>
						<input type="radio" name="LoginRadius_settings[LoginRadius_interfaceSize]" value="small" <?php echo ( isset( $loginRadiusSettings['LoginRadius_interfaceSize'] ) && $loginRadiusSettings['LoginRadius_interfaceSize'] == 'small' ) ? 'checked' : ''; ?> /> 
						<span><?php _e( 'Small', 'LoginRadius' ); ?></span>
					</label>
				</div>
				<div>
					
				<label>
					<span class="lr_property_title">
						<?php _e( 'Number of Social Icons Per Row', 'LoginRadius' ); ?>
						<span class="lr-tooltip" data-title="Enter the number of social icons to display in each row">
							<span class="dashicons dashicons-editor-help"></span>
						</span>
					</span>
					<input type="text" name="LoginRadius_settings[LoginRadius_numColumns]" style="margin-left:280px;width:50px;" maxlength="2" value="<?php
							if ( isset( $loginRadiusSettings['LoginRadius_numColumns'] ) ) {
								echo sanitize_text_field( trim( $loginRadiusSettings['LoginRadius_numColumns'] ) );
							}
							?>" />
				</label>
				</div>
				<div>
					<label>
						<span class="lr_property_title">
							<?php _e( 'Background Color', 'LoginRadius' ); ?>
							<span class="lr-tooltip" data-title="Select the background color of the Social Login interface">
								<span class="dashicons dashicons-editor-help"></span>
							</span>
						</span>
						<?php
							if ( isset( $loginRadiusSettings['LoginRadius_backgroundColor'] ) ) {
								$colorValue = esc_html( trim( $loginRadiusSettings['LoginRadius_backgroundColor'] ) );
							} else {
								$colorValue = '';
							}
						?>
						<div class="lr-color-picker-container">
						<input type="text" class="color_picker" name="LoginRadius_settings[LoginRadius_backgroundColor]" value="<?php echo $colorValue; ?>" />
						</div>
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