
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
			$('#customize-control-footer_bg_color input[type=text]').val( footer_colors[ $(this).val() ] );
			$('#customize-control-footer_bg_color .wp-color-picker').change();
		}
	});
	
	
}(jQuery));