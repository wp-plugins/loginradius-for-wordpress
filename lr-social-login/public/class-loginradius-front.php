<?php

/**
 * Plugin Name.
 *
 * @package   loginradius-for-wordpress
 * @author    LoginRadius Team
 * @license   GPL-2.0+
 * @link      http://loginradius.com
 * @copyright 2014 LoginRadius
 */

/**
 * Plugin class. This class would ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in administrative or dashboard
 * functionality, then refer to `class-loginradiusadmin.php`
 *
 *
 * @package LoginRadius
 * @author  LoginRadius Team
 */
class Login_Radius_Front {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Get singleton object for class Login_Radius_Front
     * 
     * @return object Login_Radius_Front
     */
    public static function get_instance() {
        if ( !isset( self::$instance ) && !( self::$instance instanceof Login_Radius_Front ) ) {
            self::$instance = new Login_Radius_Front();
        }
        return self::$instance;
    }

    /**
     * Constructor which loads required files at front
     */
    private function __construct() {

        require_once "inc/login/class-social-login.php";
        Social_Login:: get_instance();

        require_once "inc/commenting/class-social-commenting.php";
        Social_Commenting:: get_instance();

        require_once "inc/shortcodes/class-shortcode.php";
        Login_Radius_Shortcode:: get_instance();
    }

}

Login_Radius_Front:: get_instance();


