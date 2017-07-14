<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Carousel_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_carousel', array( $this, 'render' ) );
	}

	function load_assets(){

		wp_enqueue_style( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/css/owl.min.css');
		wp_register_script( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true );
		wp_register_script( 'imagesloaded', TZWP_PLUGIN_URL . 'assets/js/imagesloaded.pkgd.min.js', true );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'tz-owl-carousel' );

	}

	function init(){
		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map(
				array(
					'name'        => __( 'ThemesZone Carousel', 'themeszone' ),
					'description' => __( 'Content Carousel, use image or text', 'themeszone' ),
					'base'        => 'tz_carousel',
					'class'       => '',
					'controls'    => 'full',
					'icon'        => plugin_dir_url(__DIR__) . '/assets/img/carousel.svg',
					'category' => esc_html__( 'ThemesZone Shortcodes', 'themeszone'),
					'params' => array(
						array(
							'type' => 'checkbox',
							'heading' => __( 'Loop Items', 'themeszone' ),
							'param_name' => 'loop',
							'description' => __( 'Check if you want your items to loop through.', 'themeszone' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Carousel Width', 'themeszone' ),
							'param_name' => 'carousel_width',
							'description' => __( 'Width of the slider container', 'themeszone' ),

						),

						array(
							'type' => 'textfield',
							'heading' => __( 'Item Width', 'themeszone' ),
							'param_name' => 'item_width',
							'description' => __( 'Width of the item in the carousel', 'themeszone' ),

						),

						array(
							'type' => 'textfield',
							'heading' => __( 'Margin', 'themeszone' ),
							'param_name' => 'margin',
							'description' => __( 'Margin between elements', 'themeszone' ),
						),
						array(
							'type' => 'textfield',
							'heading' => __( 'Speed', 'themeszone' ),
							'param_name' => 'speed',
							'description' => __( 'Slider speed', 'themeszone' ),

						),

						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Number of Items on Wide Screens', 'themeszone' ),
							'param_name' => 'wide_num',
							'value' => array(
								'6' => '6',
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',

							),
							'std'=> '6',
						),

						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Number of Items on Desktop', 'themeszone' ),
							'param_name' => 'desktop_num',
							'value' => array(
								'6' => '6',
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',

							),
							'std'=> '5',
						),

						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Number of Items on Tablet', 'themeszone' ),
							'param_name' => 'tablet_num',
							'value' => array(
								'6' => '6',
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',

							),
							'std'=> '3',
						),

						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Number of Items on Wide Mobile', 'themeszone' ),
							'param_name' => 'wide_mobile_num',
							'value' => array(
								'6' => '6',
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',

							),
							'std'=> '2',
						),

						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Number of Items on Mobile', 'themeszone' ),
							'param_name' => 'mobile_num',
							'value' => array(
								'6' => '6',
								'5' => '5',
								'4' => '4',
								'3' => '3',
								'2' => '2',
								'1' => '1',

							),
							'std'=> '1',
						),

						array(
							'type' => 'param_group',
							'heading' => esc_html__( 'Content Items', 'themeszone' ),
							'param_name' => 'items',

							'params' => array(
								array(
									'type' => 'textarea',
									'heading' => esc_html__( 'Text Content', 'themeszone' ),
									'param_name' => 'text_content',
									'description' => esc_html__( 'Items Content', 'themeszone' ),
								),
								array(
									'type' => 'attach_images',
									'heading' => __( 'Image', 'themeszone' ),
									'param_name' => 'image_content',
									'description' => __( 'Select image from media library for content.', 'themeszone' ),

								),
							),
						),
					)
				)
			);
		}
	}

	public function render( $atts ){
		extract( shortcode_atts( array(
			'loop' => 'false',
			'margin' => '10px',
			'speed' => '700',
			'items'=> '',
			'mobile_num' => '1',
			'wide_mobile_num' => '2',
			'tablet_num' => '3',
			'desktop_num' => '5',
			'wide_num' => '6',
			'carousel_width' => '',
			'item_width' => ''

		), $atts ) );


		$this->load_assets();

		$carousel_items = vc_param_group_parse_atts($items);

		$html = '';

		$the_id = uniqid('carousel-');

		if ( count($carousel_items) ) {
			$html .= '<div class="tz-carousel owl-carousel" id="'.$the_id.'">';

			foreach($carousel_items as $item){
				$content = '';
				if ( isset($item['image_content']) ) {
					$image_attributes = wp_get_attachment_image_src($item['image_content'], 'full');

					if( $image_attributes ) {
						$img_alt = get_post_meta($item['image_content'], '_wp_attachment_image_alt', true);
						$content .= '<img alt="' . esc_attr($img_alt) . '" src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" />';
					}

				}

				if ( isset($item['text_content']) ) $content .= '<div class="text-wrapp">'.$item['text_content'].'</div>';
				$html .= "<div class='item'>{$content}</div>";
			}

			$html .= '</div>';

			$script = "
				jQuery(document).ready(function () {
					
					var carousel = jQuery('#{$the_id}');
					jQuery(carousel).imagesLoaded().done( function( instance ) {
						carousel.owlCarousel({
							loop:{$loop},
	                        margin:{$margin},
	                        smartSpeed:{$speed},
	                        animateOut: 'fadeOut',
	                        animateIn: 'fadeIn',
	                        responsiveClass:true,
				            responsive:{
				                0:{
				                    items:{$mobile_num},
				                    nav:true
				                },
				                600:{
				                    items:{$wide_mobile_num},
				                    nav:true
				                },
				                1000:{
				                    items:{$tablet_num},
				                    nav:true,
				                },
				                1200:{
				                    items:{$desktop_num},
				                    nav:true,
				                    autoWidth:true,
				                },
				                1550:{
				                    items:{$desktop_num},
				                    nav:true,
				                    autoWidth:true,
				                },
				                1860:{
				                    items:{$wide_num},
				                    nav:true,
				                    autoWidth:true,
				                }
									}});
									
							});
	
					});
					
			";

			wp_add_inline_script('tz-owl-carousel', $script);

			return $html;

		}
	}

}

new TZ_VC_Carousel_Addon();