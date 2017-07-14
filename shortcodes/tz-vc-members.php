<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Members_Addon {

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_members', array( $this, 'render_members' ) );
	}

	public function load_assets(){
		wp_enqueue_style('tz-members', TZWP_PLUGIN_URL . 'assets/css/members.css', true);
	}

	public function init(){

		$this->load_assets();

		if ( defined( 'WPB_VC_VERSION' ) ) {

			vc_map(array(
				"name" => esc_html__('Member Contacts', 'themeszone'),
				"base" => "tz_members",
				'category' => esc_html__('ThemesZone Shortcodes', 'themeszone'),
				"description" => esc_html__('Output Member Foto and social links', 'themeszone'),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/vc-icon.png',

				"params" => array(
					array(
						'type' => 'attach_image',
						'heading' => esc_html__('Member Image', 'themeszone'),
						'param_name' => 'member_img',
						'description' => esc_html__('Add Member Image', 'themeszone'),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Image size', 'themeszone'),
						'param_name' => 'member_img_size',
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
						'heading' => esc_html__('Team Member Name', 'themeszone'),
						'param_name' => 'member_name',
						'description' => esc_html__('Team Member Name', 'themeszone'),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__('Team Member Occupation', 'themeszone'),
						'param_name' => 'member_occupation',
						'description' => esc_html__('Team Member Occupation', 'themeszone'),
					),
					array(
						'type' => 'textarea',
						'heading' => esc_html__('Team Member Short Biography', 'themeszone'),
						'param_name' => 'member_biography',
						'value' => esc_html__('Short biography here', 'themeszone'),
						'description' => '',
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__('Buttons', 'themeszone'),
						'param_name' => 'buttons',
						'value' => urlencode(json_encode(array(
							array(
								'url_title' => esc_html__('Facebook', 'themeszone'),
								'url' => 'https://www.facebook.com/',
							),
							array(
								'url_title' => esc_html__('Twitter', 'themeszone'),
								'url' => 'https://twitter.com',
							),
							array(
								'url_title' => esc_html__('Google Plus', 'themeszone'),
								'url' => 'https://plus.google.com',
							),
						))),
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => esc_html__('Title', 'themeszone'),
								'param_name' => 'url_title',
								'description' => esc_html__('Enter Title', 'themeszone'),
								'admin_label' => true,
							),
							array(
								'type' => 'textfield',
								'heading' => esc_html__('URL', 'themeszone'),
								'param_name' => 'url',
								'description' => esc_html__('Enter URL for button', 'themeszone'),
								'admin_label' => true,
							),
							array(
								'type' => 'iconpicker',
								'heading' => esc_html__('Icon', 'themeszone'),
								'param_name' => 'icon',
								'value' => 'fa fa-facebook', // default value to backend editor admin_label
								'settings' => array(
									'emptyIcon' => false, // default true, display an "EMPTY" icon?
									'type' => 'fontawesome',
									'iconsPerPage' => 4000, // default 100, how many icons per/page to display
								),
								'description' => esc_html__('Select icon from library.', 'themeszone'),
							),
						),
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__('Predefined Styles', 'themeszone'),
						'param_name' => 'style',
						'value' => array(
							'Style 1' => 'style-1',
							'Style 2' => 'style-2',
						),
						'std' => 'full',
						'description' => '',
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
		}
	}

	function render_members($atts, $content = null){
		extract( shortcode_atts( array(
			'member_img' => '',
			'member_img_size' => 'full',
			'member_name' => '',
			'member_occupation' => '',
			'member_biography' => 'Short biography here',
			'style' => 'style-1',
			'css' => '',
			'buttons'=> '',
			'el_class'=> '',
		), $atts ) );

		$output = '';
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'tz-member-contact wpb_content_element ' . esc_attr($style) . ' ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), 'tz_members', $atts );

		// Get Foto
		$image_attributes = false;
		$img = '';
		$image_attributes = wp_get_attachment_image_src( $member_img, $member_img_size );
		if( $image_attributes ) {
			$img_alt = get_post_meta($member_img, '_wp_attachment_image_alt', true);
			$img = '<div class="contact-img-wrapper">
                <img alt="' . esc_attr($img_alt) . '" src="' . esc_url($image_attributes[0]) . '" width="' . esc_attr($image_attributes[1]) . '" height="' . esc_attr($image_attributes[2]) . '" />
              </div>';
		}
		// Get Social buttons
		$buttons_member = '';
		$button_attributes =  vc_param_group_parse_atts( $buttons );
		if($button_attributes){
			vc_icon_element_fonts_enqueue( 'monosocial' );
			$buttons_member .='<div class="contact-btns">';
			foreach ( $button_attributes as $data ) {
				$buttons_member.='<a href="'.esc_url($data['url']).'" target="_blank" rel="nofollow" title="'.( isset($data['url_title']) ? $data['url_title'] : esc_html__('Click here', 'plumtree') ).'"><i class="'.esc_attr($data['icon']).'"></i></a>';
			}
			$buttons_member .='</div>';
		}
		// Main Elements
		$heading = '';
		if ( $member_name ) {
			$heading = "<h3>{$member_name}</h3>";
		}
		$sub_heading = '';
		if ( $member_occupation ) {
			$sub_heading = "<span>{$member_occupation}</span>";
		}
		$short_bio = '';
		if ( $member_biography ) {
			$short_bio = "<p>{$member_biography}</p>";
		}

		// Shortcode output
		$output .= '<div class="'.$css_class.'">';

		$output .= $img;
		$output .= "<div class='text-wrapper'>";
		if ( $style == 'style-1' ) {
			$output .= $heading.$sub_heading.$short_bio.$buttons_member;
		} else {
			$output .= '<div class="h-wrapp">'.$heading.$sub_heading.$buttons_member.'</div>'.$short_bio;
		}


		$output .= "</div></div>";

		return $output;
	}

}

new TZ_VC_Members_Addon();