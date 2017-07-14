<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TZ_Image_Widget extends WP_Widget {

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

		parent::__construct(
			'tz_image_widget', // Base ID
			__('Themes Zone Menu Image Widget', 'themeszone'), // Name
			array('description' => __( "Widget to display images on the side of Mega Menu", "themeszone" ), )
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	public function form($instance){

		$image = isset( $instance['image_url'] ) ? $instance['image_url'] : '';
		$background = isset( $instance['background'] ) ? $instance['background'] : 'on';
		$link = isset( $instance['link'] ) ? $instance['link'] : '';
		?>
		<div id="tz-menu-image-widget-container">
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
				<a class="image-delete button <?php echo (!$image) ? 'hidden' : '';  ?>"
				   href="#">
					<?php _e('Remove this image', 'themeszone'); ?>
				</a>
			</div>
		</div>

		<?php

	}


	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		$image = isset( $instance['image_url'] ) ? $instance['image_url'] : '';
		$background = isset( $instance['background'] ) ? $instance['background'] : 'on';
		$link = isset( $instance['link'] ) ? $instance['link'] : '';

		?>

		<?php if ( $image ) : ?>
		<div class="tz-mm-image-widget menu-background" >
				<img src="<?php echo esc_url($image); ?>" alt="" />
		</div>
		<?php endif;

	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['image_url'] = ( ! empty( $new_instance['image_url'] ) ) ? $new_instance['image_url'] : '';
		$instance['background'] = ( ! empty( $new_instance['background'] ) ) ? $new_instance['background'] : 'off';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? $new_instance['link'] : '';

		return $instance;

	}

	public function enqueue_admin_scripts() {

		wp_enqueue_media();

		wp_enqueue_script('tz-image-menu-widget-helper', plugin_dir_url(__DIR__) . 'assets/js/helper.js', array('jquery'));

		wp_enqueue_style('tz-image-menu-widget-style', plugin_dir_url(__DIR__) . 'assets/css/widget.css');


	}




}