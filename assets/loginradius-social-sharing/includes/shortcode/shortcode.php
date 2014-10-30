<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * This function will be used to insert content where shortcode is used.
 * Shortcode [LoginRadius_Share]
 */
function login_radius_sharing_sharing_shortcode( $params ) {
	global $loginradius_share_settings;
	
	if( !loginradius_share_verify_apikey() ){
		return;
	}
	$hz_style = '';
	// Default parameters for shortcode.
	$default = array(
		'style' => '',
		'type' => 'horizontal',
	);

	// Extracting parameters.
	extract( shortcode_atts( $default, $params ) );
	
	$error = WP_DEBUG == true ? '<p style="color:red;">Please enable ' . $type . ' sharing in the Social Sharing admin options' : '' ;
	if( isset( $loginradius_share_settings['horizontal_enable'] ) || isset( $loginradius_share_settings['vertical_enable'] ) ) {
		
		if( $style != '' ){
			$hz_style = 'style="' . $style . '"';
		}

		if( $type == 'vertical' && isset( $loginradius_share_settings['vertical_enable'] ) && $loginradius_share_settings['vertical_enable'] == '1' ) {
			$share = loginradius_share_get_vertical_sharing( '', '', '', '', $style );
		}else {
			if ( $type == 'horizontal' && isset( $loginradius_share_settings['horizontal_enable'] ) && $loginradius_share_settings['horizontal_enable'] == '1' ) {
				$share = '<div class="lr_horizontal_share" ' . $hz_style . '></div>';
			}
		}

		return isset( $share ) ? $share : $error;
	}else{
		
		return $error;
	}
}

/**
 * Shortcode for social sharing.
 */
add_shortcode( 'LoginRadius_Share', 'login_radius_sharing_sharing_shortcode' );

