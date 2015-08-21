
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
				text_link_color: '#7c0101',
				nav_bg_color: '#7c0101',
				sidebar_header_bg_color: '#7c0101',
				sidebar_bg_color: '#0a0a0a',
				page_bg_color: '#444f5e'
			},
			footer_art_preset: 'city2.png'
		},
		sandy_dusk: {
			colors: {
				primary_bg_color: '#d6c1a9',
				header_bg_color: '#231300',
				footer_bg_color: '#000000',
				accent_text_color: '#6b3504',
				text_link_color: '#a04800',
				nav_bg_color: '#874b0c',
				sidebar_header_bg_color: '#89440c',
				sidebar_bg_color: '#231300',
				page_bg_color: '#523d2d'
			},
			footer_art_preset: 'desert.png'
		},
		evergreen: {
			colors: {
				primary_bg_color: '#ffffff',
				header_bg_color: '#1b3025',
				footer_bg_color: '#05724d',
				accent_text_color: '#073977',
				text_link_color: '#00895b',
				nav_bg_color: '#005438',
				sidebar_header_bg_color: '#0a6546',
				sidebar_bg_color: '#e0e0e0',
				page_bg_color: '#cad2ca'
			},
			footer_art_preset: 'forest.png'
		},
		rolling_hills: {
			colors: {
				primary_bg_color: '#ede7dc',
				header_bg_color: '#d1bfb1',
				footer_bg_color: '#8cca39',
				accent_text_color: '#2c5b2c',
				text_link_color: '#479b41',
				nav_bg_color: '#724716',
				sidebar_header_bg_color: '#996b3d',
				sidebar_bg_color: '#ffffff',
				page_bg_color: '#dcdcd9'
			},
			footer_art_preset: 'hills.png'
		},
		country_cottage : {
			colors: {
				primary_bg_color: '#e2dbbe',
				header_bg_color: '#ffffff',
				footer_bg_color: '#005496',
				accent_text_color: '#5b5b5b',
				text_link_color: '#036cc1',
				nav_bg_color: '#073977',
				sidebar_header_bg_color: '#073977',
				sidebar_bg_color: '#ffffff',
				page_bg_color: '#ffffff'
			},
			footer_art_preset: 'house.png'
		},
		purple_mountains: {
			colors: {
				primary_bg_color: '#eff4f2',
				header_bg_color: '#cac3d2',
				footer_bg_color: '#0b9041',
				accent_text_color: '#346d36',
				text_link_color: '#7a6ad1',
				nav_bg_color: '#473f68',
				sidebar_header_bg_color: '#7d6eb3',
				sidebar_bg_color: '#ffffff',
				page_bg_color: '#382d38'
			},
			footer_art_preset: 'mountains.png'
		},
		grey_and_gold: {
			colors: {
				primary_bg_color: '#d8d8d8',
				header_bg_color: '#bfaa78',
				footer_bg_color: '#bfaa78',
				accent_text_color: '#917931',
				text_link_color: '#997226',
				nav_bg_color: '#5b490b',
				sidebar_header_bg_color: '#bfaa78',
				sidebar_bg_color: '#212121',
				page_bg_color: '#494949'
			},
			footer_art_preset: 'none'
		}/*,
		crimson_nightlife: {
			primary_bg_color:
			header_bg_color:
			footer_bg_color:
			accent_text_color:
			nav_bg_color:
			sidebar_header_bg_color:
			sidebar_bg_color:
			page_bg_color:
			footer_art_preset:
		}*/
	}
	
	// keep trying to find this select element until it's finally loaded
	var v = setInterval( function() {
		if ( $('#customize-control-color_preset select').length == 0 ) return;
		
		clearInterval( v );
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
	}, 20 );
	
	
	
	function updateColorPicker( setting, newval ) {
		$('#customize-control-' + setting + ' input[type=text]').val( newval );
		$('#customize-control-' + setting + ' .wp-color-picker').change();
	}
	
}(jQuery));