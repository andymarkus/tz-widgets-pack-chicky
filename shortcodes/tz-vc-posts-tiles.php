<?php

if (!defined('ABSPATH')) die('-1');

class TZ_VC_Posts_Tiles_Addon{

	function __construct(){
		add_action( 'init', array($this, 'init') );
		add_shortcode( 'tz_tiled_posts', array( $this, 'render' ) );
	}

	public function load_assets(){
		wp_enqueue_style( 'tz-tiled-posts', TZWP_PLUGIN_URL . 'assets/css/tiled-posts.css');
	}

	public function get_latest_posts(){

		$args = array(
			'numberposts' => 20,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'include' => '',
			'exclude' => '',
			'meta_key' => '',
			'meta_value' =>'',
			'post_type' => 'post',
			'post_status' => 'draft, publish, future, pending, private',
			'suppress_filters' => true
		);

		$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

		$r_posts = array();

		foreach($recent_posts as $p){
			$r_posts[$p['post_title']] = $p['ID'];
		}
		return $r_posts;

	}

	public function init(){
		if ( is_front_page() ){
			$this->load_assets();
		}
		add_image_size( 'post-tiles', 290, 290, true );

		if ( defined( 'WPB_VC_VERSION' ) ) {
			vc_map( array(
				"name" => esc_html__( 'Tiled Blog Posts', 'themeszone' ),
				"base" => 'tz_tiled_posts',
				'category' => esc_html__( 'ThemesZone Shortcodes', 'themeszone'),
				"description" => esc_html__( 'Tiled Blog Posts for Home Page', 'themeszone' ),
				'icon' => plugin_dir_url(__DIR__) . '/assets/img/mosaic.png',
				"params" => array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title text', 'themeszone' ),
						'param_name' => 'el_title',
						'value' => __('Title goes here', 'themeszone'),
					),
					array(
						'type' => 'param_group',
						'heading' => esc_html__( 'Selected Posts', 'themeszone' ),
						'param_name' => 'selected_posts',

						'params' => array(
							array(
								'type' => 'dropdown',
								'heading' => esc_html__( 'Select Posts to show', 'themeszone' ),
								'param_name' => 'post_id',
								'description' => '',
								'value' => $this->get_latest_posts(),
							),
							array(
								'type' => 'colorpicker',
								'heading' => esc_html__( 'Text Color', 'themeszone' ),
								'param_name' => 'post_color',
								'description' => __('Text Color', 'themeszone'),
							),
							array(
								'type' => 'checkbox',
								'heading' => esc_html__( 'Hide Featured Image?', 'themeszone' ),
								'param_name' => 'hide_featured',
								'description' => __('Show / Hide Fetured Image', 'themeszone'),
							),

							array(
								'type' => 'checkbox',
								'heading' => esc_html__( 'Hide Post Excerpt?', 'themeszone' ),
								'param_name' => 'hide_text',
								'description' => __('Show / Hide Post Excerpt Text', 'themeszone'),
							)

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
			));


		}
	}

	public function render( $atts, $content = null ){

		extract( shortcode_atts( array(
			'el_title' => '',
			'selected_posts' => '',
			'css' => '',
			'el_class'=> '',
		), $atts ) );


		$this->load_assets();

		$posts_to_show = vc_param_group_parse_atts($selected_posts);

		$post_to_show_ids = array();

		foreach($posts_to_show as $post){
			$post_to_show_ids[] = (int) $post['post_id'];
		}

		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => 5,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post__in' => $post_to_show_ids
		) ) );

		$c = 0;
		$n = 0;
		$m = 0;
		if ($r->have_posts()) {

			$posts_output = '';
			$style = '';
			foreach($posts_to_show as $post_p) {
				if ( isset($post_p['post_color']) ) {
					$style .= ' #post-id-'.$post_p['post_id'].',
								#post-id-'.$post_p['post_id'].' a,
								#post-id-'.$post_p['post_id'].' span,
								#post-id-'.$post_p['post_id'].' div
								{
									color:'.$post_p['post_color'].'; 
								}';
				}
				if ( isset($post_p['hide_featured']) ) {
					$style .= ' #post-id-'.$post_p['post_id'].' .img-wrapper img {
						visibility: hidden;
					}';
				}

				if ( isset($post_p['hide_text']) ) {
					$style .= '
					#post-id-'.$post_p['post_id'].' .text-wrapper
					
					 {
						visibility: hidden;
					}';
				}

			}



			wp_add_inline_style('tz-tiled-posts',$style);

			if ( $el_title != '' ) $posts_output .= '<h3>'.esc_html($el_title).'</h3>';
			$posts_output .='<ul class="posts-tiles">';
			while ( $r->have_posts() ) {
				$c++;

				if ( ( ( 5 * $n ) + 3 ) == $c ){
					$class = 'mol';
					$n++;
				} elseif ( ( ( 5 * $m ) + 5 ) == $c ){
					$class = 'mol';
					$m++;
				} else {
					$class = '';
				}

				$r->the_post();

				$posts_output .= '<li id="post-id-'.get_the_ID().'" class="'.$class.'">';
				$posts_output .= '<div class="img-wrapper">';
				$posts_output .= '<a href="'.get_the_permalink().'">'.get_the_post_thumbnail(get_the_ID(), 'post-tiles').'</a>';
				$posts_output .= '</div>';
				if ( $class != 'mol' ) $posts_output .= '<span class="line"></span></li>';
				if ( $class != 'mol' ) $posts_output .= '<li>';
				$posts_output .= '<div class="text-wrapper">';
				$posts_output .= '<h3 class="entry-title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3>';
				$posts_output .= '<div class="related-post-meta"><span class="author">'.__('by', 'themeszone').' '.get_the_author().'</span> | <span class="related-posts-date">'.get_the_date().'</span></div>';
				$posts_output .= '<div class="related-post-content">'.$trimmed = wp_trim_words( get_the_content(), $num_words = 7, $more = '<a href="'.get_the_permalink().'"> ... </a>' ).'</div>';

				ob_start();
				do_action('chicky_related_posts_footer');
				$posts_footer = ob_get_contents();
				ob_end_clean();

				$posts_output .= $posts_footer;
				$posts_output .= '</div>';
				$posts_output .= '</li>';

			}
			$posts_output .= '</ul>';
		}



		$output = "<div class='tiled-container'>".$posts_output."</div>";

		return $output;

	}

}

new TZ_VC_Posts_Tiles_Addon();