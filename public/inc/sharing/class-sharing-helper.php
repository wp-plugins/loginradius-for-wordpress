<?php

// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

if ( !class_exists( 'Sharing_Helper' ) ) {

    /**
     * Helper/Utility class for social sharing functionality
     */
    class Sharing_Helper {

        /**
         * Add LoginRadius Plugin Sharing script pn pages and posts.
         *
         * global $loginRadiusSettings;
         */
        public static function login_radius_sharing_get_sharing_script() {
            global $loginRadiusSettings;
            $sharingScript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true; var hybridsharing = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script>';
            $sharingScript .= '<script type="text/javascript">';

            if ( $loginRadiusSettings['horizontal_shareEnable'] == '1' ) {
                // check horizontal sharing enabled
                $sharingScript .= self:: login_radius_sharing_get_sharing_script_horizontal( $loginRadiusSettings );
            }
            if ( $loginRadiusSettings['vertical_shareEnable'] == '1' ) {
                // check vertical sharing enabled
                $sharingScript .= self::login_radius_sharing_get_sharing_script_vertical( $loginRadiusSettings );
            }

            $sharingScript .= '</script>';
            echo $sharingScript;
        }

        /**
         * function returns script required for horizontal sharing.
         *
         * global $loginRadiusSettings;
         */
        public static function login_radius_sharing_get_sharing_script_horizontal() {
            global $loginRadiusSettings;
            $size = '';
            $interface = '';
            $sharingScript = '';
            $horizontalThemvalue = isset( $loginRadiusSettings['horizontalSharing_theme'] ) ? $loginRadiusSettings['horizontalSharing_theme'] : '';

            switch ( $horizontalThemvalue ) {
                case '32':
                    $size = '32';
                    $interface = 'horizontal';
                    break;

                case '16':
                    $size = '16';
                    $interface = 'horizontal';
                    break;

                case 'single_large':
                    $size = '32';
                    $interface = 'simpleimage';
                    break;

                case 'single_small':
                    $size = '16';
                    $interface = 'simpleimage';
                    break;

                case 'counter_vertical':
                    $ishorizontal = 'true';
                    $interface = 'simple';
                    $countertype = 'vertical';
                    break;

                case 'counter_horizontal':
                    $ishorizontal = 'true';
                    $interface = 'simple';
                    $countertype = 'horizontal';
                    break;

                default:
                    $size = '32';
                    $interface = 'horizontal';
                    break;
            }
            if ( !empty( $ishorizontal ) ) {
                $providers = self:: get_counter_providers( 'horizontal' );
                // prepare counter script
                $sharingScript .= 'LoginRadius.util.ready( function() { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.' . $interface . '; $S.isHorizontal = ' . $ishorizontal . '; $S.countertype = \'' . $countertype . '\'; $u = LoginRadius.user_settings; $u.isMobileFriendly = true; $S.show( "loginRadiusHorizontalSharing" ); } );';
            } else {
                $providers = self:: get_sharing_providers( 'horizontal' );
                // prepare sharing script
                $sharingScript .= 'LoginRadius.util.ready( function() { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings;';
                if ( isset( $loginRadiusSettings['LoginRadius_apikey'] ) && !empty( $loginRadiusSettings['LoginRadius_apikey'] ) ) {
                    $sharingScript .= '$u.apikey= \'' . trim( $loginRadiusSettings['LoginRadius_apikey'] ) . '\';';
                }
                $sharingScript .= '$i.size = ' . $size . '; $u.sharecounttype="url"; $u.isMobileFriendly=true; $i.show( "loginRadiusHorizontalSharing" ); } );';
            }
            return $sharingScript;
        }

        /**
         * function returns script required for vertical sharing.
         *
         * global $loginRadiusSettings;
         */
        public static function login_radius_sharing_get_sharing_script_vertical() {
            global $loginRadiusSettings;
            $sharingScript = '';
            $verticalThemvalue = isset( $loginRadiusSettings['verticalSharing_theme'] ) ? $loginRadiusSettings['verticalSharing_theme'] : '';

            switch ( $verticalThemvalue ) {
                case '32':
                    $size = '32';
                    $interface = 'Simplefloat';
                    $sharingVariable = 'i';
                    break;

                case '16':
                    $size = '16';
                    $interface = 'Simplefloat';
                    $sharingVariable = 'i';
                    break;

                case 'counter_vertical':
                    $sharingVariable = 'S';
                    $ishorizontal = 'false';
                    $interface = 'simple';
                    $type = 'vertical';
                    break;

                case 'counter_horizontal':
                    $sharingVariable = 'S';
                    $ishorizontal = 'false';
                    $interface = 'simple';
                    $type = 'horizontal';
                    break;

                default:
                    $size = '32';
                    $interface = 'Simplefloat';
                    $sharingVariable = 'i';
                    break;
            }

            $verticalPosition = isset( $loginRadiusSettings['sharing_verticalPosition'] ) ? $loginRadiusSettings['sharing_verticalPosition'] : '';
            switch ( $verticalPosition ) {
                case "top_left":
                    $position1 = 'top';
                    $position2 = 'left';
                    break;

                case "top_right":
                    $position1 = 'top';
                    $position2 = 'right';
                    break;

                case "bottom_left":
                    $position1 = 'bottom';
                    $position2 = 'left';
                    break;

                case "bottom_right":
                    $position1 = 'bottom';
                    $position2 = 'right';
                    break;

                default:
                    $position1 = 'top';
                    $position2 = 'left';
                    break;
            }

            $offset = '$' . $sharingVariable . '.' . $position1 . ' = \'0px\'; $' . $sharingVariable . '.' . $position2 . ' = \'0px\';';

            if ( empty( $size ) ) {
                $providers = self:: get_counter_providers( 'vertical' );
                $sharingScript .= 'LoginRadius.util.ready( function() { $SC.Providers.Selected = ["' . $providers . '"]; $S = $SC.Interface.' . $interface . '; $S.isHorizontal = ' . $ishorizontal . '; $S.countertype = \'' . $type . '\'; ' . $offset . ' $u = LoginRadius.user_settings; $u.isMobileFriendly = true; $S.show( "loginRadiusVerticalSharing" ); } );';
            } else {
                $providers = self:: get_sharing_providers( 'vertical' );
                // prepare sharing script
                $sharingScript .= 'LoginRadius.util.ready( function() { $i = $SS.Interface.' . $interface . '; $SS.Providers.Top = ["' . $providers . '"]; $u = LoginRadius.user_settings;';
                $sharingScript .= '$u.apikey= \'' . trim( $loginRadiusSettings['LoginRadius_apikey'] ) . '\';';
                $sharingScript .= '$i.size = ' . $size . '; ' . $offset . ' $u.isMobileFriendly=true; $i.show( "loginRadiusVerticalSharing" ); } );';
            }
            return $sharingScript;
        }

        /**
         * function returns comma seperated counters lists
         *
         * global $loginRadiusSettings;
         */
        public static function get_counter_providers( $themeType ) {
            global $loginRadiusSettings;
            $searchOption = $themeType . '_counter_providers';
            if ( isset( $loginRadiusSettings[$searchOption] ) && is_array( $loginRadiusSettings[$searchOption] ) && count( $loginRadiusSettings[$searchOption] ) > 0 ) {
                return implode( '","', $loginRadiusSettings[$searchOption] );
            } else {
                return 'Facebook Like","Google+ +1","Pinterest Pin it","LinkedIn Share","Hybridshare';
            }
        }

        /**
         * function returns comma seperated sharing providers lists
         *
         * global $loginRadiusSettings;
         */
        public static function get_sharing_providers( $themeType ) {
            global $loginRadiusSettings;
            $searchOption = $themeType . '_rearrange_providers';
            if ( isset( $loginRadiusSettings[$searchOption] ) && is_array( $loginRadiusSettings[$searchOption] ) && count( $loginRadiusSettings[$searchOption] ) > 0 ) {
                return implode( '","', $loginRadiusSettings[$searchOption] );
            } else {
                return 'Facebook","Twitter","Pinterest","Print","Email';
            }
        }

        /**
         * Callback for filter the_content,
         * This function insert appropriate div for Sharing on WordPress pages/posts
         */
        public static function login_radius_sharing_content( $content ) {

            global $post, $loginRadiusSettings;
            $lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );

            // if sharing disabled on this page/post, return content unaltered
            if ( isset( $lrMeta['sharing'] ) && $lrMeta['sharing'] == 1 && !is_front_page() ) {
                return $content;
            }
            if ( isset( $loginRadiusSettings['horizontal_shareEnable'] ) && $loginRadiusSettings['horizontal_shareEnable'] == '1' ) {
                // If horizontal sharing is enabled
                $loginRadiusHorizontalSharingDiv = '<div class="loginRadiusHorizontalSharing"';
                $loginRadiusHorizontalSharingDiv .= ' data-share-url="' . get_permalink( $post->ID ) . '" data-counter-url="' . get_permalink( $post->ID ) . '"';
                $loginRadiusHorizontalSharingDiv .= ' ></div>';

                $horizontalDiv = $loginRadiusHorizontalSharingDiv;
                $sharingFlag = '';
                //displaying sharing interface on home page
                if ( ( ( isset( $loginRadiusSettings['horizontal_sharehome'] ) && current_filter() == 'the_content' ) || ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' ) ) && is_front_page() && isset( $loginRadiusSettings['horizontal_sharehome'] ) ) {
                    //checking if current page is home page and sharing on home page is enabled.
                    $sharingFlag = 'true';
                }
                //displaying sharing interface on Post and pages
                if ( ( isset( $loginRadiusSettings['horizontal_sharepost'] ) && is_single() ) || ( isset( $loginRadiusSettings['horizontal_sharepage'] ) && is_page() && !is_front_page()) ) {
                    $sharingFlag = 'true';
                }

                if ( ( isset( $loginRadiusSettings['horizontal_sharepost'] ) && current_filter() == 'the_content' && is_single() ) || ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' && is_page() ) ) {
                    //checking if custom page is used for displaying posts
                    $sharingFlag = 'true';
                }

                if ( is_page() && !is_front_page() && isset( $loginRadiusSettings['horizontal_sharepage'] ) ) {
                    //If not home page and sharing on pages is enabled.
                    $sharingFlag = 'true';
                }

                if ( is_front_page() && !isset( $loginRadiusSettings['horizontal_sharehome'] ) ) {
                    //If sharing on front page disabled.
                    if ( true == $sharingFlag ) {
                        $sharingFlag = '';
                    }
                }

                if ( isset( $loginRadiusSettings['horizontal_shareexcerpt'] ) && current_filter() == 'get_the_excerpt' ) {
                    //If sharing on Post Excerpts is enabled.
                    $sharingFlag = 'true';
                }

                if ( isset( $loginRadiusSettings['horizontal_sharepost'] ) && current_filter() == 'the_content' && !is_single() && is_home() && isset( $loginRadiusSettings['horizontal_sharehome'] ) ) {
                    //If sharing on Post  is enabled and page is blog/home page.
                    $sharingFlag = 'true';
                }

                if ( !empty( $sharingFlag ) ) {
                    if ( isset( $loginRadiusSettings['horizontal_shareTop'] ) && isset( $loginRadiusSettings['horizontal_shareBottom'] ) ) {
                        $content = $horizontalDiv . '<br/>' . $content . '<br/>' . $horizontalDiv;
                    } else {
                        if ( isset( $loginRadiusSettings['horizontal_shareTop'] ) ) {
                            $content = $horizontalDiv . $content;
                        } elseif ( isset( $loginRadiusSettings['horizontal_shareBottom'] ) ) {
                            $content = $content . $horizontalDiv;
                        }
                    }
                }
            }
            if ( isset( $loginRadiusSettings['vertical_shareEnable'] ) && $loginRadiusSettings['vertical_shareEnable'] == '1' ) {
                $vertcalSharingFlag = '';
                $loginRadiusVerticalSharingDiv = '<div class="loginRadiusVerticalSharing" style="z-index: 10000000000"></div>';

                if ( ( ( isset( $loginRadiusSettings['vertical_sharehome'] ) && current_filter() == 'the_content' ) ) && is_front_page() && isset( $loginRadiusSettings['vertical_sharehome'] ) ) {
                    // If vertical sharing on Home page enabled.
                    $vertcalSharingFlag = 'true';
                }

                if ( ( isset( $loginRadiusSettings['vertical_sharepost'] ) && current_filter() == 'the_content' ) && is_page() ) {
                    //checking if custom page is used for displaying posts.
                    $vertcalSharingFlag = 'true';
                }

                if ( ( isset( $loginRadiusSettings['vertical_sharepost'] ) && is_single() ) || ( isset( $loginRadiusSettings['vertical_sharepage'] ) && is_page() ) ) {
                    //displaying sharing interface on Post and pages.
                    $vertcalSharingFlag = 'true';
                }

                if ( is_page() && !is_front_page() && isset( $loginRadiusSettings['vertical_sharepage'] ) ) {
                    //If not front page and vertical sharing on pages is enabled.
                    $vertcalSharingFlag = 'true';
                }
                if ( is_front_page() && !isset( $loginRadiusSettings['vertical_sharehome'] ) ) {
                    //if page is front page and vertical sharing is disabled on home page.
                    if ( $sharingFlag ) {
                        $vertcalSharingFlag = '';
                    }
                }
                if ( is_home() && isset( $loginRadiusSettings['vertical_sharehome'] ) ) {

                    $vertcalSharingFlag = 'true';
                }
                if ( !empty( $vertcalSharingFlag ) ) {
                    //if Vertical sharing is needed on current page.
                    global $loginRadiusSharingVerticalInterfaceContentCount, $loginRadiusSharingVerticalInterfaceExcerptCount;
                    if ( current_filter() == 'the_content' ) {
                        $compareVariable = 'loginRadiusSharingVerticalInterfaceContentCount';
                    } elseif ( current_filter() == 'get_the_excerpt' ) {
                        $compareVariable = 'loginRadiusSharingVerticalInterfaceExcerptCount';
                    }
                    if ( $$compareVariable == 0 ) {
                        $content = $content . $loginRadiusVerticalSharingDiv;
                        $$compareVariable++;
                    } else {
                        $content = $content . $loginRadiusVerticalSharingDiv;
                    }
                }
            }
            //returnig the content with sharing interface.
            return $content;
        }

    }

}
