<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * The horizontal sharing class.
 */
if ( ! class_exists( 'LR_Vertical_Sharing' ) ) {

    class LR_Vertical_Sharing {

		static $params;
		static $position;

    	/**
		 * Constructor
		 */
		public function __construct() {
			global $lr_js_in_footer;
			// Enqueue main scripts in footer
            if( $lr_js_in_footer ) {
            	add_action( 'wp_footer', array( $this, 'vertical_page_content' ), 1 );
            }else{
            	add_action( 'wp_enqueue_scripts', array( $this, 'vertical_page_content' ), 2);
            }
            // Always load this script in footer
            add_action( 'wp_footer', array( $this, 'get_vertical_sharing_script' ), 9999 );
		}

		/**
		 * Get LoginRadius Vertical div container.
		 */
		static function get_vertical_sharing( $class, $style = '') {
			return '<div class="lr-share-vertical-fix ' . $class . '" ' . $style . '></div>';	
		}

		/**
		 * Get LoginRadius Vertical Simple Social Sharing div and script.
		 */
		public static function get_vertical_sharing_script() {
			global $loginradius_share_settings, $loginradius_api_settings;
			
			if( ! isset($loginradius_share_settings['vertical_enable']) || ! $loginradius_share_settings['vertical_enable'] == '1' ){
				return;
			}

			$is_mobile = self::mobile_detect();
			if( $is_mobile && isset( $loginradius_share_settings['mobile_enable'] ) && $loginradius_share_settings['mobile_enable'] == '1' ) {
				return;
			}

			$position = self::get_vertical_position();
			
			if( isset(self::$position['class']) ) {
				foreach (self::$position['class'] as $key => $value) {
					$position[$value]['class'] = $value;
				}
			}

			if(isset($position)) {
				foreach ($position as $key => $value) {
					switch ($key) {
						case 'top_left':
							if( $value ) {
								$params = array(
									'top' => '0px',
									'right' => '',
									'bottom' => '',
									'left' => '0px'
								);
								$class = self::$params['top_left']['class'];
							}
							break;
						case 'top_right':
							if( $value ) {
								$params = array(
									'top' => '0px',
									'right' => '0px',
									'bottom' => '',
									'left' => ''
								);
								$class = self::$params['top_right']['class'];
							}
							break;
						case 'bottom_left':
							if( $value ) {
								$params = array(
									'top' => '',
									'right' => '',
									'bottom' => '0px',
									'left' => '0px'
								);
								$class = self::$params['bottom_left']['class'];
							}
							break;
						case 'bottom_right':
							if( $value ) {
								$params = array(
									'top' => '',
									'right' => '0px',
									'bottom' => '0px',
									'left' => ''
								);
								$class = self::$params['bottom_right']['class'];	
							}
							break;
						default:
							if( $value ) {
								$params = array(
									'top' => '',
									'right' => '',
									'bottom' => '',
									'left' => ''
								);
								$class = $position[$key]['class'];
							}
							break;
					}

					if( isset($params) ){
						$top = $params['top'] ? $params['top'] : '';
						$right = $params['right'];
						$bottom = $params['bottom'];
						$left = $params['left'];
						
						$hybrid = false;
						$theme = $loginradius_share_settings['vertical_share_interface'];
					
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

						//ob_start();
						
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
									<?php if( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings['LoginRadius_apikey'] ) ) { ?>
										$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
									<?php } ?>
									$i.size = "<?php echo $size; ?>";
									$u.isMobileFriendly = <?php echo ( isset( $loginradius_share_settings['mobile_enable'] ) && $loginradius_share_settings['mobile_enable'] == '1' ) ? 'true' : 'false'; ?>; 
									$i.show( "<?php echo $class; ?>" );
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
									$u.isMobileFriendly = <?php echo ( isset( $loginradius_share_settings['mobile_enable'] ) && $loginradius_share_settings['mobile_enable'] == '1' ) ? 'true' : 'false'; ?>;
									<?php if( isset( $loginradius_api_settings['LoginRadius_apikey'] ) && !empty( $loginradius_api_settings['LoginRadius_apikey'] ) ) { ?>
										$u.apikey = "<?php echo $loginradius_api_settings['LoginRadius_apikey']; ?>";
									<?php } ?>
									$i.isHorizontal = false;
									$i.size = "<?php echo $size; ?>";
									$i.show( "<?php echo $class; ?>" );
								});
							</script>
						<?php }
					}
					//echo ob_get_clean();
				}
			}	
		}

		public static function get_vertical_position() {
			global $loginradius_share_settings;

			if( isset($loginradius_share_settings['vertical_enable']) && $loginradius_share_settings['vertical_enable'] == '1' ){

				$top_left = false;
				$top_right = false;
				$bottom_left = false;
				$bottom_right = false;

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

				$position['top_left'] = $top_left;
				$position['top_right'] = $top_right;
				$position['bottom_left'] = $bottom_left;
				$position['bottom_right'] = $bottom_right;

				return $position;
			}
		}
		
		public static function mobile_detect() {
			
			$useragent = $_SERVER['HTTP_USER_AGENT'];

			if( preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
				return true;
			}else {
				return false;
			}
		}

		/**
		 * Output Sharing for the content.
		 */
		function vertical_page_content( $content ) {
			global $post, $loginradius_share_settings, $lr_js_in_footer;

			if( is_object( $post ) ) {
				$lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );

				// if sharing disabled on this page/post, return content unaltered.
				if ( isset( $lrMeta['sharing'] ) && $lrMeta['sharing'] == 1 && ! is_front_page() ) {
					return $content;
				}
			}

			$position = self::get_vertical_position();

			if( $position['top_left'] ){
				$class = uniqid( 'lr_' );
				self::$params['top_left']['class'] = $class;
				$content  .= self::get_vertical_sharing( $class );

			}
			if( $position['top_right'] ){
				$class = uniqid( 'lr_' );
				self::$params['top_right']['class'] = $class;
				$content  .= self::get_vertical_sharing( $class );
				
			}
			if( $position['bottom_left'] ){
				$class = uniqid( 'lr_' );
				self::$params['bottom_left']['class'] = $class;
				$content  .= self::get_vertical_sharing( $class );
			}
			if( $position['bottom_right'] ){
				$class = uniqid( 'lr_' );
				self::$params['bottom_right']['class'] = $class;
				$content  .= self::get_vertical_sharing( $class );
			}
			
			echo $content;
		}		
	}
}
new LR_Vertical_Sharing();





