/***********************
revealable section
************************/

(function() {
	tinymce.create('tinymce.plugins.revealable_section', {
		init: function(editor, url) {
			editor.addButton('revealable_section', {
				title : 'Insert revealable section',
				image : url + '/../images/revealable-section-icon.png',
				onclick: function() {
					editor.windowManager.open({
						title: 'Insert revealable section',
						body: [{
							type: 'textbox',
							name: 'button_text',
							label: 'Show/hide button text'
						}],
						onsubmit: function( e ) {
							editor.insertContent( '[revealable_section button_text="' + e.data.button_text + '"]Revealable section goes here[/revealable_section]' );
						}
					});
				}
			});
			
			// Replace ugly shortcode notation with nice html
			editor.on('BeforeSetcontent', function(e){
				e.content = e.content.replace(/\[revealable_section button_text="(.*?)"\]([\S\s]*?)\[\/revealable_section\]/gim, '<hr class="mce_revealable_section_start" /><button class="nicebutton" disabled="disabled">$1</button>$2<hr class="mce_revealable_section_end" />');
			});
			
			// Replace nice html with ugly shortcode notation
			editor.on('PostProcess', function(e) {
				e.content = e.content.replace(/<hr class="mce_revealable_section_start" \/>[\S\s]*?<button [\S\s]*?>([\S\s]*?)<\/button>([\S\s]*?)<hr class="mce_revealable_section_end" \/>/gim, '[revealable_section button_text="$1"]$2[/revealable_section]');
			});
			
		},
		
		createControl: function(n, cm) {
			return null;
		},
		
		getInfo: function() {
			return {
				longname: 'Revealable Section',
				author: 'Ransom',
				version: '1'
			};
		}
	});
	
	tinymce.PluginManager.add( 'revealable_section', tinymce.plugins.revealable_section );
	
})();


/***********************
clear break
************************/

(function() {
	tinymce.create('tinymce.plugins.clear_break', {
		init: function(editor, url) {
			editor.addButton('clear_break', {
				title : 'Insert clearing break',
				image : url + '/../images/clear-break-icon.png',
				onclick: function() {
					editor.insertContent( '<hr class="clear-break" />' );
				}
			});
		},
		
		createControl: function(n, cm) {
			return null;
		},
		
		getInfo: function() {
			return {
				longname: 'Clear break',
				author: 'Ransom',
				version: '1'
			};
		}
	});
	
	tinymce.PluginManager.add( 'clear_break', tinymce.plugins.clear_break );
	
})();



/***********************
insert button
************************/

(function() {
	tinymce.create('tinymce.plugins.insert_button', {
		init: function(editor, url) {
			editor.addButton('insert_button', {
				title : 'Insert/edit button',
				image : url + '/../images/insert-button-icon.png',
				onclick: function() {
					var values = {
						button_text: '',
						link: 'http://',
						size: '100%',
						style: 'flatlink',
						position: 'inline'
					};
					
					var is_editing = false;
					var $selection = jQuery( editor.selection.getNode() );
					if ( $selection.data('insert-button') != undefined ) {
						is_editing = true;
						jQuery.extend( true, values, extractButtonPropertiesFromHTML( $selection[0].outerHTML ) );
					}
					
					var dialog_window = editor.windowManager.open({
						title: ( is_editing ? 'Edit button' : 'Insert button' ),
						body: [{
							type: 'textbox',
							name: 'button_text',
							label: 'Button text',
							value: values.button_text
						}, {
							type: 'textbox',
							name: 'link',
							label: 'Link URL',
							value: values.link
						}, {
							type: 'listbox',
							name: 'size',
							label: 'Size',
							value: values.size,
							values: [
								{ text: '60%', value: '60%' },
								{ text: '80%', value: '80%' },
								{ text: '100%', value: '100%' },
								{ text: '120%', value: '120%' },
								{ text: '140%', value: '140%' },
								{ text: '160%', value: '160%' },
								{ text: '180%', value: '180%' },
								{ text: '200%', value: '200%' },
							]
						}, {
							type: 'listbox',
							name: 'style',
							label: 'Button style',
							value: values.style,
							values: [
								{ text: 'Flat colored', value: 'flatlink' },
								{ text: 'Raised grey', value: 'nice-button' }
							]
						}, {
							type: 'listbox',
							name: 'position',
							label: 'Positioning',
							value: values.position,
							values: [
								{ text: 'Inline', value: 'inline' },
								{ text: 'Full width', value: 'full-width' }
							]
						}],
						onsubmit: function( e ) {
							if ( is_editing ) {
								// if editing, replace the content of the old button with a new one
								var $new_element = jQuery( generateHTML( e.data.button_text.replace('"', ''), e.data.link, e.data.size, e.data.style, e.data.position ) );
								$selection.replaceWith( $new_element );
								// make tinymce's editor select the new element so the user can hit the 'edit button' button again right away without issues
								editor.selection.select( $new_element[0] );
							} else {
								// insert a new button
								editor.insertContent( generateShortcode( e.data.button_text.replace('"', ''), e.data.link, e.data.size, e.data.style, e.data.position ) );
							}
						}
					});
				}
			});
			
			// Replace ugly shortcode notation with nice html
			editor.on('BeforeSetcontent', function(e){
				// get buttons
				var buttons = e.content.match(/\[insert_button .*?\/]/gi);
				if ( buttons != null ) {
					for (var i=0; i<buttons.length; i++) {
						var props = extractButtonPropertiesFromShortcode( buttons[i] );
						
						// replace this shortcode with something that looks nice in the tinymce editor
						var html_button = generateHTML( props.button_text, props.link, props.size, props.style, props.position );
						e.content = e.content.replace( buttons[i], html_button );
					}
				}
			});
			
			// Replace nice html with ugly shortcode notation
			editor.on('PostProcess', function(e) {
				var buttons = e.content.match(/<a[^>]* data-insert-button[= ].*?>.*?<\/a>/gi);
				if ( buttons != null ) {
					for (var i=0; i<buttons.length; i++) {
						var props = extractButtonPropertiesFromHTML( buttons[i] );
						var shortcode_button = generateShortcode( props.button_text, props.link, props.size, props.style, props.position );
						e.content = e.content.replace( buttons[i], shortcode_button );
					}
				}
			});
			
		},
		
		createControl: function(n, cm) {
			return null;
		},
		
		getInfo: function() {
			return {
				longname: 'Insert Button',
				author: 'Ransom',
				version: '1'
			};
		}
	});
	
	generateShortcode = function( button_text, link, size, style, position ) {
		return '[insert_button button_text="' + button_text + '" link="' + link + '" size="' + size + '" style="' + style + '" position="' + position + '" /]'
	};
	
	generateHTML = function( button_text, link, size, style, position ) {
		var css = 'font-size:' + size + ';';
		if ( position == 'full-width' ) css += 'display:block; text-align:center';
		return '<a data-insert-button data-link="' + link + '" data-size="' + size + '" data-style="' + style + '" data-position="' + position + '" class="' + style + '" style="' + css + '">' + button_text + '</a>';
	};
	
	extractButtonPropertiesFromHTML = function( string_to_search ) {
		return extractButtonProperties( string_to_search, />(.*?)<\/a>/i, /data-link="(.*?)"/i, /data-size="(.*?)"/i, /data-style="(.*?)"/i, /data-position="(.*?)"/i );
	};
	
	extractButtonPropertiesFromShortcode = function( string_to_search ) {
		return extractButtonProperties( string_to_search, /button_text="(.*?)"/i, /link="(.*?)"/i, /size="(.*?)"/i, /style="(.*?)"/i, /position="(.*?)"/i );
	};
	
	extractButtonProperties = function( string_to_search, button_text_regex, link_regex, size_regex, style_regex, position_regex ) {
		// default properties
		var properties = [{
			property: 'button_text',
			default: 'Button text',
			regex: button_text_regex
		}, {
			property: 'link',
			default: 'http://',
			regex: link_regex
		}, {
			property: 'size',
			default: '100%',
			regex: size_regex,
		}, {
			property: 'style',
			default: 'nice-button',
			regex: style_regex
		}, {
			property: 'position',
			default: 'inline',
			regex: position_regex
		}];
		
		var extracted_values = {};
		
		for ( var i=0; i<properties.length; i++ ) {
			var match_result = string_to_search.match( properties[i].regex );
			extracted_values[ properties[i].property ] = ( match_result == null ) ? properties[i].default : match_result[1];
		}
		
		return extracted_values;
	};
	
	tinymce.PluginManager.add( 'insert_button', tinymce.plugins.insert_button);
	
})();

