<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TZ_Banner_Widget extends WP_Widget {

	private $version;

	private function sanitize_url( $url ) {

		// Sanitize the incoming string
		$url = strip_tags( $url );

		// If the URL doesn't begin with a protocol, default to http
		if ( ! preg_match( "~^(?:f|ht)tps?://~i", $url ) ) {
			$url = "http://" . $url;
		}

		$url = esc_url( trailingslashit( $url ) );

		// if, after all of this, we still don't have a valid URL, return an empty string
		return ( filter_var( $url, FILTER_VALIDATE_URL ) ) ? $url : '';

	}

	public function __construct() {

		$this->version = '1.0.0';
		$this->slug = 'tz_banner_widget';
		parent::__construct(
			'tz_banner_widget', // Base ID
			__('Themes Zone Banner Widget', 'themeszone'), // Name
			array('description' => __( "Widget to display banner in the sidebar", "themeszone" ), )
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	public function form($instance){

		$image = isset( $instance['image_url'] ) ? $instance['image_url'] : '';
		$text = isset($instance['banner_text']) ? $instance['banner_text'] : '';
		?>
		<div id="tz-banner-widget-container">
			<div class="tz-image-preview-container">
				<?php if ( $image ) : ?>
					<img src="<?php echo esc_url($image) ?>" alt="" />
				<?php endif;  ?>
			</div>
			<input class="hidden" type="text" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" value="<?php echo esc_attr( $image ); ?>" />
			<div class="image-upload-container">
				<a class="button <?php echo ($image) ? 'hidden' : '';  ?> media-button button-large image-upload" id="<?php echo $this->get_field_id( 'image_url' ); ?>">
					<?php _e( 'Upload Image', 'themeszone' ); ?>
				</a>
				<a class="image-delete button <?php echo (!$image) ? 'hidden' : '';  ?>" href="#">
					<?php _e('Remove this image', 'themeszone'); ?>
				</a>
			</div>

		</div>
		<p>
			<label for="<?php echo $this->get_field_id( 'banner_text' ); ?>"><?php _e( 'Content:' ); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('banner_text'); ?>" name="<?php echo $this->get_field_name('banner_text'); ?>"><?php echo esc_textarea( $text ); ?></textarea>
		</p>
		<?php

	}

	private function get_version() {
		return $this->version;
	}

	private function get_widget_slug() {
		return $this->widget_slug;
	}

	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$image = isset( $instance['image_url'] ) ? $instance['image_url'] : '';
		$widget_text = ! empty( $instance['banner_text'] ) ? $instance['banner_text'] : '';
		$text = apply_filters( 'widget_banner', $widget_text, $instance, $this );
		?>

		<?php if ( $image || $widget_text ) : ?>
			<div class="tz-banner-widget" >
				<img src="<?php echo esc_url($image); ?>" alt="" />
				<div class="banner-cont"><div class="banner-inner"><?php echo !empty( $instance['banner_text'] ) ? wpautop( $text ) : $text; ?></div></div>
			</div>
		<?php endif;

	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['image_url'] = ( ! empty( $new_instance['image_url'] ) ) ? $new_instance['image_url'] : '';
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['banner_text'] = $new_instance['banner_text'];
		} else {
			$instance['banner_text'] = wp_kses_post( $new_instance['banner_text'] );
		}

		return $instance;

	}

	public function enqueue_admin_scripts() {

		wp_enqueue_media();

		wp_enqueue_script('tz-banner-widget-helper', plugin_dir_url(__DIR__) . 'assets/js/banner.js', array('jquery'));

		wp_enqueue_style('tz-image-menu-widget-style', plugin_dir_url(__DIR__) . 'assets/css/widget.css');

	}

}