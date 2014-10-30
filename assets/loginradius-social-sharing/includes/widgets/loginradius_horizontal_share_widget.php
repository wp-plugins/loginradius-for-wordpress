<?php

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die();

/**
 * This file is responsible for creating Social Share Horizontal widget.
 */
class LoginRadius_Horizontal_Share_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	function LoginRadius_Horizontal_Share_Widget() {
		parent::WP_Widget( 'LoginRadiusHorizontalShare' /* unique id */, 'LoginRadius - Horizontal Sharing' /* title displayed at admin panel */, array('description' => __( 'Share content with Facebook, Twitter, Yahoo, Google and many more', 'LoginRadius' )) /* Additional parameters */ );
	}

	/**
	 * This is rendered widget content.
	 */
	function widget( $args, $instance ) {
		global $loginradius_share_settings;
		extract( $args );
		if( !loginradius_share_verify_apikey() ){
			return;
		}
		if ( !isset($loginradius_share_settings['horizontal_enable']) ) {
			if(WP_DEBUG == true) error_log('Please enable horizontal sharing in the Social Sharing admin options');
			echo (WP_DEBUG == true) ? '<p style="color:red;">Please enable horizontal sharing in the Social Sharing admin options' : '' ;
			return; 
		}
		if ( $instance['hide_for_logged_in'] == 1 && is_user_logged_in() ) {
			return;
		}
		echo $before_widget;

		if ( ! empty( $instance['before_widget_content'] ) ) {
			echo $instance['before_widget_content'];
		}
		echo '<div class="lr_horizontal_share"></div>';

		if ( ! empty( $instance['after_widget_content'] ) ) {
			echo $instance['after_widget_content'];
		}
		echo $after_widget;
	}

	/**
	 * Everything which should happen when user edit widget at admin panel.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['before_widget_content'] = $new_instance['before_widget_content'];
		$instance['after_widget_content'] = $new_instance['after_widget_content'];
		$instance['hide_for_logged_in'] = $new_instance['hide_for_logged_in'];

		return $instance;
	}

	/**
	 * Widget edit form at admin panel.
	 */
	function form( $instance ) {
		// Set up default widget settings.
		$defaults = array( 'before_widget_content' => '', 'after_widget_content' => '', 'hide_for_logged_in' => '1' );

		foreach ( $instance as $key => $value ) {
			$instance[ $key ] = esc_attr( $value );
		}

		$instance = wp_parse_args( ( array ) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'before_widget_content' ); ?>"><?php _e( 'Before widget content:', 'LoginRadius' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'before_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'before_widget_content' ); ?>" type="text" value="<?php echo $instance['before_widget_content']; ?>" />
			<label for="<?php echo $this->get_field_id( 'after_widget_content' ); ?>"><?php _e( 'After widget content:', 'LoginRadius' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'after_widget_content' ); ?>" name="<?php echo $this->get_field_name( 'after_widget_content' ); ?>" type="text" value="<?php echo $instance['after_widget_content']; ?>" />
			<br /><br />
			<label for="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>"><?php _e( 'Hide for logged in users:', 'LoginRadius' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_for_logged_in' ); ?>" name="<?php echo $this->get_field_name( 'hide_for_logged_in' ); ?>" type="text" value='1' <?php if ( $instance['hide_for_logged_in'] == 1 )  echo 'checked="checked"'; ?> />
		</p>
	<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "LoginRadius_Horizontal_Share_Widget" );' ) );
