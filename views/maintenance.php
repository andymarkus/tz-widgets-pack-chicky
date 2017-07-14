<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Montserrat:400,600" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/maintenance.css', dirname( __FILE__ ) ); ?>">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="<?php echo plugins_url( 'assets/js/countdown.js', dirname( __FILE__ ) ); ?>"></script>
	<title><?php _e('Coming Soon', 'themeszone') ?> | <?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
</head>

<body>

<div id="container">

	<header>
		<?php if ( $pre_text = get_option('tz_maintenance_mode_pre_text') ) : ?>

			<div class="pre-text animated fadeInUp"><?php echo $pre_text; ?></div>

		<?php endif; ?>

		<?php if ( $main_text = get_option('tz_maintenance_mode_main_text') ) : ?>

			<div class="main-text animated fadeInUp"><?php echo esc_html($main_text) ?></div>

		<?php endif; ?>
			<h1 class=" animated fadeInUp"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
	</header>

	<main id="countdown" class=" animated fadeInUp">

	</main>
	<?php
	if ($time = get_option('tz_maintenance_mode_time')) $target = explode("-", $time);
	?>
	<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				var container = $("#countdown");
				var newDate = new Date(<?php echo $target[0] ?>, <?php echo ($target[1]) ?>, <?php echo $target[2] ?>);
				$(container).countdown({
					until: newDate,
				});
			});
		})(jQuery);
	</script>

</div>

</body>
</html>
