<?php

// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

if ( !class_exists( 'Social_Sharing' ) ) {

    /**
     * Class responsible for Social Sharing functionality
     */
    class Social_Sharing {

        /**
         * Social_Sharing calss instance
         *
         * @var string
         */
        private static $instance = null;

        /**
         * Get singleton object for class Social_Sharing
         *
         * @return object Social_Sharing
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new Social_Sharing();
            }

            return self::$instance;
        }

        /**
         * Constructor for class Social_Sharing
         */
        public function __construct() {
            require_once "class-sharing-helper.php";

            if ( Login_Radius_Common:: scripts_in_footer_enabled() ) {
                //Adding Sharing script in footer
                add_action( 'wp_footer', array('Sharing_Helper', 'login_radius_sharing_get_sharing_script') );
            } else {
                //By default adding script in header
                add_action( 'wp_enqueue_scripts', array('Sharing_Helper', 'login_radius_sharing_get_sharing_script') );
            }
            add_filter( 'the_content', array('Sharing_Helper', 'login_radius_sharing_content') );
            add_filter( 'get_the_excerpt', array('Sharing_Helper', 'login_radius_sharing_content') );
        }

    }

}
