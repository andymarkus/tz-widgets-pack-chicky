<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TZ_Wishlist_Icon_Link' ) && class_exists( 'YITH_WCWL' ) ) :

class TZ_Wishlist_Icon_Link extends WP_Widget
{
	function __construct() {
		parent::__construct(
			'tz_wishlist_icon_link', // Base ID
			__( 'Wishlist Header Widget', 'themeszone' ), // Name
			array( 'description' => __( 'Themes Zone Wishlist Icon Link', 'themeszone' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		$wishlist_page_id = isset( $_POST['yith_wcwl_wishlist_page_id'] ) ? $_POST['yith_wcwl_wishlist_page_id'] : get_option( 'yith_wcwl_wishlist_page_id' );
		$wishlist_page_id = TZ_Helper::tz_wcwl_object_id( $wishlist_page_id );
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : __('Wishlist','themeszone') );

		if ( $wishlist_page_id ) {

			if ($title) { echo $args['before_title'] . $title . $args['after_title']; }

			$wishlist_link = get_permalink( $wishlist_page_id );

			$wishlist_link_text = ! empty( $instance['link_text'] ) ? $instance['link_text'] : '';

			$link_enabled = get_theme_mod('wishlist_enabled', "1");

			if ( $link_enabled == "1" )
			echo '<a href="'.$wishlist_link.'" class="wishlist-icon" id="wishlist-icon">'.$wishlist_link_text.'</a>';


		}

		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['link_text'] = $new_instance['link_text'];
		} else {
			$instance['link_text'] = wp_kses_post( $new_instance['link_text'] );
		}

		return $instance;
	}


	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'link_text' => __('Wishlist','themeszone') ) );

		?>
		<p><label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Link Text:', 'themesozne'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_textarea($instance['link_text']); ?>" /></p>

		<?php
	}

}

endif;