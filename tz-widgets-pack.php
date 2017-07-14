<?php
/*
Plugin Name: Themes Zone Widgets Pack
Plugin URI: https://themes.zone/blog
Description: A set of widgets
Version: 1.0.0
Author: Themes Zone
Author URI: https://themes.zone/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! defined( 'TZWP_PLUGIN_URL' ) ) {
	define( 'TZWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

require_once ( dirname( __FILE__ ) . '/lib/TZ_Helper.php' );

if ( defined(ABSPATH) )
require(ABSPATH . '/include/wp-load.php');

if ( !class_exists( 'TZ_Widgets_Pack' ) ) {

	class TZ_Widgets_Pack {

		private static $instance;

		public function __construct()
		{

		}

		private function __clone()
		{
		}

		public static function getInstance()
		{
			if (null === static::$instance) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		private function __wakeup()
		{
		}

		public function tz_activate_plugin(){

			add_settings_section(
				'tz_maintenance_section',
				__('Maintenance/Coming Soon Page', 'themeszone'),
				array($this, 'tz_maintenance_section_callback_function'),
				'general'
			);

			add_settings_field(
				'tz_maintenance_mode',
				__('Maintenance / Comming Soon', 'themeszone'),
				 array($this,'tz_maintenance_field_function'),
				'general',
				'tz_maintenance_section'
			);
			register_setting( 'general', 'tz_maintenance_mode' );

			add_settings_field(
				'tz_maintenance_mode_pre_text',
				__('Preceding text', 'themeszone'),
				array($this,'tz_maintenance_field_pre_text_function'),
				'general',
				'tz_maintenance_section'
			);

			register_setting( 'general', 'tz_maintenance_mode_pre_text' );

			add_settings_field(
				'tz_maintenance_mode_main_text',
				__('Main text', 'themeszone'),
				array($this,'tz_maintenance_mode_main_text_function'),
				'general',
				'tz_maintenance_section'
			);

			register_setting( 'general', 'tz_maintenance_mode_main_text' );

			add_settings_field(
				'tz_maintenance_mode_time',
				__('Launch Time', 'themeszone'),
				array($this,'tz_maintenance_mode_time_function'),
				'general',
				'tz_maintenance_section'
			);

			register_setting( 'general', 'tz_maintenance_mode_time' );

		}

		public function tz_maintenance_section_callback_function(){
			echo '<p>'.__('Maintenance Mode / Coming Soon Page settings', 'themeszone').'</p>';
		}

		public function tz_maintenance_field_function(){
			echo '<input name="tz_maintenance_mode" id="tz-maintenance-mode" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'tz_maintenance_mode' ), false ) . ' /> '.__('On/Off', 'themeszone');
		}

		public function tz_maintenance_field_pre_text_function(){

			$filtered_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'class' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'i' => array(
					'class' => array(),
					'aria-hidden' => array()
				),
				'ul' => array(),
				'li' => array()
			);

			echo '<textarea cols="60" rows="8" name="tz_maintenance_mode_pre_text" id="tz-maintenance-mode-pre-text"  class="code"/>'.wp_kses(get_option( 'tz_maintenance_mode_pre_text' ), $filtered_html).'</textarea> ';
		}

		public function tz_maintenance_mode_main_text_function(){
			echo '<input name="tz_maintenance_mode_main_text" id="tz-maintenance-mode-main-text" type="text" class="code" value="'.get_option( 'tz_maintenance_mode_main_text' ). '" /> ';
		}

		public function tz_maintenance_mode_time_function(){
			echo '<input name="tz_maintenance_mode_time" id="tz-maintenance-mode-time" type="text" class="code" value="'.get_option( 'tz_maintenance_mode_time' ). '" /> '.__('Set target date (YYYY-MM-DD)');
		}

		function tz_load_textdomain() {
			load_plugin_textdomain( 'themeszone', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function tz_maintenance_mode(){

			if ( get_option('tz_maintenance_mode') || ( (isset($_GET['_maintenance_mode_demo'])) && ( $_GET['_maintenance_mode_demo'] == 'true' ) ) ) {
				global $pagenow;
				if ( $pagenow !== 'wp-login.php' && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
					//header( 'HTTP/1.1 Service Unavailable', true, 503 );
					header( 'Content-Type: text/html; charset=utf-8' );
					if ( file_exists( plugin_dir_path( __FILE__ ) . 'views/maintenance.php' ) ) {
						require_once( plugin_dir_path( __FILE__ ) . 'views/maintenance.php' );
					}
					die();
				}
			}
		}

		public function tz_widgets_init()
		{

			require_once dirname( __FILE__ ) . '/widgets/header-cart-widget.php';
			require_once dirname( __FILE__ ) . '/widgets/header-search-widget.php';
			require_once dirname( __FILE__ ) . '/widgets/recent-posts.php';
			require_once dirname( __FILE__ ) . '/widgets/recent-comments.php';
			require_once dirname( __FILE__ ) . '/widgets/wishlist-icon-link.php';
			require_once dirname( __FILE__ ) . '/widgets/menu-image-widget.php';
			require_once dirname( __FILE__ ) . '/widgets/sidebar-banner.php';
			require_once dirname( __FILE__ ) . '/widgets/payment-methods.php';

			require_once dirname( __FILE__ ) . '/shortcodes/tz-social-share.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-post-like.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-post-view.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-sortable-gallery.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-portfolio.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-wc-specials.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-testimonials.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-members.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-banner.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-wc-home-products.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-tabbed-sidebar.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-posts-tiles.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-carousel.php';
			require_once dirname( __FILE__ ) . '/shortcodes/tz-vc-breadcrumbs.php';


			if ( class_exists( 'WooCommerce' ) ) register_widget ( 'TZ_Cart_Widget' );

			register_widget ( 'TZ_Search_Widget' );
			register_widget ( 'TZ_Widget_Recent_Posts');
			register_widget ( 'TZ_Widget_Recent_Comments');
			register_widget( 'TZ_Payment_Method_Widget' );
			if ( class_exists( 'YITH_WCWL' ) ) register_widget ( 'TZ_Wishlist_Icon_Link' );
			if ( class_exists( 'Mega_Menu' ) ) register_widget ( 'TZ_Image_Widget' );
			register_widget ( 'TZ_Banner_Widget' );

			add_filter('woocommerce_add_to_cart_fragments', 'themeszone_header_add_to_cart_fragment');
			add_image_size('themeszone-comments-post-thumbnails', 81, 81, true );

		}


	}

	$tz_widgets_pack = TZ_Widgets_Pack::getInstance();
	add_action( 'widgets_init', array( $tz_widgets_pack, 'tz_widgets_init' ) );
	add_action( 'admin_init', array( $tz_widgets_pack, 'tz_activate_plugin' ) );
	add_action( 'init', array( $tz_widgets_pack, 'tz_maintenance_mode' ) );
	add_action( 'init', array( $tz_widgets_pack, 'tz_load_textdomain' ) );


}