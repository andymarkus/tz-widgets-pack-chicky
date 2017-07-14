<?php
/**
 * Themes Zone Search Widget
 *
 * Configurable search widget, set custom input text and submit button text.
 *
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TZ_Search_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'tz_search_widget', // Base ID
			__('Themes Zone Header Search', 'themeszone'), // Name
			array('description' => __( "Header Search Widget", "themeszone" ), )
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => __('Search Field', 'themeszone'),
			'search-input' => __('Text here...', 'themeszone'),
			'search-button' => __('Find', 'themeszone')
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$product_search = isset( $instance['product_search'] ) ? (bool) $instance['product_search'] : false;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title: ', 'themeszone' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Input Text: ', 'themeszone' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-input') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-input') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-input'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Button Title Text: ', 'themeszone' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-button') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-button') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-button'] ); ?>" />
		</p>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<p><input class="checkbox" type="checkbox"<?php checked( $product_search ); ?> id="<?php echo $this->get_field_id( 'product_search' ); ?>" name="<?php echo $this->get_field_name( 'product_search' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'product_search' ); ?>"><?php _e( 'Product Search Only?' ); ?></label></p>
		<?php endif; ?>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search-input'] = strip_tags( $new_instance['search-input'] );
		$instance['search-button'] = strip_tags( $new_instance['search-button'] );
		$instance['product_search'] = isset( $new_instance['product_search'] ) ? (bool) $new_instance['product_search'] : false;

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$text = ( isset($instance['search-input']) ? $instance['search-input'] : 'Text here...' );
		$button = ( isset($instance['search-button']) ? $instance['search-button'] : 'Find' );
		$product_search = isset( $instance['product_search'] ) ? $instance['product_search'] : false;
		echo $before_widget;
		if ($title) { echo $before_title . $title . $after_title; }
		?>

		<span class="show-search" title="<?php _e('Click to show search-field', 'themeszone'); ?>"></span>
		<div id="tz-searchform-container">
			<form class="tz-searchform" method="get" action="<?php echo esc_url( home_url() ); ?>">
				<input id="s" name="s" type="text" class="searchtext" value="" title="<?php echo esc_attr( $text ); ?>" placeholder="<?php echo esc_attr( $text ); ?>" tabindex="1" />
				<input id="searchsubmit" type="submit" class="search-button" value="<?php echo esc_attr( $button ); ?>" title="<?php _e('Click to search', 'themeszone'); ?>" tabindex="2" />
			<?php if ( $product_search ) : ?>
				<input type="hidden" name="post_type" value="product" />
			<?php endif; ?>
			</form>
		</div>


		<?php
		echo $after_widget;
	}
}