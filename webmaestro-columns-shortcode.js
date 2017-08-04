
(function() {
	tinymce.PluginManager.add('webmaestro_columns_shortcode', function( editor, url ) {
		editor.addButton( 'webmaestro_columns_shortcode', {
			text: 'Insert columns',
			icon: false,
			type: 'menubutton',
			menu: [
				{
					text: '1/2 + 1/2',
					onclick: function() {
					editor.insertContent('<p>[columns]</p><p>Column 1 ...</p><p>[-]</p><p>Column 2 ...</p><p>[/columns]</p>');
					}
				},
				{
					text: '1/3 + 1/3 + 1/3',
					onclick: function() {
						editor.insertContent('<p>[columns]</p><p>Column 1 ...</p><p>[-]</p><p>Column 2 ...</p><p>[-]</p><p>Column 3 ...</p><p>[/columns]</p>');
					}
				},
				{
					text: ' 2/3 + 1/3',
					onclick: function() {
						editor.insertContent('<p>[columns 8-4]</p><p>Column 1 ...</p><p>[-]</p><p>Column 2 ...</p><p>[/columns]</p>');
					}
				},
				
				{
					text: ' 1/3 + 2/3',
					onclick: function() {
						editor.insertContent('<p>[columns 4-8]</p><p>Column 1 ...</p><p>[-]</p><p>Column 2 ...</p><p>[/columns]</p>');
					}
				},
			]
		});
	});
})();