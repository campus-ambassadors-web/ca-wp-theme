<?php
$rec_plugins = array();
// Register the new dashboard widget with the 'wp_dashboard_setup' action
// but only if this user is the admin, because it would be goofy to tell someone to install plugins if they don't have permissions to do so.
if ( current_user_can( 'install_plugins' ) ) {
	add_action('wp_dashboard_setup', 'add_rec_plugin_dashboard_widget' );
}

// Function used in the action hook
function add_rec_plugin_dashboard_widget() {
	// check if recommended plugins are active. if any are not, create a dashboard widget to notify the user.
	global $rec_plugins;
	$rec_plugins = array(
		'warning' => array (
			'Ninja Firewall' => array(
				'desc' => 'Protects your site against many attacks, including denial-of-service attacks, brute force loign attempts, database injection attacks, and more.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=ninjafirewall'),
				'is_active' => is_plugin_active( 'ninjafirewall/ninjafirewall.php' )
			),
			'WP Super Cache' => array(
				'desc' => 'Dramatically decreases the time it takes for your webpages to load. Protects your site from crashing in the event of large spikes in traffic.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=wp-super-cache'),
				'is_active' => is_plugin_active( 'wp-super-cache/wp-cache.php' )
			),
			'GitHub Updater' => array(
				'desc' => 'Allows automatic updating of plugins and themes hosted on GitHub (like this theme!).',
				'url' => 'https://github.com/afragen/github-updater',
				'is_active' => is_plugin_active( 'github-updater/github-updater.php' )
			)
		),
		'info' => array (
			'Google XML Sitemaps' => array(
				'desc' => 'This plugin will generate a special XML sitemap which will help search engines to better index your blog.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=google-sitemap-generator'),
				'is_active' => is_plugin_active( 'google-sitemap-generator/sitemap.php' )
			),
			'Page Builder by SiteOrigin' => array(
				'desc' => 'Build responsive page layouts using the widgets you know and love using this simple drag and drop page builder. Very useful when combined with the Black Studio TinyMCE Widget plugin.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=siteorigin-panels'),
				'is_active' => is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ),
			),
			'Black Studio TinyMCE Widget' => array(
				'desc' => 'Adds a WYSIWYG widget based on the standard TinyMCE WordPress visual editor.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=black-studio-tinymce-widget'),
				'is_active' => is_plugin_active( 'black-studio-tinymce-widget/black-studio-tinymce-widget.php' )
			),
			'Google Calendar Events' => array(
				'desc' => 'Parses Google Calendar feeds and displays the events as a calendar grid or list on a page, post or widget.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=google-calendar-events'),
				'is_active' => is_plugin_active( 'google-calendar-events/google-calendar-events.php' )
			),
			'MetaSlider' => array(
				'desc' => 'Easily create image slideshows.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=ml-slider'),
				'is_active' => is_plugin_active( 'ml-slider/ml-slider.php' ),
			),
			/*'MailPoet Newsletters' => array(
				'desc' => 'Send newsletters, post notifications or autoresponders from WordPress easily and beautifully.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=wysija-newsletters'),
				'is_active' => is_plugin_active( 'wysija-newsletters/index.php' )
			),*/
			'Google Analytics Dashboard for WP' => array(
				'desc' => 'Displays Google Analytics Reports and Real-Time Statistics in your Dashboard. Automatically inserts the tracking code in every page of your website.',
				'url' => admin_url('/plugin-install.php?tab=plugin-information&plugin=google-analytics-dashboard-for-wp'),
				'is_active' => is_plugin_active( 'google-analytics-dashboard-for-wp/gadwp.php' )
			)
		)
	);
	
	// if any of the above plugins are not active, proceed with adding the meta box.
	$add_box = false;
	foreach( $rec_plugins as $type=>$plugins ) {
		foreach( $plugins as $name=>$plugin ) {
			if ( !$plugin['is_active'] ) {
				$add_box = true;
				break;
			}
		}
	}
	
	if ( $add_box ) add_meta_box('rec_plugins_dashboard_widget', 'Recommended Plugins', 'rec_plugin_dashboard_widget_function', 'dashboard', 'normal', 'high');
}

// Function that outputs the contents of the dashboard widget
function rec_plugin_dashboard_widget_function( $post, $callback_args ) {
	global $rec_plugins;
	
	$output = '';
	// add suggestions for highly recommended plugins
	foreach ( $rec_plugins['warning'] as $name=>$plugin ) {
		if ( !$plugin['is_active'] ) {
			$output .= get_rec_plugin_output( $name, $plugin );
		}
	}
	if ( $output ) {
		echo "<h4 class='rec-plugins-warning'>Please install/activate the following strongly recommended plugins:</h4>";
		echo "<div class='rec-plugins-warning'>$output</div>";
	}
	
	$output = ''; // reset the output
	// add suggestions for nifty plugins that play well with this theme
	foreach ( $rec_plugins['info'] as $name=>$plugin ) {
		if ( !$plugin['is_active'] ) {
			$output .= get_rec_plugin_output( $name, $plugin );
		}
	}
	if ( $output ) {
		echo "<h4 class='rec-plugins-info'>Install/activate the following recommended plugins for greatly increased functionality:</h4>";
		echo "<div class='rec-plugins-info'>$output</div>";
	}
}

function get_rec_plugin_output( $name, $plugin ) {
	return "<h4>$name</h4><p>{$plugin['desc']}<br />&gt; <a href='{$plugin['url']}'>Details</a></p>";
}

if ( current_user_can( 'install_plugins' ) ) {
	add_action('admin_notices', 'google_analytics_conflict_notice');
}
function google_analytics_conflict_notice() {
	$ga_id = get_theme_mod('google_analytics_id', '');
	if ( is_plugin_active( 'google-analytics-dashboard-for-wp/gadwp.php' ) && !empty( $ga_id ) ) : ?>
		<div class="error">
			<p>Since you are using the <b>Google Analytics Dashboard</b> plugin, please remove the <b>Google Analytics Tracking ID</b> information from the <a href="<?php echo admin_url('/customize.php') ?>">theme customizations page</a>. Otherwise, tracking information will not be accurate.</p>
		</div>
	<?php
	endif;
}

?>