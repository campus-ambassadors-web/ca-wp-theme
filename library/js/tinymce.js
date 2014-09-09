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
