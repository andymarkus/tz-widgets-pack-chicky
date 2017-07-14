<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 27/09/2016
 * Time: 12:34
 */

class TZ_Widget_Recent_Comments extends WP_Widget{
	/**
	 * Sets up a new Recent Comments widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array (
			'classname' => 'widget_tz_recent_comments',
			'description' => __( 'Recent Comments with Post Images', 'themeszone' )
		);
		parent::__construct (
			'recent-tz-comments',
			__( 'Themes Zone Recent Comments', 'themeszone' ),
			$widget_ops
		);
		$this->alt_option_name = 'widget_tz_recent_comments';
	}

	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$crop_image = isset( $instance['crop_image'] ) ? $instance['crop_image'] : false;
		if ( $crop_image ) {
			$crop_image = 'themeszone-crop-image';
		}

		$style = ( ! empty( $instance['style'] ) ) ? esc_attr ( $instance['style'] ) : 'square';

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		) ) );

		if ($r->have_posts()) :
			?>
			<?php echo $args['before_widget']; ?>
			<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>

			<?php


			$com_number = ( ! empty( $instance['com_number'] ) ) ? absint( $instance['com_number'] ) : 5;
			if ( ! $com_number )
				$com_number = 5;

			$com_show_date = isset( $instance['com_show_date'] ) ? $instance['com_show_date'] : false;
			$com_excerpt = ( ! empty( $instance['com_excerpt'] ) ) ? absint( $instance['com_excerpt'] ) : 20;
			$com_style = ( ! empty( $instance['com_style'] ) ) ? esc_attr ( $instance['com_style'] ) : 'circle';



			$comments = get_comments( apply_filters( 'widget_comments_args', array(
				'number'      => $com_number,
				'status'      => 'approve',
				'post_status' => 'publish'
			) ) );

			$output ='';

			$output .= '<ul class="tzrpc-widget-list recent-comments-gravatars ' . $com_style . '">';
			if ( is_array( $comments ) && $comments ) {
				// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
				$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
				_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

				foreach ( (array) $comments as $comment ) {
					$output .= '<li class="recentcomments">';
					/* translators: comments widget: 1: comment author, 2: post link */
					$output .= '<div class="tzrpc-thumb-wrap">' . get_avatar ( $comment, 81 ) . '</div>';

					$output .= '<div class="tzrpc-content-wrap">';

					$output .= '<span class="com-by">'.sprintf( _x( 'by %1$s', 'themeszone' ),
						'<span class="comment-author-link">' . get_comment_author_link( $comment ) . '</span>'
					).'</span>';

					$output .= '<span class="com-in">'.sprintf( _x( 'in %1$s', 'themeszone' ), '<a href="' . esc_url( get_comment_link( $comment ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>' ).'</span>';

					if ( $com_excerpt > 0 ) :
						$content = get_comment_text ( $comment );
						$output .= '<p>' . wp_trim_words ( $content, $com_excerpt ) . '</p>';
					endif;

					$output .= '</div>';
					$output .= '</li>';
				}
			}
			$output .= '</ul>';


			echo $output;

			?>

			<?php echo $args['after_widget']; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );


		$instance['com_number'] 	= absint( $new_instance['com_number'] );
		$instance['com_show_date']	= isset( $new_instance['com_show_date'] ) ? (bool) $new_instance['com_show_date'] : false;
		$instance['com_style'] 		= isset( $new_instance['com_style'] ) ? esc_attr ( $new_instance['com_style'] ) : 'circle';
		$instance['com_excerpt']	= isset( $new_instance['com_excerpt'] ) ? absint( $new_instance['com_excerpt'] ) : 20;

		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title     	= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';


		$com_number = isset( $instance['com_number'] ) ? absint( $instance['com_number'] ) : 5;
		$com_show_date	= isset( $instance['com_show_date'] ) ? (bool) $instance['com_show_date'] : false;
		$com_style = isset( $instance['com_style'] ) ? esc_attr ( $instance['com_style'] ) : 'circle';
		$com_excerpt = isset( $instance['com_excerpt'] ) ? absint( $instance['com_excerpt'] ) : 20;


		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'themeszone'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'com_number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'com_number' ); ?>" name="<?php echo $this->get_field_name( 'com_number' ); ?>" type="number" step="1" min="1" value="<?php echo $com_number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'com_excerpt' ); ?>"><?php _e( 'Comments Excerpt length:' ); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'com_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'com_excerpt' ); ?>" type="number" step="1" min="0" max="100" value="<?php echo $com_excerpt; ?>" size="4" /></p>

		<p>
			<label for="<?php echo $this->get_field_id( 'com_style' ); ?>"><?php _e( 'Comments Style' ); ?></label>
			<?php $styles = array ( 'square' => 'Square', 'circle' => 'Circle' ); ?>
			<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'com_style' ); ?>">
				<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
				<?php foreach ( $styles as $key => $value ) : ?>

					<option value="<?php echo $key; ?>" <?php selected( $com_style, $key ); ?>>
						<?php echo esc_html( $value ); ?>
					</option>

				<?php endforeach; ?>
			</select>
		</p>


		<?php
	}

}