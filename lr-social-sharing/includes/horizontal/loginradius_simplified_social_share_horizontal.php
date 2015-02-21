<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * The horizontal sharing class.
 */
if ( ! class_exists( 'LR_Horizontal_Sharing' ) ) {

    class LR_Horizontal_Sharing {

    	/**
		 * Constructor
		 */
		public function __construct() {
			global $lr_js_in_footer;

			// Enqueue main scripts in footer
            if( $lr_js_in_footer ) {
                add_action( 'wp_footer', array( $this, 'loginradius_share_get_horizontal_sharing' ), 100 );
            }else{
                add_action( 'wp_head', array( $this, 'loginradius_share_get_horizontal_sharing' ), 100 );
            }
            
            add_filter( 'the_content', array( $this, 'loginradius_share_horizontal_content' ) );
			add_filter( 'get_the_excerpt', array( $this, 'loginradius_share_horizontal_content' ) );
		}

		/**
		 * Get LoginRadius Horizontal Simple Social Sharing JavaScript loaded in <head>.
		 */
		function loginradius_share_get_horizontal_sharing() {
			global $loginradius_share_settings, $loginradius_api_settings;

			$hybrid = false;
			$theme = $loginradius_share_settings['horizontal_share_interface'];

			switch ( $theme ) {
				case '32-h':
					$size = '32';
					$interface = 'horizontal';
					break;
				case '16-h':
					$size = '16';
					$interface = 'horizontal';
					break;
				case 'single-lg-h':
					$size = '32';
					$interface = 'simpleimage';
					break;
				case 'single-sm-h':
					$size = '16';
					$interface = 'simpleimage';
					break;
				case 'hybrid-h-h':
					$hybrid = true;
					$size = '32';
					$countertype = "horizontal";
					break;
				case 'hybrid-h-v':
					$hybrid = true;
					$size = '32';
					$countertype = "vertical";
					break;
				default:
					$size = '32';
					$interface = 'horizontal';
					break;
			}
				if( $hybrid == false ) {
			?>
				<script type="text/javascript">
					LoginRadius.util.ready(function () { 
						$i = $SS.Interface.<?php echo $interface; ?>;
						$SS.Providers.Top = ["<?php echo implode('","', $loginradius_share_settings['horizontal_rearrange_providers']); ?>"];
						$u = LoginRadius.user_settings;
						$u.sharecounttype='url';
						$u.isMobileFriendly = <?php echo ( isset( $loginradius_share_settings['mobile_enable'] ) && $loginradius_share_settings['mobile_enable'] == '1' ) ? 'true' : 'false'; ?>;
						<?php if( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings['LoginRadius_apikey'] ) ) { ?>
						$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
						<?php } ?>
						$i.size = "<?php echo $size; ?>"; 
						$i.show( "lr_horizontal_share" );
					});
				</script>
			<?php
				} else { ?>
				<script type="text/javascript">
					LoginRadius.util.ready( function() {
						$i = $SC.Interface.simple;
						$SC.Providers.Selected = ["<?php echo implode('","', $loginradius_share_settings['horizontal_sharing_providers']['Hybrid']); ?>"];
						$u = LoginRadius.user_settings;
						$i.countertype = "<?php echo $countertype ?>";
						$u.isMobileFriendly = <?php echo ( isset( $loginradius_share_settings['mobile_enable'] ) && $loginradius_share_settings['mobile_enable'] == '1' ) ? 'true' : 'false'; ?>;
						<?php if( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings['LoginRadius_apikey'] ) ) { ?>
						$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
						<?php } ?>
						$i.isHorizontal = "<?php echo $countertype; ?>";
						$i.size = "<?php echo $size; ?>";
						$i.show( "lr_horizontal_share" );
					});
				</script>
			<?php }
		}
		
		/**
		 * Output Sharing <div> for the content.
		 */
		function loginradius_share_horizontal_content( $content ) {
			global $post, $loginradius_share_settings;

			$return = '';
			$top = false;
			$bottom = false;

		    if( is_object( $post ) ) {
				$lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );

				// if sharing disabled on this page/post, return content unaltered.
				if ( isset( $lrMeta['sharing'] ) && $lrMeta['sharing'] == 1 && ! is_front_page() ) {
					return $content;
				}
			}

			if( isset($loginradius_share_settings['horizontal_enable']) && $loginradius_share_settings['horizontal_enable'] == '1' ) {
					if ( current_filter() == 'the_content' ) {
						// Show on Pages.
						if( is_page() && ! is_front_page() && ( isset($loginradius_share_settings['horizontal_position']['Pages']['Top'])  || isset($loginradius_share_settings['horizontal_position']['Pages']['Bottom']) ) ) {
							if( isset($loginradius_share_settings['horizontal_position']['Pages']['Top']) )
								$top = true;
							if( isset($loginradius_share_settings['horizontal_position']['Pages']['Bottom']) )
								$bottom = true;
						}

						// Show on Front Page.
						if( is_front_page() && ( isset($loginradius_share_settings['horizontal_position']['Home']['Top']) || isset($loginradius_share_settings['horizontal_position']['Home']['Bottom']) ) ) {
							if( isset($loginradius_share_settings['horizontal_position']['Home']['Top']) )
								$top = true;
							if( isset($loginradius_share_settings['horizontal_position']['Home']['Bottom']) )
								$bottom = true;
						}

						// Show on Front Page.
						if( is_front_page() && is_home() && ( isset($loginradius_share_settings['horizontal_position']['Home']['Top']) || isset($loginradius_share_settings['horizontal_position']['Home']['Bottom']) ) ) {
							if( isset($loginradius_share_settings['horizontal_position']['Home']['Top']) )
								$top = true;
							if( isset($loginradius_share_settings['horizontal_position']['Home']['Bottom']) )
								$bottom = true;
						}

						// Show on Blog Page When not front page.
						if( is_home() && ! is_front_page() && ( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Top']) || isset($loginradius_share_settings['horizontal_position']['Excerpts']['Bottom']) ) ) {
							if( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Top']) )
								$top = true;
							if( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Bottom']) )
								$bottom = true;
						}

						// Show on Posts.
						if( is_single() && ( isset($loginradius_share_settings['horizontal_position']['Posts']['Top']) || isset($loginradius_share_settings['horizontal_position']['Posts']['Bottom']) ) ) {
							if( isset($loginradius_share_settings['horizontal_position']['Posts']['Top']) )
								$top = true;
							if( isset($loginradius_share_settings['horizontal_position']['Posts']['Bottom']) )
								$bottom = true;
						}
					}

					if ( current_filter() == 'get_the_excerpt' && ( isset( $loginradius_share_settings['horizontal_position']['Excerpts']['Top'] ) || isset( $loginradius_share_settings['horizontal_position']['Excerpts']['Bottom'] ) ) ) {
						if( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Top']) ) {
							$top = true;
						}
						if( isset($loginradius_share_settings['horizontal_position']['Excerpts']['Bottom']) ) {
							$bottom = true;
						}
					}
			}

			if( $top ) {
				$return  = '<div class="lr_horizontal_share" data-share-url="' . get_permalink( $post->ID ) . '" data-counter-url="' . get_permalink( $post->ID ) . '"></div>';
			}

			$return .= $content;

			if( $bottom ) {
				$return .= '<div class="lr_horizontal_share" data-share-url="' . get_permalink( $post->ID ) . '" data-counter-url="' . get_permalink( $post->ID ) . '"></div>';
			}
			return $return;
		}	
	}
}
new LR_Horizontal_Sharing();