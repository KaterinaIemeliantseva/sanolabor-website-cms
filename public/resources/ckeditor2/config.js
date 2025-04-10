/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';


	config.toolbar = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord',  '-', 'Undo', 'Redo' ] },
		//{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', 'TextColor', 'FontSize', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  },
		'/',
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
		{ name: 'styles', items: [  'Format' ] },
		{ name: 'embeddButtons', items: ['Youtube'] }
	];

	CKEDITOR.config.colorButton_colors =
	'882287,000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
	'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
	'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
	'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
	'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';

    config.extraPlugins = 'maximize';

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,Colors';

	// Se the most common block elements.
	config.format_tags = 'p;h2;h3';


	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;link:advanced';


	//config.width = '500px';
	//config.height = '200px';
	config.height = '250px';

    config.contentsCss = 'http://gorlek.ha2net.com/css/app.css';

    //config.extraPlugins = 'webkit-span-fix';

    //config.extraPlugins += (config.extraPlugins ? ',youtube' : 'youtube' );
    //CKEDITOR.plugins.addExternal('youtube', 'plugins/youtube/');

    //config.extraPlugins = 'youtube';

    config.filebrowserBrowseUrl = '/public/resources/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=sl';
    config.filebrowserUploadUrl = '/public/resources/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=sl';
    config.filebrowserImageBrowseUrl = '/public/resources/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&lang=sl';
	config.allowedContent = true;
};
