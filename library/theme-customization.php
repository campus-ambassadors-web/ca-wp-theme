<?php

function theme_support_additions() {
	
	// use custom-header for the logo
	add_theme_support( 'custom-header', array(
		'default-image'          => '',
		'random-default'         => false,
		'width'                  => 410,
		'height'                 => 0,
		'flex-height'            => true,
		'flex-width'             => false,
		'default-text-color'     => '',
		'header-text'            => false,
		'uploads'                => true,
		'wp-head-callback'       => '',
		'admin-head-callback'    => '',
		'admin-preview-callback' => '',
	));
}
add_action('after_setup_theme','theme_support_additions', 17);


// a function which only tries to add panels if the feature is supported
function safe_add_panel( $wp_customize, $panel_id, $options ) {
	if ( method_exists( $wp_customize, 'add_panel' ) ) {
		$wp_customize->add_panel( $panel_id, $options );
	}
}


$header_photo_values = array();
$footer_art_values = array();
function theme_customization_additions( $wp_customize ) {
	
	/////////////////////////////////////////////
	// Theme Style and Layout
	/////////////////////////////////////////////
	
	safe_add_panel( $wp_customize, 'theme_style_panel', array(
		'title'          => 'Style & Layout',
		'priority'       => 70
	));
	
	
	/////////////////////////
	// Layout
	$wp_customize->add_section( 'layout_section', array(
		'title'		=> __( 'Layout', 'bonestheme' ),
		'priority'	=> 1,
		'panel'		=> 'theme_style_panel'
	));
	
	// Page layout style
	$wp_customize->add_setting( 'sidebar_style', array(
		'default'     => 'sidebar-style-boxes'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sidebar_style', array(
		'label'		=> 'Page layout style',
		'section'	=> 'layout_section',
		'settings'	=> 'sidebar_style',
		'type'		=> 'radio',
		'choices'	=> array(
			'sidebar-style-boxes'	=> 'Floating sidebar',
			'sidebar-style-column'	=> 'Columns'
		)
	)));
	
	// nav location
	$wp_customize->add_setting( 'nav_location', array(
		'default'     => 'nav-below-header'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'nav_location', array(
		'label'		=> 'Navigation bar location',
		'section'	=> 'layout_section',
		'settings'	=> 'nav_location',
		'type'		=> 'radio',
		'choices'	=> array(
			'nav-below-header'	=> 'Below header',
			'nav-above-header'	=> 'Fixed at top of window'
		)
	)));
	
	
	/////////////////////////
	// Colors
	$wp_customize->add_section( 'colors_section', array(
		'title'		=> __( 'Colors', 'bonestheme' ),
		'panel'		=> 'theme_style_panel',
		'priority'	=> 5,
	));
	
	// color presets
	$wp_customize->add_setting( 'color_preset', array(
		'default'	=> 'crimson_nightlife'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'color_preset', array(
		'label'		=> 'Color scheme presets',
		'section'	=> 'colors_section',
		'settings'	=> 'color_preset',
		'type'		=> 'select',
		'priority'	=> 1,
		'choices'	=> array(
			'crimson_nightlife'	=> 'Crimson Nightlife',
			'sandy_dusk'		=> 'Sandy Dusk',
			'evergreen'			=> 'Evergreen',
			'rolling_hills'		=> 'Rolling Hills',
			'country_cottage'	=> 'Country Cottage',
			'purple_mountains'	=> 'Purple Mountains',
			'grey_and_gold'		=> 'Grey and Gold',
			'custom'	=> '[Custom color scheme]'
		)
	)));
	
	// colors
	$colors = array(
		'primary_bg_color' => array(
			'label' => 'Primary background color',
			'default' => '#e9e0cc',
			'active_callback' => 'customizer_callback_is_custom_color_scheme'
		),
		'header_bg_color' => array(
			'label' => 'Page header background color',
			'default' => '#cac3d2',
			'active_callback' => 'customizer_callback_is_custom_color_scheme'
		),
		'footer_bg_color' => array(
			'label' => 'Footer background color',
			'default' => '#0b9041',
			'active_callback' => 'customizer_callback_show_footer_bg_color'
		),
		'accent_text_color' => array(
			'label' => 'Accent text color',
			'default' => '#643804',
			'active_callback' => 'customizer_callback_is_custom_color_scheme'
		),
		'text_link_color' => array(
			'label' => 'Text link color',
			'default' => '#3686b7',
			'active_callback' => 'customizer_callback_is_custom_color_scheme'
		),
		'nav_bg_color' => array(
			'label' => 'Navigation background color',
			'default' => '#473f68',
			'active_callback' => 'customizer_callback_show_nav_bg_color'
		),
		'sidebar_header_bg_color' => array(
			'label' => 'Widget header background color',
			'default' => '#7d6eb3',
			'active_callback' => 'customizer_callback_is_custom_color_scheme_and_not_column_sidebar'
		),
		'sidebar_bg_color' => array(
			'label' => 'Widget background color',
			'default' => '#ffffff',
			'active_callback' => 'customizer_callback_is_custom_color_scheme_and_not_column_sidebar'
		),
		'main_bg_color' => array(
			'label' => 'Main content background color',
			'default' => '#ffffff',
			'active_callback' => 'customizer_callback_is_custom_color_scheme_and_column_sidebar'
		)
	);
	
	$inc = 3;
	foreach( $colors as $key=>$color ) {
		$wp_customize->add_setting( $key, array(
			'default'	=> $color['default']
		));
		$control_options = array(
			'label'		=> $color['label'],
			'section'	=> 'colors_section',
			'settings'	=> $key,
			'priority'	=> $inc++
		);
		if ( isset( $color['active_callback'] ) ) $control_options['active_callback'] = $color['active_callback'];
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, $control_options ));
	}
	
	
	/////////////////////////
	// Eye Candy
	$wp_customize->add_section( 'eye_candy_section', array(
		'title'		=> __( 'Eye Candy', 'bonestheme' ),
		'panel'		=> 'theme_style_panel',
		'priority'	=> 10
	));
	
	// background pattern
	$wp_customize->add_setting( 'bg_pattern', array(
		'default'     => 'none'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'bg_pattern', array(
		'label'      => __( 'Background Pattern', 'bonestheme' ),
		'section'    => 'eye_candy_section',
		'settings'   => 'bg_pattern',
		'type'		 => 'select',
		'choices'	 => array(
			'none'			=> '[None (flat color)]',
			'axiom'			=> 'Axiom',
			'brick-wall'	=> 'Brick Wall',
			'diamonds'		=> 'Diamonds',
			'diamonds2'		=> 'Diamonds 2',
			'dimension'		=> 'Dimension',
			'doily'			=> 'Doily',
			'doodles'		=> 'Doodles',
			'drywall'		=> 'Drywall',
			'fresh-snow'	=> 'Fresh Snow',
			'grainy'		=> 'Grainy',
			'metalwork'		=> 'Metalwork',
			'paper'			=> 'Paper',
			'paper2'		=> 'Paper 2',
			'plaid-fabric'	=> 'Plaid Fabric',
			'rice-paper'	=> 'Rice Paper',
			'stained-glass' => 'Stained Glass',
			'swirls'		=> 'Swirls'
		)
	)));
	
	// checkboxes
	$checkboxes = array(
		'rounded_corners'	=> 'Use rounded corners',
		'box_shadows'		=> 'Use box shadows',
		'text_shadows'		=> 'Use text shadows',
		'image_borders'		=> 'Use image borders'
	);
	foreach( $checkboxes as $key=>$label ) {
		$wp_customize->add_setting( $key, array(
			'default'     => false
		));
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $key, array(
			'label'		=> $label,
			'section'	=> 'eye_candy_section',
			'settings'	=> $key,
			'type'		=> 'checkbox'
		)));
	}
	
	
	/////////////////////////
	// Fonts
	$wp_customize->add_section( 'font_section', array(
		'title'		=> __( 'Fonts', 'bonestheme' ),
		'panel'		=> 'theme_style_panel',
		'priority'	=> 15
	));
	
	// accent font
	$wp_customize->add_setting( 'accent_font', array(
		'default'     => 'palatino'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'accent_font', array(
		'label'		=> __( 'Accent font', 'bonestheme' ),
		'section'	=> 'font_section',
		'settings'	=> 'accent_font',
		'type'		=> 'select',
		'choices'	=> array(
			'palatino'		=> 'Palatino Linotype',
			'alegreya_sc'	=> 'Alegreya SC',
			'lora'			=> 'Lora',
			'josefin_slab'	=> 'Josefin Slab',
			'cabin'			=> 'Cabin',
			'philosopher'	=> 'Philosopher',
			'quicksand'		=> 'Quicksand',
			'bad_script'	=> 'Bad Script',
			'bilbo'			=> 'Bilbo',
			'lobster'		=> 'Lobster',
			'fira_mono'		=> 'Fira Mono',
			'luckiest_guy'	=> 'Luckiest Guy',
			'vast_shadow'	=> 'Vast Shadow'
		)
	)));
	
	
	/////////////////////////
	// Header photos
	
	// header photo style
	$wp_customize->add_section( 'header_photo_section', array(
		'title'      => __( 'Header Photos', 'bonestheme' ),
		'description' => 'Add up to four photos to use as decoration in the header.',
		'panel'		=> 'theme_style_panel',
		'priority'   => 70
	));
	
	$wp_customize->add_setting( 'header_photo_style', array(
		'default' => 'polaroid'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_photo_style', array(
		'label'		=> 'Header photo style',
		'section'	=> 'header_photo_section',
		'settings'	=> 'header_photo_style',
		'type'		=> 'radio',
		'priority'	=> 10,
		'choices'	=> array(
			'polaroid'	=> 'Polaroid',
			'parallax' => 'Parallax'
		)
	)));
	
	// fade out parallax image
	$wp_customize->add_setting( 'parallax_fade', array(
		'default' => 'parallax-fade-none'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'parallax_fade', array(
		'label'		=> 'Parallax image fade',
		'section'	=> 'header_photo_section',
		'settings'	=> 'parallax_fade',
		'type'		=> 'select',
		'priority'	=> 15,
		'active_callback' => 'customizer_callback_is_parallax',
		'choices'	=> array(
			'parallax-fade-none'	=> 'No parallax image fade',
			'parallax-fade-logo'	=> 'Fade image behind logo/title only',
			'parallax-fade-all'		=> 'Fade entire image'
		)
	)));
	
	
	// add a header height field
	$wp_customize->add_setting( 'header_height', array(
		'default' => 200
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_height', array(
		'label'		=> 'Header height (in pixels)',
		'section'	=> 'header_photo_section',
		'settings'	=> 'header_height',
		'type'		=> 'number',
		'priority'	=> 20,
		'active_callback' => 'customizer_callback_is_parallax',
		'input_attrs' => array(
			'min'	=> 100,
			'max'	=> 500
		)
	)));
	
	// header photos
	$header_photo_options = array( 'header_photo_1', 'header_photo_2', 'header_photo_3', 'header_photo_4' );
	global $header_photo_values;
	foreach( $header_photo_options as $index=>$id ) {
		$wp_customize->add_setting( $id );
		$control = new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'		=> 'Header image ' . ($index+1),
			'section'	=> 'header_photo_section',
			'settings'	=> $id,
			'priority'	=> 30
		));
		$wp_customize->add_control( $control );
		// add a media library tab to the control
		$control->add_tab( 'library', __( 'Media Library', 'bonestheme' ), 'customize_header_image_control_library_tab_handler' );
		$control->remove_tab( 'upload-new' );
		$control->remove_tab(' uploaded' );
		array_push( $header_photo_values, get_theme_mod( $control->id ) );
	}
	
	
	/////////////////////////
	// Footer art
	$wp_customize->add_section( 'footer_art_section', array(
		'title'      => __( 'Footer Art', 'bonestheme' ),
		'description' => 'This can only be changed if you are using a custom color scheme.',
		'panel'		=> 'theme_style_panel',
		'priority'   => 71
	));
	
	// presets
	$wp_customize->add_setting( 'footer_art_preset', array(
		'default'	=> 'hills.png'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'footer_art_preset', array(
		'section'	=> 'footer_art_section',
		'settings'	=> 'footer_art_preset',
		'priority'	=> 10,
		'type'		=> 'select',
		'active_callback' => 'customizer_callback_is_custom_color_scheme',
		'choices'	=> array(
			'none'			=> '[None]',
			'city1.png'		=> 'City 1',
			'city2.png'		=> 'City 2',
			'desert.png'	=> 'Desert',
			'forest.png'	=> 'Forest',
			'hills.png'		=> 'Hills',
			'house.png'		=> 'House',
			'manger_cross.png' => 'Manger and Cross',
			'mountains.png'	=> 'Mountains',
			'custom'	=> '[Custom footer art]'
		)
	)));
	
	// custom footer art
	$footer_art_options = array( 'footer_art' );
	global $footer_art_values;
	foreach( $footer_art_options as $id ) {
		$wp_customize->add_setting( $id );
		$control = new WP_Customize_Image_Control( $wp_customize, $id, array(
			'label'		=> 'Custom footer image',
			'section'    => 'footer_art_section',
			'description'	=> 'You may also set a footer color in the "Colors" section.<br />Please use a very wide image.',
			'settings'   => $id,
			'priority'	=> 20,
			'active_callback' => 'customizer_callback_is_custom_footer_and_custom_color_scheme'
		));
		$wp_customize->add_control( $control );
		// add a media library tab to the control
		$control->add_tab( 'library', __( 'Media Library', 'bonestheme' ), 'customize_footer_image_control_library_tab_handler' );
		$control->remove_tab( 'upload-new' );
		$control->remove_tab(' uploaded' );
		array_push( $footer_art_values, get_theme_mod( $control->id ) );
	}
	
	
	/////////////////////////
	// Social media icons
	$wp_customize->add_section( 'social_media_section', array(
		'title'		=> __( 'Social Media Icons', 'bonestheme' ),
		'description' => 'Enter the URLs for your social media pages to add linked icons to the site.',
		'priority'	=> 145
	));
	$wp_customize->add_setting( 'sm_header', array(
		'default'	=> true
	));
	$wp_customize->add_setting( 'sm_footer', array(
		'default'	=> false
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sm_footer', array(
		'label'		=> 'Show icons in footer',
		'section'	=> 'social_media_section',
		'settings'	=> 'sm_footer',
		'type'		=> 'checkbox'
	)));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'sm_header', array(
		'label'		=> 'Show icons in header',
		'section'	=> 'social_media_section',
		'settings'	=> 'sm_header',
		'type'		=> 'checkbox'
	)));
	
	$sm_url_options = array(
		'default'	=> '',
		'sanitize_callback' => 'esc_url'
	);
	$sm_options = array(
		'sm_facebook_url' => 'Facebook',
		'sm_twitter_url' => 'Twitter',
		'sm_instagram_url' => 'Instagram',
		'sm_pinterest_url' => 'Pinterest'
	);
	foreach( $sm_options as $option_id=>$option_label ) {
		$wp_customize->add_setting( $option_id, $sm_url_options);
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $option_id, array(
			'label'		=> $option_label,
			'section'	=> 'social_media_section',
			'settings'	=> $option_id
		)));
	}
	
	
	/////////////////////////////////////////////
	// Google Analytics
	/////////////////////////////////////////////
	$wp_customize->add_section( 'google_analytics_section', array(
		'title'		=> __( 'Google Analytics', 'bonestheme' ),
		'description' => 'Google Analytics can track visitor statistics. For more information, see the <a href="http://www.google.com/analytics/" target="_blank">analytics website</a>.',
		'priority'	=> 150
	));
	$wp_customize->add_setting( 'google_analytics_id', array(
		'default'	=> '',
		'sanitize_callback' => 'esc_html'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'google_analytics_id', array(
		'label'		=> 'Tracking ID (UA-########-#)',
		'section'	=> 'google_analytics_section',
		'settings'	=> 'google_analytics_id'
	)));
	
	
	/////////////////////////////////////////////
	// Sidebar options
	/////////////////////////////////////////////
	$wp_customize->add_section( 'sidebar_options_section', array(
		'title'		=> __( 'Sidebar Options', 'bonestheme' ),
		'priority'	=> 160
	));
	$wp_customize->add_setting( 'number_of_sidebars', array(
		'default' => 'one'
	));
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'number_of_sidebars', array(
		'section'	=> 'sidebar_options_section',
		'settings'	=> 'number_of_sidebars',
		'type'		=> 'radio',
		'choices'	=> array(
			'one'		=> 'Use one sidebar throughout the site',
			'two'		=> 'Use a separate sidebar for posts'
		)
	)));
	
}
add_action( 'customize_register', 'theme_customization_additions' );


$header_photo_func_count = 0;
function customize_header_image_control_library_tab_handler() {
	global $header_photo_func_count, $header_photo_values;
	// create a link button, and set data on that button to tell the control what thumbnail image to load. otherwise it will stick the full size image in the thumbnail area.
	?>
	<a class="choose-from-library-link button" data-thumbnail="<?php echo wp_get_attachment_thumb_url( $header_photo_values[$header_photo_func_count] ) ?>">Choose image</a>
	<?php
	$header_photo_func_count++;
}

$footer_art_func_count = 0;
function customize_footer_image_control_library_tab_handler() {
	global $footer_art_func_count, $footer_art_values;
	// create a link button, and set data on that button to tell the control what thumbnail image to load. otherwise it will stick the full size image in the thumbnail area.
	?>
	<a class="choose-from-library-link button" data-thumbnail="<?php echo wp_get_attachment_thumb_url( $footer_art_values[$footer_art_func_count] ) ?>">Choose image</a>
	<?php
	$footer_art_func_count++;
}


// apply css to enable styles chosen in the theme customization
add_action( 'wp_enqueue_scripts', 'customization_styles', 1000 );
add_action( 'login_enqueue_scripts', 'login_customization_styles', 1000 );
function customization_styles( $is_login = false ) {
	
	$getvars = array();
	$mod_names = array('bg_pattern', 'accent_font', 'primary_bg_color', 'header_bg_color', 'sidebar_header_bg_color', 'sidebar_bg_color', 'footer_bg_color', 'nav_bg_color', 'main_bg_color', 'accent_text_color', 'text_link_color', 'header_height');
	foreach( $mod_names as $mod_name ) {
		$mod = get_theme_mod( $mod_name, false );
		if ( $mod ) $getvars[$mod_name] = $mod;
	}
	
	$timestamp = filemtime( get_template_directory() . '/library/css/customizations.css.php' );
	if ( $is_login ) {
		$getvars['is_login_page'] = "1";
		wp_register_style( 'customization-styles', get_stylesheet_directory_uri() . '/library/css/customizations.css.php?' . http_build_query( $getvars ), array(), $timestamp );
		wp_enqueue_style( 'customization-styles' );
	}
	else if ( !is_admin() ) {
		wp_register_style( 'customization-styles', get_stylesheet_directory_uri() . '/library/css/customizations.css.php?' . http_build_query( $getvars ), array('bones-stylesheet', 'bones-ie-only'), $timestamp );
		wp_enqueue_style( 'customization-styles' );
	}
}

function login_customization_styles() {
	customization_styles( true );
}



function customizer_callback_is_parallax() {
	$header_photo_style = get_theme_mod( 'header_photo_style', 'polaroid' );
	return ( $header_photo_style == 'parallax' );
}
function customizer_callback_show_nav_bg_color() {
	if ( !customizer_callback_is_custom_color_scheme() ) return false;
	$nav_loc = get_theme_mod( 'nav_location', 'nav-below-header' );
	$sidebar_style = get_theme_mod( 'sidebar_style', 'sidebar-style-boxes' );
	if ( $nav_loc == 'nav-above-header' ||
	 $nav_loc == 'nav-below-header' && $sidebar_style == 'sidebar-style-column' ) {
		return true;
	} else return false;
}
function customizer_callback_is_custom_color_scheme_and_column_sidebar() {
	return ( customizer_callback_column_sidebar() && customizer_callback_is_custom_color_scheme() );
}
function customizer_callback_column_sidebar() {
	$sidebar = get_theme_mod( 'sidebar_style', 'sidebar-style-boxes' );
	return ( $sidebar == 'sidebar-style-column' );
}
function customizer_callback_is_custom_color_scheme_and_not_column_sidebar() {
	if ( !customizer_callback_is_custom_color_scheme() ) return false;
	return !customizer_callback_column_sidebar();
}
function customizer_callback_show_footer_bg_color() {
	$footer_preset = get_theme_mod( 'footer_art_preset', 'hills.png' );
	return ( $footer_preset == 'custom' || $footer_preset == 'none' ) && customizer_callback_is_custom_color_scheme();
}
function customizer_callback_is_custom_footer_and_custom_color_scheme() {
	$footer_preset = get_theme_mod( 'footer_art_preset', 'hills.png' );
	return ( $footer_preset == 'custom' && customizer_callback_is_custom_color_scheme() );
}

function customizer_callback_is_custom_color_scheme() {
	$color_scheme = get_theme_mod( 'color_preset', 'crimson_nightlife' );
	return ( $color_scheme == 'custom' );
}


function customizer_add_js() {
	// add a script if we are on the cutomizer page
	if ( get_current_screen()->base == 'customize' ) {
		wp_register_script( 'customizer-js', get_stylesheet_directory_uri() . '/library/js/customizer.js', array( 'jquery' ), filemtime( get_stylesheet_directory( '/library/js/customizer.js' ) ), true );
		wp_enqueue_script( 'customizer-js' );
	}
}
add_action( 'admin_enqueue_scripts', 'customizer_add_js' );

?>