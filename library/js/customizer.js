
( function($) {
	
	// change colors associated with default footer images in the customizer
	var footer_colors = {
		'city1.png':	'#000000',
		'city2.png':	'#000000',
		'desert.png':	'#000000',
		'forest.png':	'#05724d',
		'hills.png':	'#8cca39',
		'house.png':	'#005496',
		'manger_cross.png': '#000000',
		'mountains.png':'#0b9041'
	};
	$('#customize-control-footer_art_preset select').change( function() {
		if ( footer_colors[ $(this).val() ] != undefined ) {
			updateColorPicker( 'footer_bg_color', footer_colors[ $(this).val() ] );
		}
	});
	
	
	
	
	var color_schemes = {
		crimson_nightlife: {
			colors: {
				primary_bg_color: '#b4b9c4',
				header_bg_color: '#09080a',
				footer_bg_color: '#000000',
				accent_text_color: '#7c0101',
				nav_bg_color: '#7c0101',
				sidebar_header_bg_color: '#7c0101',
				sidebar_bg_color: '#0a0a0a',
				main_bg_color: '#e1e6ef'
			},
			footer_art_preset: 'city2.png'
		},
		rolling_hills: {
			colors: {
				primary_bg_color: '#ede7dc',
				header_bg_color: '#d1bfb1',
				footer_bg_color: '#8cca39',
				accent_text_color: '#2c5b2c',
				nav_bg_color: '#724716',
				sidebar_header_bg_color: '#996b3d',
				sidebar_bg_color: '#ffffff',
				main_bg_color: '#ffffff'
			},
			footer_art_preset: 'hills.png'
		},
		purple_mountains: {
			colors: {
				primary_bg_color: '#e9e0cc',
				header_bg_color: '#cac3d2',
				footer_bg_color: '#0b9041',
				accent_text_color: '#643804',
				nav_bg_color: '#473f68',
				sidebar_header_bg_color: '#7d6eb3',
				sidebar_bg_color: '#ffffff',
				main_bg_color: '#ffffff'
			},
			footer_art_preset: 'mountains.png'
		}/*,
		crimson_nightlife: {
			primary_bg_color:
			header_bg_color:
			footer_bg_color:
			accent_text_color:
			nav_bg_color:
			sidebar_header_bg_color:
			sidebar_bg_color:
			main_bg_color:
			footer_art_preset:
		}*/
	}
	
	// when the color scheme preset dropdown is changed, change all these other settings too
	$('#customize-control-color_preset select').change( function() {
		var scheme = color_schemes[ $(this).val() ];
		if ( scheme != undefined ) {
			// update colors
			$.each( scheme.colors, function( key, val ) {
				updateColorPicker( key, val );
			});
			// update footer art dropdown
			var $footer_art_dropdown = $('#customize-control-footer_art_preset select');
			$footer_art_dropdown.find('option[selected]').removeAttr('selected');
			$footer_art_dropdown.find('option[value="' + scheme.footer_art_preset + '"]').attr('selected', 'selected');
			$footer_art_dropdown.change();
		}
	});
	
	
	
	
	function updateColorPicker( setting, newval ) {
		$('#customize-control-' + setting + ' input[type=text]').val( newval );
		$('#customize-control-' + setting + ' .wp-color-picker').change();
	}
	
}(jQuery));