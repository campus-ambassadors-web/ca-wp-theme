<?php
/*
Author: Eddie Machado and (mostly) Ransom Christofferson
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

define( 'I_HAVE_SUPPORTED_THE_IMAGE_WIDGET', true ); // quiet, you pesky (but useful) plugin

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once('library/bones.php'); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
//require_once('library/custom-post-type.php'); // you can disable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
require_once('library/admin.php'); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once('library/translation/translation.php'); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/


/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	
	$common_sidebar_widget_options = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>'
	);
	
	if ( get_theme_mod('number_of_sidebars', '') == 'two' ) {
		// one sidebar for pages only, and one sidebar for posts only
		register_sidebar( array_merge( array(
			'id' => 'sidebar1',
			'name' => __('Page Sidebar', 'bonestheme'),
			'description' => __('A sidebar which sits beside page content.', 'bonestheme')
		), $common_sidebar_widget_options ) );
		
		register_sidebar( array_merge( array(
			'id' => 'sidebar2',
			'name' => __('Post Sidebar', 'bonestheme'),
			'description' => __('A sidebar which sits beside post content or archive pages.', 'bonestheme'),
		), $common_sidebar_widget_options ) );
	} else {
		
		// default... one sidebar for everything.
		register_sidebar( array_merge( array(
			'id' => 'sidebar1',
			'name' => __('Sidebar', 'bonestheme'),
			'description' => __('A sidebar which sits beside page/post content.', 'bonestheme')
		), $common_sidebar_widget_options ) );
	}
	
} // don't remove this bracket!



// custom admin styles
function load_custom_wp_admin_style() {
	wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/library/css/admin.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


// title tag for homepage
add_filter( 'wp_title', 'hack_wp_title_for_home' );
function hack_wp_title_for_home( $title )
{
  if( empty( $title ) && ( is_home() || is_front_page() ) ) {
    return get_bloginfo( 'title' ) . ' | ' . get_bloginfo( 'description' );
  }
  return $title;
}


/************* THEME CUSTOMIZATION *********************/

require_once('library/theme-customization.php');

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<!-- custom gravatar call -->
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>', 'bonestheme'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><i class="icon-calendar"></i> <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__('F jS, Y', 'bonestheme')); ?> </a></time>
				<?php edit_comment_link(__('Edit', 'bonestheme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!


// fix a mild issue with a certain plugin
function load_admin_scripts() {
	wp_register_script( 'custom-admin-js', get_stylesheet_directory_uri() . '/library/js/admin.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'custom-admin-js' );
}
add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );


/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</form>';
	return $form;
} // don't remove this bracket!


/************* GET USER IP ADDRESS *****************/
function getIP() {
	//check ip from share internet
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
	//to check ip is pass from proxy
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else $ip = $_SERVER['REMOTE_ADDR'];
	return $ip;
}


/************* REVEALABLE SECTION, CLEAR BREAK, AND INSERT BUTTON FOR TINYMCE *****************/

function revealable_section_shortcode_handler( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'button_text' => 'Show section',
		'show_on_load' => false
	), $atts );
	
	ob_start();
	?>
	<button type="button" onclick="hideSection(this)">
		<?php echo $a['button_text']; ?>
	</button>
	<div <?php echo ( $a['show_on_load'] ) ? '' : 'style="display:none"' ; ?> class="revealable-section">
		<?php echo $content; ?>
	</div>
	<?php
	return ob_get_clean();
}

function insert_button_shortcode_handler( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'button_text' => 'Do something',
		'link' => get_site_url(),
		'size' => '100%',
		'style' => 'nice-button',
		'position' => 'inline'
	), $atts );
	
	$html_output = <<<EOT
	<a class="{$a['style']} {$a['position']}" style="font-size: {$a['size']}" href="{$a['link']}">{$a['button_text']}</a>
EOT;
	return $html_output;
}

function tinymce_register_buttons( $buttons ) {
	// find which index the "outdent" button is at and put my buttons right before it
	// if "outdent" isn't found, just put the buttons before "undo"... if "undo" isn't found, pu the buttons at the end
	$array_index = array_search( 'outdent', $buttons );
	if ( $array_index === false ) {
		$array_index = array_search( 'undo', $buttons );
	}
	
	if ( $array_index === false ) {
		array_push( $buttons, 'clear_break', 'revealable_section', 'insert_button' );
	} else {
		array_splice( $buttons, $array_index, 0, array( 'clear_break', 'revealable_section', 'insert_button' ) );
	}
	return $buttons;
}

function tinymce_javascript( $plugin_array ) {
	$plugin_array['clear_break'] = get_template_directory_uri() . '/library/js/tinymce.js';
	$plugin_array['revealable_section'] = get_template_directory_uri() . '/library/js/tinymce.js';
	$plugin_array['insert_button'] = get_template_directory_uri() . '/library/js/tinymce.js';
	return $plugin_array;
}

add_shortcode( 'revealable_section', 'revealable_section_shortcode_handler' );
add_shortcode( 'insert_button', 'insert_button_shortcode_handler' );
add_filter('mce_buttons_2', 'tinymce_register_buttons');
add_filter('mce_external_plugins', 'tinymce_javascript');

function tinymce_styles() {
    add_editor_style( 'library/css/tinymce.css' );
}
add_action( 'after_setup_theme', 'tinymce_styles' );


/************* QUICK EMAIL CONTACT WIDGET *****************/

// This widget uses javascript found in library/js/scripts.js.
// This widget uses styles found in library/less/_base.less

class Quick_Email_Contact_Widget extends WP_Widget {

	public function __construct() {
		// widget init
		parent::__construct(
			'quick_email_contact',
			'Quick Email Contact Form',
			array( 'description' => 'Allows visitors to send a message or feedback via a quick contact form.' )
		);
	}

	public function widget( $args, $instance ) {
		// output the content of the widget
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		
		if ( !empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<div>
			<?php if ( !empty( $instance['text'] ) ) : ?>
				<p><?php echo $instance['text'] ?></p>
			<?php endif; ?>
			<?php if ( $instance['multiline'] ) : ?>
				<textarea class="quick-message"></textarea>
			<?php else : ?>
				<input type="text" class="quick-message" />
			<?php endif; ?>
			<?php if ( $instance['require_email'] ) : ?>
				<input type="email" class="quick-message-email" placeholder="your email address" />
			<?php endif; ?>
			<br />
			<a class="nice-button" href="<?php echo admin_url('admin-ajax.php') ?>"><?php echo $instance['button_text'] ?></a>
			<input type="hidden" name="widget_id" value="<?php echo $this->number ?>" />
			<?php wp_nonce_field( 'quick-contact-widget_'.$this->number, '_nonce_qcw_'.$this->number ); ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	// options form in admin panel
	public function form( $instance ) {
		// define text field properties in this array for easy output
		$properties = array(
			'title' => array(
				'Title',
				get_val( $instance['title'], 'Send Us A Message' )
			),
			'text'	=> array(
				'Text above the input area',
				get_val( $instance['text'], '' )
			),
			'email'	=> array(
				'Email address for messages to be sent to',
				get_val( $instance['email'], get_option('admin_email', '' ) )
			),
			'subject' => array(
				'Subject line of emails sent to the above address',
				get_val( $instance['subject'], '' )
			),
			'button_text' => array(
				'Label on the submit button',
				get_val( $instance['button_text'], 'Submit' )
			),
			'success_message' => array(
				'Message to show to user when email is sent',
				get_val( $instance['success_message'], 'Thank you for contacting us.' )
			)
		);
		
		// output all the text fields
		foreach( $properties as $prop=>$desc ) {
			?>
			<p><label><?php echo $desc[0] ?>:
					<input class="widefat" name="<?php echo $this->get_field_name( $prop ) ?>" type="text" value="<?php echo esc_attr( $desc[1] ); ?>">
			</label></p>
			<?php
		}
		// now output other fields
		?>
		<p><label>
			<input name="<?php echo $this->get_field_name( 'multiline' ) ?>" type="checkbox" <?php if ( get_val( $instance['multiline'], false ) ) echo 'checked="checked"' ?> />
			Allow multiple lines of input?
		</label></p>
		<p><label>
			<input name="<?php echo $this->get_field_name( 'require_email' ) ?>" type="checkbox" <?php if ( get_val( $instance['require_email'], false ) ) echo 'checked="checked"' ?> />
			Require user to provide his/her email address?
		</label></p>
		<?php 
	}
	
	// save widget options
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'			=> strip_tags( $new_instance['title'] ),
			'text'			=> strip_tags( $new_instance['text'] ),
			'email'			=> empty( $new_instance['email'] ) ? get_option('admin_email', '') : strip_tags( $new_instance['email'] ),
			'subject'		=> strip_tags( $new_instance['subject'] ),
			'button_text' 	=> empty( $new_instance['button_text'] ) ? 'Submit' : strip_tags( $new_instance['button_text'] ),
			'success_message' => strip_tags( $new_instance['success_message'] ),
			'multiline'		=> empty( $new_instance['multiline'] ) ? false : $new_instance['multiline'],
			'require_email'	=> empty( $new_instance['require_email'] ) ? false : $new_instance['require_email']
		);
	}
}

add_action('widgets_init',
     create_function('', 'return register_widget("Quick_Email_Contact_Widget");')
);


// add fancybox js
function add_fancybox_scripts() {
	//adding scripts file in the footer
	wp_register_script( 'fancybox-js', get_stylesheet_directory_uri() . '/library/js/libs/fancybox/jquery.fancybox.pack.js', array( 'jquery' ), '', true );
    wp_register_style( 'fancybox-style', get_stylesheet_directory_uri() . '/library/js/libs/fancybox/jquery.fancybox.css', array(), '', 'screen' );

    // enqueue styles and scripts
    wp_enqueue_script( 'fancybox-js' );
	wp_enqueue_style( 'fancybox-style' );
}
add_action('wp_enqueue_scripts', 'add_fancybox_scripts', 1000);


// function to respond to AJAX request
function ajax_quick_email_contact_submit() {
	// get data on the instance of the widget that was used to send this ajax request
	$widget_id = intval( $_POST['widget_id'] );
	$contact_widgets = get_option('widget_quick_email_contact');
	if ( isset( $contact_widgets[$widget_id] ) ) {
		// verify nonce
		if ( wp_verify_nonce( $_POST['nonce'], 'quick-contact-widget_'.$widget_id ) ) {
			// if user email input is required, verify that the email is legit
			if ( !$contact_widgets[$widget_id]['require_email'] || $contact_widgets[$widget_id]['require_email'] && is_email( $_POST['return_email'] ) ) {
				
				$widget = $contact_widgets[$widget_id];
				// the message comes from user input
				$message = stripslashes( strip_tags( $_POST['message'] ) );
				// the target email address comes from the widget options set on the admin widget page
				$email = $widget['email'];
				
				$headers = '';
				if ( $widget['require_email'] ) {
					$return_email = $_POST['return_email'];
					$headers = "From: $return_email <$return_email>\r\n" . "Reply-To: $return_email <$return_email>\r\n";
				}
				
				if ( !empty( $message ) ) {
					// now send the email
					$email_message = $message . "\n\n" .
									"_______________________\n" .
									"IP address: " . getIP();
					
					if ( wp_mail( $email, ( empty( $widget['subject'] ) ? 'Message from quick contact widget' : $widget['subject'] ), $email_message, $headers ) ) {
						echo '<p class="ajax-success">Success!</p>';
						echo '<p>' . $widget['success_message'] . '</p>';
					} else echo '<p class="ajax-error">Sorry!</p> Error: Could not submit the request. Please try again later.';
				} else echo '<p class="ajax-error">Sorry!</p> Please enter a message.';
			} else echo '<p class="ajax-error">Sorry!</p> Please enter a valid email address.';
		} else echo '<p class="ajax-error">Sorry!</p> There was a problem with the request. Please try again later.';
	} else echo '<p class="ajax-error">Sorry!</p> An error occurred. Please try again later.';
	die();
}
add_action( 'wp_ajax_quick_email_contact_submit', 'ajax_quick_email_contact_submit' );
add_action( 'wp_ajax_nopriv_quick_email_contact_submit', 'ajax_quick_email_contact_submit' );

// change default "from" name for wp emails
function change_wp_mail_from_name( $from_name ){
	return $_SERVER['HTTP_HOST'];
}
add_filter("wp_mail_from_name", "change_wp_mail_from_name");



/************* OUTPUT LATEST POSTS WIDGET *****************/

class Output_Latest_Posts_Widget extends WP_Widget {

	public function __construct() {
		// widget init
		parent::__construct(
			'output_latest_posts',
			'Output Latest Posts',
			array( 'description' => 'Outputs your latest posts\' excerpts or post content.' )
		);
	}

	public function widget( $args, $instance ) {
		// output the content of the widget
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		
		if ( !empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		?><div><?php
			// this code essentially copies the template in single.php, except cut up into sections depending on the saved instance variables
			query_posts( array(
				'post_type' => 'post',
				'posts_per_page' => $instance['num_posts']
			));
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					?>
					<article <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						<header class="article-header">
							<h3 class="h2 entry-title single-title" itemprop="headline"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
							<p class="byline vcard"><?php
								if ( $instance['byline_author'] ) {
									?><i class="icon-pencil"></i> <?php echo bones_get_the_author_posts_link();
								}
								if ( $instance['byline_time'] ) {
									?><i class="icon-calendar"></i> <time class="updated" datetime="<?php echo get_the_time('Y-m-j') ?>" pubdate><?php echo get_the_time(get_option('date_format')) ?></time> <?php
								}
								if ( $instance['byline_category'] ) {
									?><i class="icon-folder-open"></i> <?php echo get_the_category_list(', ');
								}
							?></p>
						</header> <!-- end article header -->
						<?php if ( $instance['show_amount'] == 'title' ) :
							if ( $instance['show_thumbnail'] ) : 
								?><a href="<?php the_permalink() ?>"><?php
								the_post_thumbnail( 'bones-thumb-300' );
								?></a><?php
							endif;
						else : ?>
							<section class="entry-content clearfix" itemprop="articleBody">
								<?php
								if ( $instance['show_amount'] == 'excerpt' ) {
									if ( $instance['show_thumbnail'] ) {
										?><a href="<?php the_permalink() ?>"><?php
										the_post_thumbnail( 'bones-thumb-300' );
										?></a><?php
									}
									the_excerpt();
								} else {
									the_content();
								}
								?>
							</section> <!-- end article section -->
							
							<?php if ( $instance['show_amount'] == 'full' ) : ?>
							<footer class="article-footer">
								<?php the_tags('<p class="tags"><span class="tags-title">' . __('Tags:', 'bonestheme') . '</span> ', ', ', '</p>'); ?>
							</footer> <!-- end article footer -->
							<?php endif; // if show_amount == 'full' ?>
						<?php endif; // if show_amount != 'title' ?>
					</article> <!-- end article -->
					<?php
				}
			};
			wp_reset_query();
		?></div><?php
		
		echo $args['after_widget'];
	}

	// options form in admin panel
	public function form( $instance ) {
		?>
		<p><label>Title:<br />
			<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ) ?>" value="<?php echo get_val( $instance['title'], '' ) ?>" />
		</label></p>
		<p><label>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'show_thumbnail' ) ?>" <?php if ( get_val( $instance['show_thumbnail'], true ) ) echo 'checked="checked"' ?> />
			Show thumbnails
		</label></p>
		<?php
		$show_amount = get_val( $instance['show_amount'], 'excerpt' );
		$show_amount_options = array(
			'title' => 'Title only',
			'excerpt' => 'Title and excerpt',
			'full' => 'Full post'
		);
		?>
		<p><label>Amount of each post to show:<br />
			<select name="<?php echo $this->get_field_name( 'show_amount' ) ?>" class="widefat">
			<?php foreach( $show_amount_options as $key=>$desc ) {
				echo "<option value='$key' " . ( $show_amount == $key ? 'selected="selected"' : '' ) . ">$desc</option>";
			} ?>
			</select>
		</label></p>
		<?php
		$byline_amount = get_val( $instance['byline_amount'], 'time' );
		$byline_amount_options = array(
			'byline_author' => 'Author',
			'byline_time' => 'Time',
			'byline_category' => 'Category'
		);
		?>
		<p><label>Amount of byline to show:</label><br />
			<?php foreach( $byline_amount_options as $key=>$desc ) {
				?><label>
					<input type="checkbox" name="<?php echo $this->get_field_name( $key ) ?>" <?php if ( get_val( $instance[ $key ], false ) ) echo 'checked="checked"' ?> />
					<?php echo $desc; ?> &nbsp;&nbsp;&nbsp;
				</label><?php
			} ?>
		</p>
		<p><label>
			Number of posts to show: 
			<input type="number" min="1" max="20" name="<?php echo $this->get_field_name( 'num_posts' ) ?>" value="<?php echo get_val( $instance['num_posts'], 3 ) ?>" />
		</label></p>
		<?php
	}
	
	// save widget options
	public function update( $new_instance, $old_instance ) {
		return array(
			'title'				=> strip_tags( $new_instance['title'] ),
			'show_thumbnail'	=> (bool) get_val( $new_instance['show_thumbnail'], false ),
			'show_amount'		=> strip_tags( $new_instance['show_amount'] ),
			'byline_author'		=> (bool) get_val( $new_instance['byline_author'], false ),
			'byline_time'		=> (bool) get_val( $new_instance['byline_time'], false ),
			'byline_category'	=> (bool) get_val( $new_instance['byline_category'], false ),
			'num_posts'			=> min( max( intval( $new_instance['num_posts'] ), 1 ), 20 )
		);
	}
}

add_action('widgets_init',
     create_function('', 'return register_widget("Output_Latest_Posts_Widget");')
);


/************* SECONDARY (SIDE) NAV *****************/

function get_secondary_nav( $id ) {
	// get top-level ancestor and list the children of it
	$ancestors = get_ancestors( $id, 'page' );
	$top_ancestor = sizeof( $ancestors ) == 0 ? $id : array_pop( $ancestors );
	$pagelist = wp_list_pages( array(
		'depth' => 0,
		'child_of' => $top_ancestor,
		'title_li' => '',
		'echo' => 0
	));
	if ( empty( $pagelist ) ) return '';
	else {
		ob_start();
		if ( $top_ancestor != $id ) echo '<h2><a href="' . get_permalink($top_ancestor) . '">' . get_post( $top_ancestor )->post_title . '</a></h2>';
		echo "<ul>$pagelist</ul>";
		return ob_get_clean();
	}
}
function secondary_nav( $id ) {
	echo get_secondary_nav( $id );
}

/************* SOCIAL MEDIA ICON OUTPUT *****************/

function show_social_media_icons() {
	?>
	<div class="social-media-icons">
		<?php
		// an array of the options and the associated letters in the icon font and name of the site
		$sm_icons = array(
			'sm_facebook_url'	=> array( 'icon-facebook', 'Facebook' ),
			'sm_twitter_url'	=> array( 'icon-twitter', 'Twitter' ),
			'sm_instagram_url'	=> array( 'icon-instagram', 'Instagram' ),
			'sm_pinterest_url'	=> array( 'icon-pinterest', 'Pinterest' )
		);
		
		foreach( $sm_icons as $option_name=>$icon_props ) {
			$option_value = get_theme_mod( $option_name );
			if ( $option_value ) {
				?><a
					class="<?php echo $icon_props[0] ?>"
					href="<?php echo $option_value ?>"
					target="_blank"
					title="Visit us on <?php echo $icon_props[1] ?>"
				></a><?php
			}
		}
		?>
	</div>
	<?php
}



/************* ADD A RECOMMENDED PLUGIN DASHBOARD WIDGET *****************/
require_once('library/recommended-plugins-dashboard.php');


/************* ADD A HELP DASHBOARD WIDGET *****************/

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'add_help_dashboard_widget' );

// Function used in the action hook
function add_help_dashboard_widget() {
	add_meta_box('help_tutorials_dashboard_widget', 'Help Tutorials', 'help_dashboard_widget_function', 'dashboard', 'side');
}

// Function that outputs the contents of the dashboard widget
function help_dashboard_widget_function( $post, $callback_args ) {
	echo "Tutorial slideshows will go here.";
}



// generic function

function get_val( $value, $default ) {
	if ( !isset( $value ) || $value === NULL ) return $default;
	else return $value;
}
