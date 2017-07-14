<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_WC_Home_Products_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_wc_home_products', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_enqueue_style( 'tz-home-products', TZWP_PLUGIN_URL . 'assets/css/home-products.css');
		wp_enqueue_style( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/css/owl.min.css');
		wp_register_script( 'tz-tabslet', TZWP_PLUGIN_URL . 'assets/js/jquery.tabslet.min.js', true );
		wp_register_script( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true );
		wp_register_script( 'tz-home-products', TZWP_PLUGIN_URL . 'assets/js/home-products.js', true );
		wp_enqueue_script( 'tz-owl-carousel' );
		wp_enqueue_script( 'tz-tabslet' );
		wp_enqueue_script( 'tz-home-products' );
	}

	public function init(){
		if ( is_front_page() ) {
			$this->load_assets();
		}

		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map( array(
				"name" => esc_html__( 'Home Page Products in Tabs', 'themeszone' ),
				"base" => 'tz_wc_home_products',
				'category' => esc_html__( 'ThemesZone Shortcodes', 'themeszone'),
				"description" => esc_html__( 'New and Featured Products Tabs for Home Page', 'themeszone' ),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/cart.png',
				'params' => array(
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Testimonials Style', 'themeszone'),
						'param_name' => 'prod_type',
						'value' => array(
							__('New and Featured Products', 'themeszone') => 'new_featured',
							__('Special and Best Sellers', 'themeszone') => 'special_best',
						),
					),
				),

			));
		}
	}

	public function render( $atts, $content = null ){
		$this->load_assets();

		extract(shortcode_atts(array(
			'prod_type' => 'new_featured'
		), $atts));

		$html = '<div class="tabs tz-home-products-tabs" data-toggle="tabslet" data-animation="true">';

		if ( isset($prod_type) && ($prod_type == 'new_featured')) {
		$html .= '<ul class=\'horizontal\'>
	        <li><a href="#new-products">'.__('New Products', 'themeszone').'</a></li><li><a href="#featured-products">'.__('Featured Products', 'themeszone').'</a></li>
	        </ul>';
			$html .= '<div id=\'new-products\' class="tz-home-products owl-carousel active">';
			$html .= do_shortcode('[recent_products per_page="6" columns="0"]');
			$html .= '</div>';
			$html .= '<div id=\'featured-products\' class="tz-home-products owl-carousel">';
			$html .= do_shortcode('[featured_products per_page="6" columns="0"]');
			$html .= '</div>';
		} else {
			$html .= '<ul class=\'horizontal\'>
	        <li><a href="#onsale-products">'.__('On Sale', 'themeszone').'</a></li><li><a href="#bestselling-products">'.__('Bestsellers', 'themeszone').'</a></li>
	        </ul>';
			$html .= '<div id=\'onsale-products\' class="tz-home-products owl-carousel active">';
			$html .= do_shortcode('[sale_products per_page="6" column="0"]');
			$html .= '</div>';
			$html .= '<div id=\'bestselling-products\' class="tz-home-products owl-carousel">';
			$html .= do_shortcode('[best_selling_products per_page="6" columns="0"]');
			$html .= '</div>';
		}
		$html .='</div>';
		return $html;
	}

}

new TZ_VC_WC_Home_Products_Addon();