<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Banner_Addon{
	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_banner', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_enqueue_style('tz-banner', TZWP_PLUGIN_URL . 'assets/css/banner.css', true);
	}

	public function init(){

		$this->load_assets();

		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map(array(
				"name" => esc_html__('Home Page Banner', 'themeszone'),
				"base" => "tz_banner",
				'category' => esc_html__('ThemesZone Shortcodes', 'themeszone'),
				"description" => esc_html__('Shows Home Page banner', 'themeszone'),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/banner.png',
				'params' => array(
					array(
						'type' => 'attach_image',
						'heading' => esc_html__('Banner Image', 'themeszone'),
						'param_name' => 'banner_img',
						'description' => esc_html__('Add Banner Image', 'themeszone'),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Image size', 'themeszone'),
						'param_name' => 'banner_img_size',
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
						'type' => 'textarea_html',
						'holder' => 'div',
						'heading' => esc_html__('Banner Text', 'themeszone'),
						'param_name' => 'banner_content',
						'value' => '',
						'description' => esc_html__('Banner HTML Content', 'themeszone'),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('URL', 'themeszone'),
						'param_name' => 'url',
						'description' => esc_html__('Enter URL for banner', 'themeszone'),
						'admin_label' => true,
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Predefined Styles', 'themeszone'),
						'param_name' => 'style',
						'value' => array(
							'Style 1' => 'style-1',
							'Style 2' => 'style-2',
							'Style 3' => 'style-3',
							'Style 4' => 'style-4',
							'Style 5' => 'style-5',

						),
						'std' => 'style-1',
						'description' => '',
					),

					array(
						'type' => 'colorpicker',
						'heading' => esc_html__( 'Text Color', 'themeszone' ),
						'param_name' => 'text_color',
						'description' => __('Text Color', 'themeszone'),
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__('Button Title', 'themeszone'),
						'param_name' => 'button_title',
						'description' => esc_html__('Text for the banner button', 'themeszone'),
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

			)
			);
		}

	}

	public function render($atts, $content = null){
		extract( shortcode_atts( array(
			'url' => '',
			'banner_img' => '',
			'banner_img_size' => 'full',
			'banner_content' => '',
			'style' => 'style-1',
			'css' => '',
			'button_title' => '',
			'el_class'=> '',
			'text_color' => '#000'
		), $atts ) );



		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'tz-hp-banner wpb_content_element ' . esc_attr($style) . ' ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), 'tz_banner', $atts );

		// Get Foto
		$image_attributes = false;
		$img = '';
		$image_attributes = wp_get_attachment_image_src( $banner_img, $banner_img_size );
		if( $image_attributes ) {
			$img_alt = get_post_meta($banner_img, '_wp_attachment_image_alt', true);
			$img = '<div class="banner-img-wrapper"><img alt="' . esc_attr($img_alt) . '" src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" /></div>';
		}

		if ( $button_title != '' ) $button = "<a href=\"{$url}\" class=\"button\">{$button_title}</a>";

		$html = '';
		$banner_content = trim(preg_replace('/\s\s+/', ' ', preg_replace('~</?p[^>]*>~', '', $banner_content)));
		$html .= ( ( $button_title == '') ? "<a href=\"{$url}\">":"" )."<div style=\"color:{$text_color}\"  class=\"{$css_class}\">{$img}<div class=\"banner-content-wrapper\"><div class=\"border-anim\" style=\"color:{$text_color};\">{$banner_content}</div></div>".(isset($button) ? $button : "" )."</div>".( ( $button_title == '') ? "</a>":"" );

		return trim(preg_replace('/\s\s+/', ' ', $html));;

	}
}

new TZ_VC_Banner_Addon();