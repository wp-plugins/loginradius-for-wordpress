<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The front function class of LoginRadius Raas.
 */
if ( !class_exists( 'LR_Social_Share_Shortcode' ) ) {

	class LR_Social_Share_Shortcode {

		/**
		 * Constructor
		 */
		public function __construct() {
			/**
			 * Shortcode for social sharing.
			 */
			add_shortcode( 'LoginRadius_Share', array( $this, 'sharing_shortcode' ) );
		}
		
		/**
		 * This function will be used to insert content where shortcode is used.
		 * Shortcode [LoginRadius_Share]
		 */
		public static function sharing_shortcode( $params ) {
			global $post, $loginradius_share_settings;

			if( is_object( $post ) ) {
				$lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );

				// if sharing disabled on this page/post, return content unaltered.
				if ( isset( $lrMeta['sharing'] ) && $lrMeta['sharing'] == 1 && ! is_front_page() ) {
					return;
				}
			}

			$hz_style = '';
			// Default parameters for shortcode.
			$default = array(
				'style' => '',
				'type' => 'horizontal',
			);

			// Extracting parameters.
			extract( shortcode_atts( $default, $params ) );
			
			if( isset( $loginradius_share_settings['horizontal_enable'] ) || isset( $loginradius_share_settings['vertical_enable'] ) ) {
				
				if( $style != '' ){
					$style = 'style="' . $style . '"';
				}

				if( $type == 'vertical' && isset( $loginradius_share_settings['vertical_enable'] ) && $loginradius_share_settings['vertical_enable'] == '1' ) {
					$unique_id = uniqid();
        			LR_Vertical_Sharing::$position['class'][] = $unique_id;
					$share = LR_Vertical_Sharing::get_vertical_sharing( 'lr_ver_share_shortcode ' . $unique_id, $style );
				}
				if ( $type == 'horizontal' && isset( $loginradius_share_settings['horizontal_enable'] ) && $loginradius_share_settings['horizontal_enable'] == '1' ) {
					$share = '<div class="lr_horizontal_share" ' . $style . '></div>';
				}

				return isset( $share ) ? $share : '';
			}else{
				return;
			}
		}

	}
}
new LR_Social_Share_Shortcode();



