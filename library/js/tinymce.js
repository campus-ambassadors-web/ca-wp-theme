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
				title : 'Insert button',
				image : url + '/../images/insert-button-icon.png',
				onclick: function() {
					editor.windowManager.open({
						title: 'Insert button',
						body: [{
							type: 'textbox',
							name: 'button_text',
							label: 'Button text'
						}, {
							type: 'textbox',
							name: 'link',
							label: 'Link'
						}, {
							type: 'listbox',
							name: 'size',
							label: 'Size',
							value: '100%',
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
							values: [
								{ text: 'Raised grey', value: 'nice-button' },
								{ text: 'Flat blue', value: 'flatlink' }
							]
						}],
						onsubmit: function( e ) {
							editor.insertContent( '[insert_button button_text="' + e.data.button_text.replace('"', '') + '" link="' + e.data.link + '" size="' + e.data.size + '" style="' + e.data.style + '" /]' );
						}
					});
				}
			});
			
			// Replace ugly shortcode notation with nice html
			editor.on('BeforeSetcontent', function(e){
				e.content = e.content.replace(/\[insert_button button_text="([^"]*)" link="([^"]*)" size="([^"]*)" style="([^"]*)" \/]/gim, '<a class="$4" style="font-size:$3" data-href="$2">$1</a>');
			});
			
			// Replace nice html with ugly shortcode notation
			editor.on('PostProcess', function(e) {
				e.content = e.content.replace(/<a class="([^"]*)" style="font-size:([^"]*)" data-href="([^"]*)"[^"]*>([^"]*)<\/a>/gim, '[insert_button button_text="$4" link="$3" size="$2" style="$1" /]');
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
	
	tinymce.PluginManager.add( 'insert_button', tinymce.plugins.insert_button);
	
})();