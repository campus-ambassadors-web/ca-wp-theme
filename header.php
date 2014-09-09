<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<!-- Google Chrome Frame for IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>

		<!-- mobile meta (hooray!) -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<!-- icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) -->
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<!-- or, set /favicon.ico for IE10 win -->
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
	</head>
	
	<?php
	// classes to add to the body
	$class_options = array( get_theme_mod('sidebar_style', 'sidebar-style-boxes'), get_theme_mod('nav_location', 'nav-below-header'), get_theme_mod('header_photo_style', 'polaroid') );
	// efficiently add options based on checkboxes
	$class_options_cbs = array( 'rounded_corners', 'box_shadows', 'text_shadows', 'image_borders' );
	foreach ( $class_options_cbs as $option_name ) {
		$use_option = get_theme_mod( $option_name, false );
		if ( $use_option ) array_push( $class_options, str_replace( '_', '-', $option_name ) );
	}
	// @TODO: someday, make these changes in a functions file, or the theme-customization.php file.
	// http://codex.wordpress.org/Plugin_API/Filter_Reference/body_class
	?>
	
	<body <?php body_class($class_options); ?>>
	
	<?php
	// google analytics support
	$analytics_id = get_theme_mod('google_analytics_id');
	if ( $analytics_id ) : ?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', '<?php echo $analytics_id ?>', 'auto');
			ga('send', 'pageview');
		</script>
	<?php endif; ?>
	
	<div id="container">
		<?php
		$header_photo_style = get_theme_mod('header_photo_style', 'polaroid');
		$header_photos = array( get_theme_mod('header_photo_1', ''), get_theme_mod('header_photo_2', ''), get_theme_mod('header_photo_3', ''), get_theme_mod('header_photo_4', '') );
		for( $i=0; $i<sizeof($header_photos); $i++ ) {
			if ( empty( $header_photos[$i] ) ) {
				array_splice( $header_photos, $i, 1 );
				$i--;
			}
		}
		?>
		
		<header class="header <?php
		if ( strpos( $header_photo_style, 'parallax' ) !== false && sizeof( $header_photos ) > 0 ) {
			echo 'parallax-element';
			// get the id of one of the header images
			$header_photo_id = $header_photos[ mt_rand(0, sizeof($header_photos)-1) ];
			$header_photo = wp_get_attachment_image_src( $header_photo_id, array( 1000, 200 ) );
			$ratio = $header_photo[1] / $header_photo[2];
			echo '" style="background-image: url(' . $header_photo[0] . ')" data-ratio="' . $ratio;
			
		}
		?>" role="banner" id="header-wrap">
			
			<div id="main-header" class="wrap clearfix">
				<div id="mobile-header-fixed">
					<div id="mobile-menu-bg">
						<?php show_social_media_icons(); ?>
					</div>
					<div id="mobile-icons">
						<a href="javascript:void(0)" onClick="toggleMobileNav()" id="mobile-navicon"><div></div></a>
					</div>
					
					<nav role="navigation" id="mobile-nav">
						<?php bones_main_nav(); ?>
					</nav>
				</div>
				
				<a href="<?php echo site_url() ?>">
					<!--
					<img class="header-logo" src="<?php echo get_template_directory_uri(); ?>/library/images/ca-logo.png" alt="<?php bloginfo('name') ?>" />
					-->
					<?php if ( empty( get_custom_header()->url ) ) : ?>
						<div id="header-title">
							<h1><?php bloginfo('name') ?></h1>
							<h2><?php bloginfo('description') ?></h2>
						</div>
					<?php else : ?>
						<img class="header-logo" src="<?php header_image(); ?>" alt="<?php bloginfo('name') ?>" />
					<?php endif; ?>
				</a>
				
				
				<?php
				if ( $header_photo_style == 'polaroid' ) :
				?>
					<div id="header-photos">
						<?php
						foreach( $header_photos as $header_photo_id ) {
							?>
							<div>
								<img class="header-photo" src="<?php echo wp_get_attachment_thumb_url( $header_photo_id ) ?>" />
							</div>
							<?php
						}
						?>
					</div>
				<?php endif; ?>
			</div>

			<div id="inner-header" class="wrap clearfix">

				<!-- to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> -->
				<!-- <a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a> -->

				<!-- if you'd like to use the site description you can un-comment it below -->
				<?php // bloginfo('description'); ?>


				<nav role="navigation" id="main-nav">
					<?php bones_main_nav(); ?>
					<?php if ( get_theme_mod('sm_header') ) show_social_media_icons(); ?>
				</nav>

			</div> <!-- end #inner-header -->

		</header> <!-- end header -->
