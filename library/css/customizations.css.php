<?php

// sshhhh...
error_reporting(0);

function isLoginPage() {
	if ( isset( $_GET['is_login_page'] ) && $_GET['is_login_page'] == '1' ) return true;
	else return false;
}

// this file sets css rules for certain theme customizations... namely, those variables which have a large number of possible values

// make the browser believe we are sending a css file... otherwise it may be skeptical and not load it since the file extension ends in .php
header('Content-Type: text/css');

// the easiest way to make these style customizations is to insert GET variables into CSS code
// Well, rather than CSS, I use LESS, particularly because of its color transformation functions.
// This means that the LESS compiler needs to run on the server when this file is loaded.
// The stylesheet only changes when the customization options are changed, so we can cache the results of the compiled LESS code.
// (I don't want to compile LESS every time the file is loaded!).
// The cached results are saved as a static file, named partly after the hashed GET values.

// Actually, any given customization settings will result in two different outputs from this php script: one for the login page, and one for the rest of the site
$cached_file_name_base = 'customizations.css.cache.' . ( isLoginPage() ? 'login' : 'main' ) . '.';

// naming the cached file after the hashed value of the GET variables makes it so we can tell if the customization settings have changed, just by using the filename
// this will also cause the cache to refresh if this script has changed, since the script's filemtime is passed as a GET parameter
$cached_file_name = $cached_file_name_base . md5( serialize( $_GET ) );

$file_contents = file_get_contents( $cached_file_name );
if ( $file_contents !== false ) {
	// we've already cached the compiled CSS for the given GET variables. just get those results and echo them.
	echo "/* cached CSS */\n";
	echo $file_contents;
	exit;
}


// at this point, the cached file for the given customization settings doesn't exist, or there was an error reading it.
// delete the old cached customizations.css files to keep things clean.
$old_caches = glob( $cached_file_name_base . '*' );
if ( $old_caches !== false ) {
	foreach ( $old_caches as $filename ) {
		unlink( $filename );
	}
}

// time to generate the LESS/CSS.

// less compiler
require_once('..' . DIRECTORY_SEPARATOR . 'external' . DIRECTORY_SEPARATOR . 'lessc.inc.php');

// we will capture the contents of the output buffer and run it through the less compiler
ob_start();

/* theme customizations, based on $_GET values
 * accepted GET parameters:
 *		is_login_page
 *		bg_pattern
 *		primary_bg_color
 *		header_bg_color
 *		sidebar_header_bg_color
 *		sidebar_bg_color
 *		footer_bg_color
 *		nav_bg_color
 *		main_bg_color
 *		header_height
 *		accent_text_color
 *		accent_font
 */


// construct some CSS statements to use throughout this file

$media_query_2x = "@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min--moz-device-pixel-ratio: 1.5), only screen and (min-device-pixel-ratio: 1.5)";


if ( isset( $_GET['bg_pattern'] ) ) {
	$image_size = getimagesize( dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'bg-patterns' . DIRECTORY_SEPARATOR . $_GET['bg_pattern'] . '.png' );
	$half_width = $image_size[0]/2;
	$half_height = $image_size[1]/2;
}


//////////////////////
// if login page
if ( isLoginPage() ) {
	if ( isset( $_GET['bg_pattern'] ) ) {
		$bg = get_val_default( 'primary_bg_color', '#e9e0cc' );
		
		echo <<<EOT
		html {
			background-image: url(../images/bg-patterns/subtle/{$_GET['bg_pattern']}.png);
			background-repeat: repeat;
			background-color: $bg
		}
		$media_query_2x {
			html {
				background-size: {$half_width}px {$half_height}px;
			}
		}
EOT;
	}
	
	finish(); // end here so I don't have to have extra indentation in the rest of the file due to an else block
}


//////////////////////
// if not login page

////////////////////////////////////////////
// bg_pattern //////////////////////////////
////////////////////////////////////////////
if ( isset( $_GET['bg_pattern'] ) ) {
	
	echo <<<EOT
	/* background patterns courtesy of http://subtlepatterns.com/
	   CC-BY-SA 3.0 */
	
	.sidebar-style-boxes {
		#container,
		#mobile-icons,
		#main-nav .top-nav > ul > li.current_page_item,
		#main-nav .top-nav > ul > li.current_page_ancestor {
			background-image: url(../images/bg-patterns/subtle/{$_GET['bg_pattern']}.png);
			background-repeat: repeat;
		}
	}
	.sidebar-style-column {
		#container {
			background-image: url(../images/bg-patterns/{$_GET['bg_pattern']}.png);
			background-repeat: repeat;
		}
	}
	
	$media_query_2x {
		#container, #mobile-icons,
		#main-nav .top-nav > ul > li.current_page_item,
		#main-nav .top-nav > ul > li.current_page_ancestor {
			background-size: {$half_width}px {$half_height}px;
		}
	}
EOT;
}



////////////////////////////////////////////
// colors //////////////////////////////////
////////////////////////////////////////////

function get_val_default( $key, $default ) {
	if ( isset ( $_GET[ $key ] ) ) return $_GET[ $key ];
	else return $default;
}

$primary_bg_color = get_val_default( 'primary_bg_color', '#e9e0cc' );
$header_bg_color = get_val_default( 'header_bg_color', '#cac3d2' );
$sidebar_header_bg_color = get_val_default( 'sidebar_header_bg_color', '#7d6eb3' );
$sidebar_bg_color = get_val_default( 'sidebar_bg_color', '#ffffff' );
$footer_bg_color = get_val_default( 'footer_bg_color', '#0b9041' );
$header_height = intval( get_val_default( 'header_height', 200 ) );
$nav_bg_color = get_val_default( 'nav_bg_color', '#473f68' );
$main_bg_color = get_val_default( 'main_bg_color', '#ffffff' );
$accent_text_color = get_val_default( 'accent_text_color', '#643804' );


echo <<<EOT

@mobile-nav-bg-color: desaturate( darken( $header_bg_color, 20% ), 5% );

#container { background-color: $primary_bg_color; }
h1, .h1,
h2, .h2,
.panel-grid .widget > h3:first-child { color: $accent_text_color; }


#header-wrap { background-color: $header_bg_color; }
#mobile-menu-bg { background-color: @mobile-nav-bg-color; }
#mobile-icons {
	background-color: $header_bg_color;
	border-color: darken( saturate( $header_bg_color, 10% ), 10% );
}

#container #mobile-nav {
	background-color: @mobile-nav-bg-color;
	ul {
		li {
			a {
				border-bottom-color: darken( @mobile-nav-bg-color, 8% );
				&:active {
					background-color: lighten( @mobile-nav-bg-color, 10% );
				}
			}
			ul.children {
				border-left-color: darken( @mobile-nav-bg-color, 8% );
				border-right-color: darken( @mobile-nav-bg-color, 8% );
			}
		}
		li.current_page_item > a {
			background: lighten( @mobile-nav-bg-color, 10% );
		}
	}
}


#container {
	.child-nav {
		h2 a { color: $accent_text_color; }
		ul {
			a { color: $accent_text_color; }
			
			li.current_page_item > a {
				background: desaturate( darken( $primary_bg_color, 15% ), 10% );
			}
		}
	}
}

#mobile-child-nav h2 {
	border-bottom-color: desaturate( darken( $primary_bg_color, 15% ), 10% );
}

.single-title, .article-header {
	a, a:hover, a:visited {
		color: $accent_text_color;
	}
	a:hover {
		border-bottom-color: $accent_text_color;
	}
}

.entry-content {
	b, blockquote { color: $accent_text_color }
}

hr {
	border-bottom-color: lighten( $primary_bg_color, 20% );
	border-top-color: darken( $primary_bg_color, 20% );
}

.widgettitle {
	background-color: $sidebar_header_bg_color;
}
.widget {
	background-color: $sidebar_bg_color;
}
.panel.widget {
	background-color: transparent;
	.widgettitle {
		color: $accent_text_color;
	}
}

#inner-footer {
	background-color: $footer_bg_color;
}
.gce-list-event, .gce-tooltip-event {
	color: $accent_text_color;
}
.gce-event-where, .gce-event-when {
	color: desaturate( lighten( $accent_text_color, 15% ), 15% );
}

.wp-caption {
	background: darken( desaturate( $primary_bg_color, 15% ), 15% );
}

.bones_page_navi li,
.flatlink {
	background: $accent_text_color;
}
.bones_page_navi li a,
.flatlink {
	&:hover {
		color: $accent_text_color;
	}
	&:active {
		background: lighten( $accent_text_color, 40% );
	}
}

.sidebar-style-column {
	.all-sidebars {
		background: lighten( fadeout( $primary_bg_color, 33% ), 5% );
		.widget,
		.widgettitle {
			background: none;
		}
	}
	#main {
		background: $main_bg_color;
	}
	#main-nav > .top-nav > ul > li {
		&.current_page_item,
		&.current_page_ancestor {
			background: $main_bg_color;
		}
	}
	.wp-caption {
		background: darken( desaturate( $main_bg_color, 15% ), 15% );
	}
}

.parallax #main-header {
	background: $header_bg_color;
}

@media only screen and (min-width: 768px) {
	.parallax {
		#inner-header, #main-header {
			background-color: fadeout( $header_bg_color, 15% );
		}
		#main-header {
			height: {$header_height}px;
		}
		&.not-faded #main-header {
			background: none;
		}
	}
	
	#container > .header {
		background-color: $header_bg_color;
	}
	
	#main-nav {
		.top-nav > ul > li {
			&.current_page_item, &.current_page_ancestor {
				background-color: $primary_bg_color;
			}
			&.page_item_has_children:hover,
			> .children {
				background: lighten( $header_bg_color, 15% );
				> li {
					border-bottom: 1px solid lighten( $header_bg_color, 3% );
					border-top: 1px solid lighten( $header_bg_color, 27%% );
				}
			}
		}
	}
	
	
	.nav-above-header,
	.nav-below-header.sidebar-style-column {
		
		#inner-header {
			background: $nav_bg_color;
		}
		
		#main-nav .top-nav > ul > li {
			&.current_page_item,
			&.current_page_ancestor {
				background: darken( $nav_bg_color, 12% );
			}
			
			&:hover,
			> .children {
				background: lighten( $nav_bg_color, 12% );
				> li {
					border-bottom: 1px solid $nav_bg_color;
					border-top: 1px solid lighten( $nav_bg_color, 24% );
				}
			}
		}
	}
	.nav-below-header.sidebar-style-column.parallax #inner-header {
		background: fadeout( $nav_bg_color, 20% );
	}
}

EOT;


////////////////////////////////////////////
// accent_font /////////////////////////////
////////////////////////////////////////////
if ( isset( $_GET['accent_font'] ) ) {
	$accent_font = $_GET['accent_font'];
	
	if ( $accent_font == 'palatino' ) {
			// do nothing. this is the default.
	
	
	/* ------------------ serif ---------------- */
	} else if ( $accent_font == 'lora' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 700;
		  src: local('Lora Bold'), local('Lora-Bold'), url(http://fonts.gstatic.com/s/lora/v8/P18Nsu9EiYldSvHIj_0e5w.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: italic;
		  font-weight: 400;
		  src: local('Lora Italic'), local('Lora-Italic'), url(http://fonts.gstatic.com/s/lora/v8/_RSiB1sBuflZfa9fxV8cOg.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Lora'), local('Lora-Regular'), url(http://fonts.gstatic.com/s/lora/v8/4vqKRIwnQQGUQQh-PnvdMA.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		<?php
	} else if ( $accent_font == 'josefin_slab' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Josefin Slab'), local('JosefinSlab'), url(http://fonts.gstatic.com/s/josefinslab/v5/46aYWdgz-1oFX11flmyEfegdm0LZdjqr5-oayXSOefg.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		/* latin */
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: italic;
		  font-weight: 400;
		  src: local('Josefin Slab Italic'), local('JosefinSlab-Italic'), url(http://fonts.gstatic.com/s/josefinslab/v5/etsUjZYO8lTLU85lDhZwUhMBlNeli-0RcAdOFWWLdxQ.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5,
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	} else if ( $accent_font == 'alegreya_sc' ) {
		?>
		@font-face {
		  font-family: 'accent_header_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Alegreya SC'), local('AlegreyaSC-Regular'), url(http://fonts.gstatic.com/s/alegreyasc/v6/-74JUGs8W14C5cCBFRS30-L2WfuF7Qc3ANwCvwl0TnA.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		@font-face {
		  font-family: 'accent_header_font';
		  font-style: italic;
		  font-weight: 400;
		  src: local('Alegreya SC Italic'), local('AlegreyaSC-Italic'), url(http://fonts.gstatic.com/s/alegreyasc/v6/GOqmv3FLsJ2r6ZALMZVBmlGX6R4v-P3fCrxUne8Jf1A.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5,
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	}
	
	/* ------------------ sans serif ---------------- */
	else if ( $accent_font == 'cabin' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Cabin Regular'), local('Cabin-Regular'), url(http://fonts.gstatic.com/s/cabin/v6/yQOMOX5hR0-6LTD879t-PQ.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		/* latin */
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 700;
		  src: local('Cabin Bold'), local('Cabin-Bold'), url(http://fonts.gstatic.com/s/cabin/v6/82B-3YlzWJm8zbCrVEmc_vesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		<?php
	} else if ( $accent_font == 'philosopher' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Philosopher'), url(http://fonts.gstatic.com/s/philosopher/v6/OttjxgcoEsufOGSINYBGLY4P5ICox8Kq3LLUNMylGO4.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 700;
		  src: local('Philosopher Bold'), local('Philosopher-Bold'), url(http://fonts.gstatic.com/s/philosopher/v6/napvkewXG9Gqby5vwGHICIlIZu-HDpmDIZMigmsroc4.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		<?php
	} else if ( $accent_font == 'quicksand' ) {
		?>
		@font-face {
		  font-family: 'accent_header_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Quicksand Regular'), local('Quicksand-Regular'), url(http://fonts.gstatic.com/s/quicksand/v4/sKd0EMYPAh5PYCRKSryvW1tXRa8TVwTICgirnJhmVJw.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		/* latin */
		@font-face {
		  font-family: 'accent_link_font';
		  font-style: normal;
		  font-weight: 700;
		  src: local('Quicksand Bold'), local('Quicksand-Bold'), url(http://fonts.gstatic.com/s/quicksand/v4/32nyIRHyCu6iqEka_hbKsugdm0LZdjqr5-oayXSOefg.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5 {
			font-weight: 400 !important;
		}
		<?php
	}
	
	/* ------------------------ script -------------------------- */
	else if ( $accent_font == 'bad_script' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Bad Script Regular'), local('BadScript-Regular'), url(http://fonts.gstatic.com/s/badscript/v5/rL_b2ND61EQmMOJ8CRr1fiEAvth_LlrfE80CYdSH47w.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5,
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	} else if ( $accent_font == 'bilbo' ) {
		?>
		@font-face {
		  font-family: 'accent_header_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Bilbo Swash Caps'), local('BilboSwashCaps-Regular'), url(http://fonts.gstatic.com/s/bilboswashcaps/v7/UB_-crLvhx-PwGKW1oosDkQKLJqOwopPoTIHsnJrFJg.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5 {
			font-weight: 400 !important;
		}
		blockquote {
			font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif !important;
		}
		<?php
	} else if ( $accent_font == 'lobster' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Lobster'), url(http://fonts.gstatic.com/s/lobster/v9/cycBf3mfbGkh66G5NhszPQ.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5,
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	}
	
	/* ------------------------ monospace -------------------- */
	else if ( $accent_font == 'fira_mono' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Fira Mono'), local('FiraMono'), url(http://fonts.gstatic.com/s/firamono/v3/SlRWfq1zeqXiYWAN-lnG-hJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 700;
		  src: local('Fira Mono Bold'), local('FiraMono-Bold'), url(http://fonts.gstatic.com/s/firamono/v3/l24Wph3FsyKAbJ8dfExTZzy24DTBG-RpCwXaYkM4aks.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	}
	
	/* ------------------------- special -------------------- */
	else if ( $accent_font == 'luckiest_guy' ) {
		?>
		@font-face {
		  font-family: 'accent_header_and_link_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Luckiest Guy'), local('LuckiestGuy-Regular'), url(http://fonts.gstatic.com/s/luckiestguy/v5/5718gH8nDy3hFVihOpkY5Ogdm0LZdjqr5-oayXSOefg.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5,
		#mobile-nav a, .child-nav a, #main-nav a {
			font-weight: 400 !important;
		}
		<?php
	} else if ( $accent_font == 'vast_shadow' ) {
		?>
		@font-face {
		  font-family: 'accent_header_font';
		  font-style: normal;
		  font-weight: 400;
		  src: local('Vast Shadow Regular'), local('VastShadow-Regular'), url(http://fonts.gstatic.com/s/vastshadow/v6/vUwwSAve1had6QNw3_7lJeL2WfuF7Qc3ANwCvwl0TnA.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
		}
		h1, h2, h3, h4, h5, .h1, .h2, .h3, .h4, .h5 {
			font-weight: 400 !important;
		}
		<?php
	}
}


finish();
function finish() {
	// compile the LESS into CSS
	$less = new lessc;
	$css = $less->compile( ob_get_clean() );
	
	// write the compiled CSS to a cache file
	global $cached_file_name;
	file_put_contents( $cached_file_name, $css );
	
	// now echo the CSS and be done
	echo $css;
	exit;
}

?>