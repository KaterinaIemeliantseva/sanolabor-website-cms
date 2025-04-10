/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'sl';
	// config.uiColor = '#AADC6E';

    config.toolbar = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord',  '-', 'Undo', 'Redo' ] },
		//{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: ['FontSize', 'Bold', 'Italic', 'Strike', 'Underline', 'TextColor', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],  },
		'/',
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
		{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
		{ name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Maximize', 'Save'  ] },
		{ name: 'styles', items: [  'Format'] },
		{ name: 'embeddButtons', items: ['Youtube'] }
        //{ name: 'colors'},
		//{ name: 'colordialog' }
	];

    //CKEDITOR.config.extraPlugins = 'colorbutton';

    config.format_tags = 'navadni;naslov1;naslov2;izpostavljeno;podpis';
    config.format_navadni = {name: 'Navaden', element: 'p', attributes: { 'class': '' }};
    config.format_naslov1 = {name: 'Naslov', element: 'h2', attributes: { 'class': '' }};
    config.format_naslov2 = {name: 'Podnaslov', element: 'h3', attributes: { 'class': '' }};
    config.format_izpostavljeno = {name: 'Izpostavljeno', element: 'div', attributes: { 'class': 'clanek_note' }};
    config.format_podpis = {name: 'Podpis', element: 'p', attributes: { 'class': 'signature' }};


	CKEDITOR.config.colorButton_colors =
	'B0008E,000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
	'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
	'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
	'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
	'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';


	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	//config.removeButtons = 'Subscript,Superscript,Colors';

	// Se the most common block elements.
    //config.format_tags = 'p;h2;h3;h4';


    // Make dialogs simpler.
    config.removeDialogTabs = 'image:advanced;link:advanced';


    //config.width = '500px';
    //config.height = '200px';
	config.height = '300px';
	
	//config.pasteFromWordRemoveFontStyles = true;
	//config.pasteFromWordRemoveStyles = true;
	//config.CleanWordKeepsStructure = true;
	//config.pasteFromWordPromptCleanup = true;
	//config.pasteFromWordCleanupFile = 'custom';

    config.contentsCss = 'http://sanolaborweb.ha2net.com/css/app.css';

	config.extraPlugins = 'maximize,save,youtube,font,richcombo,floatpanel,listblock,panel';


    //config.extraPlugins = 'webkit-span-fix';

    //config.extraPlugins += (config.extraPlugins ? ',youtube' : 'youtube' );
    //CKEDITOR.plugins.addExternal('youtube', 'plugins/youtube/');

    //config.extraPlugins = 'youtube';

    config.filebrowserBrowseUrl = '/public/resources/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=sl';
    config.filebrowserUploadUrl = '/public/resources/filemanager/dialog.php?type=2&editor=ckeditor&fldr=&lang=sl';
    config.filebrowserImageBrowseUrl = '/public/resources/filemanager/dialog.php?type=1&editor=ckeditor&fldr=&lang=sl';
	config.allowedContent = true;
	
	
};
