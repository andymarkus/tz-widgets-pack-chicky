<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Sortable_Gallery_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_sortable_gallery', array( $this, 'render' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'init' , array($this, 'tz_add_tags_to_attachments') );
	}

	function tz_add_tags_to_attachments() {
		register_taxonomy_for_object_type( 'post_tag', 'attachment' );
	}

	public function init(){

		add_image_size( 'gallery', 786, '' );

		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map(
				array(
					'name'        => __( 'ThemesZone Gallery', 'themeszone' ),
					'description' => __( 'Sortable Masonry Media Gallery with filters', 'themeszone' ),
					'base'        => 'tz_sortable_gallery',
					'class'       => '',
					'controls'    => 'full',
					'icon'        => plugin_dir_url(__DIR__) . '/assets/img/sort_gallery.svg',
					'category' => esc_html__( 'ThemesZone Shortcodes', 'themeszone'),
					'params' => array(
						array(
							'type' => 'attach_images',
							'heading' => __( 'Images', 'themeszone' ),
							'param_name' => 'ids',
							'description' => __( 'Select images from media library.', 'themeszone' ),

						),
						array(
							'type' => 'dropdown',
							'heading' => __('Display Mode', 'themeszone'),
							'param_name' => 'mode',
							'default'   => 'full',
							'value' => array(
								__('All at once', 'themeszone') => 'full',
								__('By row in slider', 'themeszone') => 'slider',
							)
						),

						array(
							'type' => 'dropdown',
							'heading' => __('Item Layout', 'themeszone'),
							'param_name' => 'design',
							'default'   => 'modern',
							'value' => array(
								__('Modern', 'themeszone') => 'modern',
								__('Classic', 'themeszone') => 'classic',
							)
						),

						array(
							'type' => 'dropdown',
							'heading' => __( 'Show Gallery Filters?', 'themeszone' ),
							'dependency' => array(
									'element' => 'mode',
									'value' => 'full'
							),
							'param_name' => 'filters',
							'value' => array(
								__( 'Yes', 'themeszone' ) => 'on',
								__( 'No', 'themeszone' ) => 'off',
							),
							'description' => __( 'Choose whether to show filters or not.', 'themeszone' )
						),

						array(
							'type' => 'dropdown',
							'heading' => __( 'Gallery Sort Order', 'themeszone' ),
							'param_name' => 'sort',
							'value' => array(
								__( 'Ascending', 'themeszone' ) => 'ASC',
								__( 'Descending', 'themeszone' ) => 'DESC',
							),
							'description' => __( 'Select Gallery Sort Order', 'themeszone' )
						),

						array(
							'type' => 'dropdown',
							'heading' => __( 'Number Images per Row', 'themeszone' ),
							'param_name' => 'columns',
							'value' => array(
								__( 'Two', 'themeszone' ) => 2,
								__( 'Three', 'themeszone' ) => 3,
								__( 'Four', 'themeszone' ) => 4,
								__( 'Five', 'themeszone' ) => 5,
							),
							'description' => __( 'Select Number of Images per Row', 'themeszone' )
						),

						array(
							'type' => 'dropdown',
							'heading' => __( 'Gallery Layout Type', 'themeszone' ),
							'dependency' => array(
								'element' => 'mode',
								'value' => 'full'
							),
							'param_name' => 'layout',
							'value' => array(
								__( 'Masonry', 'themeszone' ) => 'masonry',
								__( 'Fit Rows', 'themeszone' ) => 'fitRows',
								__( 'Vertical', 'themeszone' ) => 'vertical',
							),
							'description' => __( 'Select Gallery Layout Type', 'themeszone' )
						)
					)
				)
			);
		}

	}

	public function render( $attr ){

		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$output = apply_filters( 'tz_sortable_gallery', '', $attr, $instance );
		if ( $output != '' ) {
			return $output;
		}

		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => $html5 ? 'div'     : 'dl',
			'columns'    => 3,
			'size'       => 'gallery',
			'include'    => '',
			'exclude'    => '',
			'filters'    => 'on',
			'layout'     => 'masonry',
			'mode'       => 'full',
			'design'     => 'modern',
		), $attr, 'gallery' );

		$this->load_assets( $atts['mode'] );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape( $atts['itemtag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );
		if ( ! isset( $valid_tags[ $itemtag ] ) ) {
			$itemtag = 'dl';
		}

		$columns = intval( $atts['columns'] );
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$gallery_style = '';

		/**
		 * Filters whether to print default gallery styles.
		 *
		 * @since 3.1.0
		 *
		 * @param bool $print Whether to print default gallery styles.
		 *                    Defaults to false if the theme supports HTML5 galleries.
		 *                    Otherwise, defaults to true.
		 */
		if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {
			$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item-container {
				float: {$float};
				margin-top: 10px;
				text-align: right;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
		}
		$design_mode = $atts['design'] ? $atts['design'] : 'modern';
		$size_class = sanitize_html_class( $atts['size'] );
		$gallery_div = "<div id='$selector' class='gallery ".( $atts['mode'] == 'slider' ? 'owl-carousel' : '' )." sortable-gallery {$design_mode} galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'
		data-cols='{$columns}' data-mode='{$atts['mode']}' data-layout='{$atts['layout']}'>";

		$tags = array();

		foreach ( $attachments as $att_id => $attachment ) {
			$img_tags = wp_get_post_tags( $att_id, 'post_tag' );
			foreach( $img_tags as $i_tag ) {
				$tags[] = $i_tag->name;
			}
		}

		$tags = array_unique($tags);

		if ( count($tags) && ( $atts['mode'] != 'slider' ) && ( $atts['filters'] == 'on' ) ) {
			$filters_output = '<label for="gallery-filters">'.__('Sort Gallery', 'themeszone').'</label>';
			$filters_output .= '<select id="gallery-filters" class="gallery-filters">
			<option value="*">'.__('All', 'themeszone').'</option>';

			foreach($tags as $tag){
				$filters_output .= '<option value=".'.$tag.'">'.$tag.'</option>';
			}

			$filters_output .= '</select>';

			$output .= $filters_output;

		}
		$output .= apply_filters( 'gallery_style', $gallery_style . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {
			$item_tags = '';
			$it_tags = wp_get_post_tags( $id, 'post_tag' );
			foreach( $it_tags as $i_tag ) {
				$item_tags .= $i_tag->name.' ';
			}

			$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';

			$excerpt = trim( $attachment->post_excerpt );

			$image_meta  = wp_get_attachment_metadata( $id );

			$attr = array('class' => 'gallery-item-image');

			$image_media_page = get_attachment_link($id);
			$image_src = wp_get_attachment_image_src( $id, 'full' );
			$image_src = $image_src[0];
			$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );

			$orientation = '';
			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
				$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
			}

			if ( $design_mode == 'classic' ) {

				$output .= "
				<{$itemtag} class='gallery-item {$design_mode} {$item_tags}'>
						<div class='gallery-content'>
							
							<figure class='gallery-item-figure'>
								{$image_output}
								<figcaption class='gallery-item-caption'>";

				$output .= "<p class='gallery-item-description' >".$excerpt."</p>";
				$output .= "<p class='gallery-item-links'>
					<a class='popup' href='{$image_src}'></a>
					<a class='post-link' href='{$image_media_page}'></a>
					</p>";
				$output	.="</figcaption></figure>
						</div>
					</{$itemtag}>";

			} else {

				$output .= "
				<{$itemtag} class='gallery-item {$design_mode} {$item_tags}'>
						<div class='gallery-content'>
							
							<figure class='gallery-item-figure'>
								<a class='popup' href='{$image_src}'>{$image_output}
								<div class='gallery-item-deco gallery-item-deco-shine'><div></div></div>
								<div class='gallery-item-deco gallery-item-deco-overlay'></div>
								</a>
								<figcaption class='gallery-item-caption'>";
								if (trim($attachment->post_excerpt) ) {
									$output .= "<h3 class='gallery-item-title'>" . wptexturize($attachment->post_excerpt) . "</h3>";
								}
									$output .= "<p class='gallery-item-description' ><a href='{$image_media_page}'>".__('Details', 'themeszone')."</a></p>";

					$output .= "</figcaption>
								
							</figure>
						</div>
					</{$itemtag}>";
			}

			if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
				$output .= '<br style="clear: both" />';
			}
		}

		if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
			$output .= "
			<br style='clear: both' />";
		}

		$output .= "
		</div>\n";



		return $output;

	}

	public function load_assets( $mode = 'full' ){
		wp_enqueue_style( 'tz-gallery-style', TZWP_PLUGIN_URL . 'assets/css/component.css');
		wp_enqueue_style( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/css/owl.min.css');
		wp_enqueue_style( 'tz-gallery-popup', TZWP_PLUGIN_URL . 'assets/css/magnific-popup.css');

		wp_register_script( 'tz-gallery-img-loaded', TZWP_PLUGIN_URL . 'assets/js/imagesloaded.pkgd.min.js', true );
		wp_register_script( 'tz-gallery-popup', TZWP_PLUGIN_URL . 'assets/js/jquery.magnific-popup.min.js', true );
		wp_register_script( 'tz-gallery-anime', TZWP_PLUGIN_URL . 'assets/js/anime.min.js', true );
		wp_register_script( 'tz-gallery-main', TZWP_PLUGIN_URL . 'assets/js/main.js', true );
		wp_register_script( 'tz-gallery-isotope', TZWP_PLUGIN_URL . 'assets/js/isotope.min.js', true );
		wp_register_script( 'tz-gallery-shortcode', TZWP_PLUGIN_URL . 'assets/js/gallery.js', true );
		wp_register_script( 'tz-owl-carousel', TZWP_PLUGIN_URL . 'assets/js/owl.carousel.min.js', true );

		wp_enqueue_script( 'tz-gallery-img-loaded' );
		wp_enqueue_script( 'tz-gallery-popup' );
		wp_enqueue_script( 'tz-gallery-anime' );
		wp_enqueue_script( 'tz-gallery-main' );

		if ( $mode == 'slider' )
			wp_enqueue_script( 'tz-owl-carousel' );
		else
			wp_enqueue_script( 'tz-gallery-isotope' );

		wp_enqueue_script( 'tz-gallery-shortcode' );

	}

	public function show_vc_version_notice() {
		$plugin_data = get_plugin_data(__FILE__);
		echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
	}

}

new TZ_VC_Sortable_Gallery_Addon();