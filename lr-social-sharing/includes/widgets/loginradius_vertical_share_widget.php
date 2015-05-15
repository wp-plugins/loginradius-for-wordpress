<?php
// If this file is called directly, abort.
defined('ABSPATH') or die();

/**
 * This file is responsible for creating Social Share Vertical widget.
 */
class LoginRadius_Vertical_Share_Widget extends WP_Widget {

    /**
     * Constructor.
     */
    function LoginRadius_Vertical_Share_Widget() {
        parent::WP_Widget('LoginRadiusVerticalShare' /* unique id */, 'LoginRadius - Vertical Sharing' /* title displayed at admin panel */, array('description' => __('Share content with Facebook, Twitter, Yahoo, Google and many more', 'LoginRadius')) /* Additional parameters */);
    }

    /**
     * This is rendered widget content.
     * 
     * @global type $post
     * @param type $args
     * @param type $instance
     * @return type
     */
    function widget($args, $instance) {
        global $post;

        if (is_object($post)) {
            $lrMeta = get_post_meta($post->ID, '_login_radius_meta', true);

            // If sharing disabled on this page/post, return content unaltered.
            if (isset($lrMeta['sharing']) && $lrMeta['sharing'] == 1 && !is_front_page()) {
                return;
            }
        }

        extract($args);
        if ($instance['hide_for_logged_in'] == 1 && is_user_logged_in()) {
            return;
        }
        $unique_id = uniqid();
        LR_Vertical_Sharing::$position['class'][] = $unique_id;
        LR_Common_Sharing::vertical_sharing();
        echo LR_Vertical_Sharing::get_vertical_sharing('lr_ver_share_widget ' . $unique_id, '');
    }

    /**
     * Everything which should happen when user edit widget at admin panel.
     * 
     * @param type $new_instance
     * @param type $old_instance
     * @return type
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['hide_for_logged_in'] = isset($new_instance['hide_for_logged_in'])?$new_instance['hide_for_logged_in']:'';
        return $instance;
    }

    /**
     * Widget edit form at admin panel.
     * 
     * @param type $instance
     */
    function form($instance) {
        $defaults = array('hide_for_logged_in' => '1');
        foreach ($instance as $key => $value) {
            $instance[$key] = esc_attr($value);
        }
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('hide_for_logged_in'); ?>"><?php _e('Hide for logged in users:', 'LoginRadius'); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('hide_for_logged_in'); ?>" name="<?php echo $this->get_field_name('hide_for_logged_in'); ?>" type="text" value='1' <?php if ($instance['hide_for_logged_in'] == 1) echo 'checked="checked"'; ?> />
        </p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget( "LoginRadius_Vertical_Share_Widget" );'));
