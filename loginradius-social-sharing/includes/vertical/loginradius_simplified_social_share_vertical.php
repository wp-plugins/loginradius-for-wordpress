<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * Get LoginRadius Vertical Simple Social Sharing div and script.
 */
function loginradius_share_get_vertical_sharing( $top, $right, $bottom, $left, $style ) {
	global $loginradius_share_settings, $loginradius_api_settings;
	$div_class = uniqid( 'lr_' );
	$hybrid = false;
	$theme = $loginradius_share_settings['vertical_share_interface'];

	if( $top == '' && $right == '' && $bottom == '' && $left == '' ) {
		$div_class .= " lr_ver_share_widget";
		$style = 'style="position: relative !important;' . $style . '"';
	}
	switch ( $theme ) {
		case '32-v':
			$size = '32';
			break;
		case '16-v':
			$size = '16';
			break;
		case 'hybrid-v-h':
			$hybrid = true;
			$size = '32';
            $countertype = "horizontal";
			break;
		case 'hybrid-v-v':
			$hybrid = true;
			$size = '32';
            $countertype = "vertical";
			break;
		default:
			$size = '32';
			$top = 'top';
			$left = 'left';
			break;
	}

	ob_start();
	?>
		<div class="<?php echo $div_class; ?>" <?php echo $style; ?> ></div>
	<?php
		if( $hybrid == false ) {
	?>
		<script type="text/javascript">
			LoginRadius.util.ready(function () {
				$i = $SS.Interface.Simplefloat; 
				$i.top = '<?php echo $top; ?>';
				$i.right = '<?php echo $right; ?>';
				$i.bottom = '<?php echo $bottom; ?>';
				$i.left = '<?php echo $left; ?>';
				$SS.Providers.Top = ["<?php echo implode('","', $loginradius_share_settings['vertical_rearrange_providers']); ?>"];
				$u = LoginRadius.user_settings;
				$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
				$i.size = "<?php echo $size; ?>";
				$u.isMobileFriendly=true; 
				$i.show( "<?php echo $div_class; ?>" );
			});
		</script>
	<?php
		} else { ?>
		<script type="text/javascript">
			LoginRadius.util.ready( function() {
				$i = $SC.Interface.simple;
				$i.top = '<?php echo $top; ?>';
				$i.right = '<?php echo $right; ?>';
				$i.bottom = '<?php echo $bottom; ?>';
				$i.left = '<?php echo $left; ?>';
				$SC.Providers.Selected = ["<?php echo implode('","', $loginradius_share_settings['vertical_sharing_providers']['Hybrid']); ?>"];
				$u = LoginRadius.user_settings;
				$i.countertype = "<?php echo $countertype ?>";
				$u.isMobileFriendly = true;
				$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
				$i.isHorizontal = false;
				$i.size = "<?php echo $size; ?>";
				$i.show( "<?php echo $div_class; ?>" );
			});
		</script>
	<?php }
	return ob_get_clean();
}

/**
 * Output Sharing for the content.
 */
function loginradius_share_vertical_content( $content ) {
	global $loginradius_share_settings;

	$top_left = false;
	$top_right = false;
	$bottom_left = false;
	$bottom_right = false;

	if( isset($loginradius_share_settings['vertical_enable']) && $loginradius_share_settings['vertical_enable'] == '1' && loginradius_share_verify_apikey() ){

		// Show on Pages.
		if( is_page() && ! is_front_page() && ( isset($loginradius_share_settings['vertical_position']['Pages']['Top Left']) || isset($loginradius_share_settings['vertical_position']['Pages']['Top Right']) || isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Left']) || isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Right']) ) ) {
			if( isset($loginradius_share_settings['vertical_position']['Pages']['Top Left']) )
				$top_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Pages']['Top Right']) )
				$top_right = true;
			if( isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Left']) )
				$bottom_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Pages']['Bottom Right']) )
				$bottom_right = true;
		}

		// Show on Front Static Page.
		if( is_front_page() && ! is_home() && ( isset($loginradius_share_settings['vertical_position']['Home']['Top Left']) || isset($loginradius_share_settings['vertical_position']['Home']['Top Right']) || isset($loginradius_share_settings['vertical_position']['Home']['Bottom Left']) || isset($loginradius_share_settings['vertical_position']['Home']['Bottom Right']) ) ) {
			if( isset($loginradius_share_settings['vertical_position']['Home']['Top Left']) )
				$top_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Top Right']) )
				$top_right = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Bottom Left']) )
				$bottom_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Bottom Right']) )
				$bottom_right = true;
		}

		// Show on Posts.
		if( is_single() && ( isset($loginradius_share_settings['vertical_position']['Posts']['Top Left']) || isset($loginradius_share_settings['vertical_position']['Posts']['Top Right']) || isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Left']) || isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Right']) ) ) {
			if( isset($loginradius_share_settings['vertical_position']['Posts']['Top Left']) )
				$top_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Posts']['Top Right']) )
				$top_right = true;
			if( isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Left']) )
				$bottom_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Posts']['Bottom Right']) )
				$bottom_right = true;
		}
	}

	$return = $content;
	if( $top_left ){
		$return  .= loginradius_share_get_vertical_sharing( '0px', '', '', '0px', '' );
	}
	if( $top_right ){
		$return  .= loginradius_share_get_vertical_sharing( '0px', '0px', '', '', '' );
	}
	if( $bottom_left ){
		$return  .= loginradius_share_get_vertical_sharing( '', '', '0px', '0px', '' );
	}
	if( $bottom_right ){
		$return  .= loginradius_share_get_vertical_sharing( '', '0px', '0px', '', '' );
	}
	
	return $return;
}
add_filter( 'the_content', 'loginradius_share_vertical_content' );

/**
 * Output Sharing for the Front Page Posts content.
 */
function loginradius_share_vertical_front_page_posts( $content ) {
	global $loginradius_share_settings;
	$return = "";
	$top_left = false;
	$top_right = false;
	$bottom_left = false;
	$bottom_right = false;

	if( isset($loginradius_share_settings['vertical_enable']) && $loginradius_share_settings['vertical_enable'] == '1' && loginradius_share_verify_apikey() ){
		
		// Show on Front Page.
		if( is_front_page() && is_home() && ( isset($loginradius_share_settings['vertical_position']['Home']['Top Left']) || isset($loginradius_share_settings['vertical_position']['Home']['Top Right']) || isset($loginradius_share_settings['vertical_position']['Home']['Bottom Left']) || isset($loginradius_share_settings['vertical_position']['Home']['Bottom Right']) ) ) {
			if( isset($loginradius_share_settings['vertical_position']['Home']['Top Left']) )
				$top_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Top Right']) )
				$top_right = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Bottom Left']) )
				$bottom_left = true;
			if( isset($loginradius_share_settings['vertical_position']['Home']['Bottom Right']) )
				$bottom_right = true;
		}
	}

	//$return = $content;
	if( $top_left ){
		$return .= loginradius_share_get_vertical_sharing( '0px', '', '', '0px', '' );
	}
	if( $top_right ){
		$return .= loginradius_share_get_vertical_sharing( '0px', '0px', '', '', '' );
	}
	if( $bottom_left ){
		$return .= loginradius_share_get_vertical_sharing( '', '', '0px', '0px', '' );
	}
	if( $bottom_right ){
		$return .= loginradius_share_get_vertical_sharing( '', '0px', '0px', '', '' );
	}

	echo $return;
}
add_filter( 'wp_head', 'loginradius_share_vertical_front_page_posts' );





