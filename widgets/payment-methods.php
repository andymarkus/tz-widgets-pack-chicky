<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class TZ_Payment_Method_Widget extends WP_Widget {

	private $version;
	private $slug;
	private function get_version() {
		return $this->version;
	}



	private $methods = array(
		'pw-clickandbuy',
		'pw-westernunion',
		'pw-braintree',
		'pw-paysafecard',
		'pw-ideal',
		'pw-paypal',
		'pw-skrill',
		'pw-gittip',
		'pw-flattr',
		'pw-cb',
		'pw-bitcoin',
		'pw-bitcoin-sign',
		'pw-ripple',
		'pw-sofort',
		'pw-diners',
		'pw-mastercard',
		'pw-trust-e',
		'pw-amazon',
		'pw-jcb',
		'pw-google-wallet',
		'pw-stripe',
		'pw-square',
		'pw-ogone',
		'pw-verisign',
		'pw-discover',
		'pw-american-express',
		'pw-paypal--classic',
		'pw-maestro',
		'pw-visa',
		'pw-visa-electron',
		'pw-postepay',
		'pw-cartasi',
		'pw-unionpay',
		'pw-ec',
		'pw-bancontact',
		);

	private function get_widget_slug() {
		return $this->slug;
	}
	public function __construct() {

		$this->version = '1.0.0';
		$this->slug = 'tz_payment_method_widget';
		parent::__construct(
			'tz_payment_method_widget', // Base ID
			__('Themes Zone Payment Methods Widget', 'themeszone'), // Name
			array('description' => __( "Widget to display payment methods", "themeszone" ), )
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {

		wp_enqueue_style('tz-payment-methods-style', plugin_dir_url(__DIR__) . 'assets/css/payment.css');

	}

	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );
		$this->enqueue_scripts();
		$selected_methods = isset( $instance['methods'] ) ? $instance['methods'] : array();
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( count($selected_methods) ) :
			?>
			<ul class="tz-payment-methods">
				<?php foreach( $selected_methods as $method ) : ?>
					<li><i class="pw <?php echo $method; ?>"></i></li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['methods'] = $new_instance['methods'];
		return $instance;
	}

	public function form($instance){
		$title     	= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$selected_methods = isset( $instance['methods'] ) ? $instance['methods'] : array();

		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'themeszone'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<div class="tz-payment-methods-form-container">
			<p>
				<label><?php _e('Select Methods To Display', 'themeszone'); ?></label>
			</p>
			<ul class="methods-container">
			<?php
			foreach($this->methods as $method) {
				?>
				<li><label for="<?php echo $this->get_field_id('methods') . $method; ?>">
					<i class="pw <?php echo $method; ?>"></i>
				<input id="<?php echo $this->get_field_id('methods') . $method; ?>" name="<?php echo $this->get_field_name('methods'); ?>[]" type="checkbox" value="<?php echo $method; ?>" <?php checked('1', in_array($method, $selected_methods)); ?> />
				</label></li>
					<?php
			}
			?>
			</ul>
		</div>

		<?php
	}

}