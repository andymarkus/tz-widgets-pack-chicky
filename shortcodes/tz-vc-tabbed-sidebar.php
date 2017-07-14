<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Tabbed_Sidebar_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_tabbed_content', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_enqueue_style( 'tz-tabbed-sidebar', TZWP_PLUGIN_URL . 'assets/css/tabbed-sidebar.css');
		wp_register_script( 'tz-tabslet', TZWP_PLUGIN_URL . 'assets/js/jquery.tabslet.min.js', true );
		wp_enqueue_script( 'tz-tabslet' );
	}

	function get_registered_sidebars(){
		global $wp_registered_sidebars;

		$sidebar_options = array();

		foreach ($wp_registered_sidebars as $sidebar){

			$sidebar_options[$sidebar['name']] = $sidebar['id'];

		}

		return $sidebar_options;
	}

	public function init(){
		if ( is_front_page() ) {
			$this->load_assets();
		}
		if ( defined( 'WPB_VC_VERSION' ) ) {

			vc_map(array(

				"name" => esc_html__('ThemesZone Tabbed Sidebar', 'themeszone'),
				"base" => "tz_tabbed_content",
				"description" => esc_html__('Tabbed Widgetised Sidebar', 'themeszone'),
				'category' => esc_html__('ThemesZone Shortcodes', 'themeszone'),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/tabbed.svg',

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
						'param_name' => 'sidebar',
						'value' => $this->get_registered_sidebars(),
					),
				)));
		}

	}

	public function render($atts, $content = null){

		$this->load_assets();

		extract( shortcode_atts( array(
			'el_title' => '',
			'sidebar' => ''
		), $atts ) );

		global $wp_registered_sidebars;

		$sidebars_widgets = get_option( 'sidebars_widgets' );

		if ( isset($sidebars_widgets[$sidebar]) )  $current_widgets = $sidebars_widgets[$sidebar];
		else return;

		if ( ! count( $current_widgets ) ) return;

		$sidebar_conf = isset($wp_registered_sidebars[$sidebar]) ? $wp_registered_sidebars[$sidebar] : '';

		if ( isset( $sidebar_conf['after_widget'] ) ) $tag = str_replace(array('</', '>'), '', $sidebar_conf['after_widget']);

		$before_title = isset( $sidebar_conf['before_title'] ) ? $sidebar_conf['before_title'] : '' ;

		$after_title = isset( $sidebar_conf['after_title'] ) ? $sidebar_conf['after_title'] : '';

		$output = '';

		$widgets_titles = array();

		if ( is_active_sidebar($sidebar) ) {

			ob_start();

			dynamic_sidebar($sidebar);

			$sidebar_content = ob_get_contents();

			ob_end_clean();

			preg_match_all('~'.$before_title.'(.*?)'.$after_title.'~', $sidebar_content, $widgets_titles);

			$sidebar_content = preg_replace('~'.$before_title.'(.*?)'.$after_title.'~', '', $sidebar_content);

			$output .= '<div class="tz-tabbed-sidebar" data-toggle="tabslet" data-autorotate="true" data-pauseonhover="true" data-delay="3000" data-animation="true" data-tab="'.$tag.'">
						<ul class="horizontal">';
			$i = 0;

			foreach($widgets_titles[1] as $title){

				$output .= '<li><a href="#'.$current_widgets[$i++].'">'.$title.'</a></li>';

			}

			$output .= '</ul>';

			$output .= $sidebar_content.'</div>';

			return $output;
		}


	}
}

new TZ_VC_Tabbed_Sidebar_Addon();