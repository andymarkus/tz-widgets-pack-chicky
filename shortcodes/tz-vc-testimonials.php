<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_WC_Testimonials_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_testimonials', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_register_script( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true );
		wp_enqueue_style( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/css/owl.min.css', true);
		wp_enqueue_style( 'tz-testimonials', TZWP_PLUGIN_URL . 'assets/css/testimonials.css', true);
		wp_enqueue_script( 'tz-owl-carousel' );
	}


	function init()
	{
		if ( class_exists( 'WPBakeryShortCode' ) ) :

			vc_map(array(
				"name" => esc_html__('ThemesZone Testimonials', 'themeszone'),
				"base" => "tz_testimonials",
				"description" => esc_html__('Output carousel with Testimonials', 'themeszone'),
				'category' => esc_html__('ThemesZone Shortcodes', 'themeszone'),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/test.png',

				"params" => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Title text', 'themeszone'),
						'param_name' => 'el_title',
						'value' => __('Title goes here', 'themeszone'),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Testimonials Style', 'themeszone'),
						'param_name' => 'testimonials_style',
						'value' => array(
							__('One wide testimonial', 'themeszone') => 'style_1',
							__('Two testimonials at once', 'themeszone') => 'style_2',
						),
						'std' => 'style_1',
					),
					/*array(
						'type' => 'dropdown',
						'heading' => esc_html__('Transition Type', 'chicky'),
						'param_name' => 'transition_type',
						'value' => array(
							'Fade' => 'fade',
							'Back Slide' => 'backSlide',
							'Go Down' => 'goDown',
							'Fade Up' => 'fadeUp',
						),
						'std' => 'fade',
					),*/
					array(
						'type' => 'checkbox',
						'heading' => esc_html__('Autoplay', 'themeszone'),
						'param_name' => 'autoplay',
						'description' => esc_html__('Whether to running your carousel automatically or not', 'themeszone'),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__('Show Arrows', 'themeszone'),
						'param_name' => 'show_arrows',
						'description' => esc_html__('Show/hide arrow buttons', 'themeszone'),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__('Show Page Navigation', 'themeszone'),
						'param_name' => 'page_navi',
						'description' => esc_html__('Show/hide navigation buttons under your carousel', 'themeszone'),
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__('Testimonials Items', 'themeszone'),
						'param_name' => 'testimonials_items',
						'value' => urlencode(json_encode(array(
							array(
								'name' => esc_html__('Name', 'themeszone'),
								'occupation' => esc_html__('Occupation', 'themeszone'),
								'content_text' => esc_html__('Text', 'themeszone'),
							),

						))),
						'params' => array(
							array(
								'type' => 'attach_image',
								'heading' => esc_html__(' Image', 'themeszone'),
								'param_name' => 'image',
								'description' => esc_html__('Add Image', 'themeszone'),
							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__('Image size', 'themeszone'),
								'param_name' => 'img_size',
								'value' => array(
									'Thumbnail' => 'thumbnail',
									'Medium' => 'medium',
									'Large' => 'large',
									'Full' => 'full',
								),
								'std' => 'full',
								'description' => esc_html__("Enter image size. You can change these images' dimensions in wordpress media settings.", 'themeszone'),
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__('Name', 'themeszone'),
								'param_name' => 'name',
								'description' => esc_html__('Enter Name', 'themeszone'),

							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__('Occupation', 'themeszone'),
								'param_name' => 'occupation',
								'description' => esc_html__('Enter Occupation', 'themeszone'),

							),
							array(
								'type' => 'textarea',
								'heading' => esc_html__('Content Text', 'themeszone'),
								'param_name' => 'content_text',
								'description' => esc_html__('Set content of element', 'themeszone'),

							),
							array(
								'type' => 'dropdown',
								'heading' => esc_html__('Add star rating to testimonial', 'themeszone'),
								'param_name' => 'rating_value',
								'value' => array(
									'5 Stars' => '5',
									'4 Stars' => '4',
									'3 Stars' => '3',
									'2 Stars' => '2',
									'1 Star' => '1',
								),
								'std' => '5',
								'dependency' => array(
									'element' => 'testimonials_style',
									'value' => array('style_2'),
								),
							),
						),
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__('Extra class name', 'themeszone'),
						'param_name' => 'el_class',
						'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'themeszone'),
					),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__('CSS box', 'themeszone'),
						'param_name' => 'css',
						'group' => esc_html__('Design Options', 'themeszone'),
					),
				)
			));
		endif;

	}

	function render($atts, $content = null)
	{

		$this->load_assets();

		extract(shortcode_atts(array(
			'el_title' => 'Title Here',
			'transition_type' => 'fade',
			'autoplay' => 'false',
			'show_arrows' => '',
			'page_navi' => 'false',
			'testimonials_items' => '',
			'css' => '',
			'el_class' => '',
			'testimonials_style' => 'style_1'
		), $atts));

		$output = '';
		$carousel_content = '';
		$testimonials_items_content = vc_param_group_parse_atts($testimonials_items);
		$container_id = uniqid('owl', false);
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'tz-testimonials wpb_content_element ' . $el_class . vc_shortcode_custom_css_class($css, ' '), 'tz_testimonials', $atts);

		if ($testimonials_items_content) {
			foreach ($testimonials_items_content as $item) {
				$carousel_content .= '<div class="carousel-item">';
				$carousel_content .= '<div class="item-wrapper">';
				if (array_key_exists('image', $item)) {
					$image_attributes = wp_get_attachment_image_src($item['image'], $item['img_size']);
					$img_alt = get_post_meta($item['image'], '_wp_attachment_image_alt', true);
					$carousel_content .= '<div class="img-wrapper"><img alt="' . esc_attr($img_alt) . '" src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" /></div>';
				}
				$carousel_content .= '<div class="text-wrapper">';

				if (array_key_exists('name', $item) && ($testimonials_style == 'style_2') ) {
					$carousel_content .= '<h3>' . $item['name'] . '</h3>';
				}
				if (array_key_exists('occupation', $item) && ($testimonials_style == 'style_2') ) {
					$carousel_content .= '<span class="occupation">' . $item['occupation'] . '</span>';
				}

				if ($testimonials_style == 'style_2') {
					$width = absint($item['rating_value'] * 2);
					$carousel_content .= '<div class="star-rating"><span style="width:' . $width . '0%"></span></div>';
				}
				if (array_key_exists('content_text', $item)) {
					$carousel_content .= '<p><q>' . $item['content_text'] . '</q></p>';
				}

				if (array_key_exists('name', $item) && ($testimonials_style == 'style_1') ) {
					$carousel_content .= '<h3>' . $item['name'] . '</h3>';
				}
				if (array_key_exists('occupation', $item) && ($testimonials_style == 'style_1') ) {
					$carousel_content .= '<span class="occupation">' . $item['occupation'] . '</span>';
				}

				$carousel_content .= '</div>';

				$carousel_content .= '</div>';
				$carousel_content .= '</div>';
			}
		}

		$output .= '<div class=" owl-carousel ' . $css_class . '" id="' . $container_id . '">';
		$output .= "<div class='title-wrapper'><h3>{$el_title}</h3>";
		if ($show_arrows) {
			$output .= "<span class='prev'></span><span class='next'></span>";
		}
		$output .= "</div><div class='carousel-container {$testimonials_style}'>";
		$output .= $carousel_content;
		$output .= "</div></div>";

		$items_displayed = ( $testimonials_style == 'style_1' ) ? '1' : '2';
		$animate_in = ( $testimonials_style == 'style_1' ) ? 'fadeIn' : '';
		$animate_out = ( $testimonials_style == 'style_1' ) ? 'fadeOut' : '';
		$margin = ( $testimonials_style == 'style_1' ) ? '0' : '20';
		$single_item = ( $testimonials_style == 'style_1' ) ? 'true' : 'false';

		$output .= '<script type="text/javascript">
					(function($) {
						$(document).ready(function() {
							var owl = $("#' . $container_id . ' .carousel-container");

							owl.owlCarousel({
				              nav : true,
				              dots : '.$page_navi.',
				              autoPlay   : '.$autoplay.',
				              smartSpeed : 700,
				              singleItem : '.$single_item.',
				              animateOut : "'.$animate_out.'",
				              animateIn : "'.$animate_in.'",
				              items: '.$items_displayed.',
				              margin:'.$margin.',
				              responsive:{
		                        0:{
		                            items:1,
		                            nav:true
		                        },
		                        600:{
		                            items:2,
		                            nav:true
		                        },
		                        1000:{
		                            items:'.$items_displayed.',
		                            
		                        },
		                        1200:{
		                            items: '.$items_displayed.',
		                        },

                              }
				              
				            });

							// Custom Navigation Events
							$("#' . $container_id . '").find(".next").click(function(){
								owl.trigger("owl.next");
							})
							$("#' . $container_id . '").find(".prev").click(function(){
								owl.trigger("owl.prev");
							})
						});
					})(jQuery);
				</script>';

		return $output;
	}

}

new TZ_VC_WC_Testimonials_Addon();


