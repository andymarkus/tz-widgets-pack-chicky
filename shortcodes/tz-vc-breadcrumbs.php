<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Breadcrumbs_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_breadcrumbs', array( $this, 'render' ) );
	}

	function breadcrumbs_wrapper( $echo = true ){
		$breadcrumbs = '';
		if ( function_exists('yoast_breadcrumb') ) {
			$breadcrumbs = yoast_breadcrumb("<div class='nav-crumbs'>","</div>",false);
		} else return false;
		if ( $echo ) echo $breadcrumbs;
		return $breadcrumbs;
	}

	function bybe_crumb_v_fix ($link_output) {
		$link_output = preg_replace(array('#<span xmlns:v="http://rdf.data-vocabulary.org/\#">#','#<span typeof="v:Breadcrumb"><a href="(.*?)" .*?'.'>(.*?)</a></span>#','#<span typeof="v:Breadcrumb">(.*?)</span>#','# property=".*?"#','#</span>$#'), array('','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="$1" itemprop="url"><span itemprop="title">$2</span></a></span>','<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">$1</span></span>','',''), $link_output);
		return $link_output;
	}

	public function init(){

		add_filter ('wpseo_breadcrumb_output',array($this, 'bybe_crumb_v_fix'));

		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map(array(
					"name" => esc_html__('ThemesZone breadcrumbs', 'themeszone'),
					"base" => "tz_breadcrumbs",
					'category' => esc_html__('ThemesZone Shortcodes', 'themeszone'),
					"description" => esc_html__('Seo optimized breadcrumbs', 'themeszone'),
					'icon' => plugin_dir_url(__DIR__) . '/assets/img/breadcrumbs.png',
				)
			);
		}

	}

	public function render(){
		return '<div id="breadcrumbs">'.$this->breadcrumbs_wrapper(false).'</div>';
	}
}

new TZ_VC_Breadcrumbs_Addon();