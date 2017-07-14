<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_WC_Specials_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_wc_specials', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_enqueue_style( 'tz-sales-carousel', TZWP_PLUGIN_URL . 'assets/css/sales-carousel.css');
		wp_register_script( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true );
		wp_register_script( 'tz-countdown', TZWP_PLUGIN_URL . 'assets/js/countdown.js', true );
		wp_enqueue_script('tz-owl-carousel');
		wp_enqueue_script('tz-countdown');
	}

	protected function get_sales_products(){
		$sale_ids = wc_get_product_ids_on_sale();
		$sale_products = array();
		foreach( $sale_ids as $id ) {
			$sale_products[get_the_title($id)] = $id;
		}
		return $sale_products;
	}

	public function init(){

		add_image_size('specials-slider', '1000', '99999', false);

		if ( defined( 'WPB_VC_VERSION' ) ) {

			vc_map( array(
				"name" => esc_html__( 'Sale Carousel', 'themeszone' ),
				"base" => 'tz_wc_specials',
				'category' => esc_html__( 'ThemesZone Shortcodes', 'themeszone'),
				"description" => esc_html__( 'Output carousel with sale products', 'themeszone' ),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/specials.svg',
				"params" => array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Enter Sale Product ID', 'themeszone' ),
					'param_name' => 'product_id',
					'description' => '',
					'value' => $this->get_sales_products(),
				),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__('Product Image', 'themeszone'),
					'param_name' => 'image',
					'description' => esc_html__('Add Image', 'themeszone'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Target Date', 'themeszone' ),
					'param_name' => 'target_date',
					'description' => esc_html__( 'Set target date (YYYY-MM-DD) when special offer ends', 'themeszone' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Pre-Countdown text', 'themeszone' ),
					'param_name' => 'pre_countdown_text',
					'description' => esc_html__( 'This text appears before countdown timer', 'themeszone' ),
				),

					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'themeszone' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'themeszone' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS box', 'themeszone' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'themeszone' ),
					),
				)
			 );

		}

	}

	function render( $atts, $content = null ) {

		$this->load_assets();

		//$this->get_sales_products();

		extract( shortcode_atts( array(
			'product_id' => '',
			'css' => '',
			'image' => '',
			'target_date' => '2017-12-31',
			'pre_countdown_text' => '',
			'el_class'=> '',
		), $atts ) );

		$output = '';
		$carousel_items = '';

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'tz_wc_specials', $atts );


			$carousel_items .= '<div class="sales-product">';

				if ( isset( $product_id ) ) {
					$product = new WC_Product( $product_id );
					if ( $product->is_on_sale() ) {
						if (isset($image)) {
							$image_attributes = wp_get_attachment_image_src($image, 'specials-slider');
							$img_sizes = wp_calculate_image_sizes(array(500, 99999), esc_url($image_attributes[0]));
							$img_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
							$special_image = '<img alt="' . esc_attr($img_alt) . '" src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" />';
						}
						$countdown_container_id = uniqid('countdown',false);
						if (isset($target_date)) $target = explode("-", $target_date);
						$carousel_items .= '<div class="img-wrapper">';
						$carousel_items .= '<a href="'.esc_url($product->get_permalink()).'" class="link-to-product">'.$special_image.'</a></div>';
						$carousel_items .= '<div class="counter-wrapper"><h4>'.esc_html($product->get_title()).'</h4>';
						// Sale value in percents
						$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
						//$carousel_items .= '<span class="sale-value">-'.$percentage.'%</span>';
						$carousel_items .= '<div class="countdown-wrapper">';

						$carousel_items .= '<div class="price-wrapper">'.$product->get_price_html().'</div>';

						$carousel_items .= '<div class="countdown-container" id="'.$countdown_container_id.'">';
						$carousel_items .='</div></div>';

						$carousel_items .= '<a class="shop-now" href="'.$product->get_permalink().'">'.__('Shop Now', 'themeszone').'</a>';

						if ( isset($target) && count($target) > 1 ) {
							$carousel_items .='
    					<script type="text/javascript">
    						(function($) {
    							$(document).ready(function() {

    								var container = $("#'.$countdown_container_id.'");
    								var newDate = new Date('.$target[0].', '.$target[1].'-1, '.$target[2].');
    								container.countdown({
    									until: newDate,
    								});

    							});
    						})(jQuery);
    					</script>';
						}
						$carousel_items .= '</div>';
						if ( isset( $pre_countdown_text ) ) {
							$carousel_items .= '<span class="pre-countdown-text">'.esc_html($pre_countdown_text).'</span>';
						}
					}
				}

			$carousel_items .= '</div>';


		// Output Carousel
		$output .= '<div class="tz-sales '.$el_class.$css_class.'" >';

		$output .= "<div class='wrapper'>";

		if ( $carousel_items && $carousel_items !='') {
			$output .= $carousel_items;
		} else {
			$output .= esc_html__('Add sale products first', 'themeszone');
		}
		$output .= "</div></div>";

		return $output;
	}

}

new TZ_VC_WC_Specials_Addon();