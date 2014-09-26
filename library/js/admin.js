jQuery(document).ready( function($) {
	if ( $('.pw-toggle-customize[value=no]').is(':checked') ) {
		$('#pw-sidebars-customize').toggle(false);
	}
	
	$('.pw-toggle-customize[value=yes]').click( function() {
		$('#pw-sidebars-customize').toggle(true);
	});
	
	$('.pw-toggle-customize[value=no]').click( function() {
		$('#pw-sidebars-customize').toggle(false);
	});
	
	if ( String(window.location).indexOf( 'customize.php' ) != 0 ) {
		$('.accordion-section-title').each( function() {
			/*if ( $(this).html() == 'Header Image' ) {
				$(this).html('Header Image (logo)');
			}*/
			if ( $(this).html().indexOf( 'Header Image' ) != -1 ) {
				$(this).html( $(this).html().replace('Header Image', 'Logo') );
			}
		});
	}
	
	
	// set the thumbnail image for the header photo customize image controllers, so it doesn't try to fit huge full-size images into that tiny space.
	window.setTimeout( function() {
		$('[data-customize-tab=library] .choose-from-library-link').each( function() {
			var controller_id = $(this).parents('.customize-control').attr('id').replace('customize-control-', '');
			var controller = wp.customize.control.instance( controller_id );
			var thumbnail = $(this).data('thumbnail');
			if ( thumbnail != null && thumbnail != undefined && thumbnail != '' ) {
				controller.thumbnailSrc( $(this).data('thumbnail') );
			}
		});
	}, 100 );
	
	
	// remove widgets from the Page Builder plugin that I don't need
	$('.widget[id*=siteorigin-panels], .panel-type[data-class*=SiteOrigin_Panels_Widgets_], .panel-type[data-class*=SiteOrigin_Panels_Widget_], .widget[id*="_origin_"]').remove();
	
	// reorganize widgets
	$tinymce_widget_panel = $('[data-class=WP_Widget_Black_Studio_TinyMCE].panel-type');
	$tinymce_widget_panel.parent().prepend( $tinymce_widget_panel );
	
	// remove Add Media / Add Slider buttons from Page Builder tab (on the page/post editing screen)
	if ( String(window.location).indexOf( 'post.php' ) != 0 ) {
		window.setInterval( function() {
			if ( $('#wp-content-wrap').hasClass('panels-active') ) {
				$('#wp-content-media-buttons').toggle( false );
			} else {
				$('#wp-content-media-buttons').toggle( true );
			}
		}, 100 );
	}
	
	// remove slider options which are not width-responsive
	if ( String(window.location).indexOf( 'admin.php?page=metaslider' ) != 0 ) {
		$('#metaslider_configuration label[for=coin]').toggle(false);
	}
	
});

( function($) {
	// media library addition to the customize image controls on the theme customizations page
	if ( !wp.media ) return;
	
	wp.media.customMediaManager = {
		init: function() {
			// create the media frame
			this.frame = wp.media.frames.customMediaManager = wp.media({
				title: 'Choose Image',
				library: { type: 'image' },
				button: { text: 'Use this photo' }
			});
			
			this.frame.on( 'select', function() {
				// get the selected attachment and insert the value into the customize control
				var attachment = wp.media.customMediaManager.frame.state().get('selection').first();
				// the ID of this controller is in the id attribute of a parent element of the link we clicked on
				// the id attribute looks like id="customize-control-id_of_control"
				// this element we want to find has a class of 'customize-control'
				var $controller_el = wp.media.customMediaManager.$el.parents('.customize-control');
				var controller_id = $controller_el.attr('id').replace('customize-control-', '');
				var controller = wp.customize.control.instance( controller_id );
				
				// save setting. we want to use the attachment id, since some theme styles will use the thumbnail, and some theme styles will use larger images.
				controller.setting.set( attachment.id );
				var thumbnail = '';
				if ( attachment.attributes.sizes && attachment.attributes.sizes.thumbnail && attachment.attributes.sizes.thumbnail.url !== '' ) {
					thumbnail = attachment.attributes.sizes.thumbnail.url;
				} else {
					thumbnail = attachment.attributes.url;
				}
				controller.thumbnailSrc( thumbnail );
				wp.media.customMediaManager.$el.data( 'thumbnail', thumbnail );
			});
			
			$('[data-customize-tab=library] .choose-from-library-link').click( function( e ) {
				wp.media.customMediaManager.$el = $(this);
				e.preventDefault();
				wp.media.customMediaManager.frame.open();
			});
		}
	};
	
	wp.media.customMediaManager.init();
}(jQuery));