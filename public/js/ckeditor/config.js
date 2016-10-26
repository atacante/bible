/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	//config.toolbar = 'Basic',
	config.toolbar = [
		//['Styles','Format','Font','FontSize'],
		[(!!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform))?'':'webSpeechEnabled', 'webSpeechSettings'],
		//'/',
		['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
		//'/',
		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','Table','-','Link','Flash','Smiley','TextColor','BGColor']
	];

	config.toolbarGroups =
		[
			{name: 'ckwebspeech'}  //Add the CKWebSpeech button on the toolbar
		];
	config.scayt_autoStartup = true;
	//config.startupFocus  = true,
	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	//Initializes and loads the resources CKWebSpeech
	config.extraPlugins = 'ckwebspeech';
	/* Initializes the default language, if this line is not added by default starts with
	 English-United States (en-us) */
	config.ckwebspeech = {'culture' : 'en-us',
		'commandvoice' : 'command',   //trigger voice commands
		'commands': [                 //command list
			{'newline': 'new line'},            //trigger to add a new line in CKEditor
			{'newparagraph': 'new paragraph'},  //trigger to add a new paragraph in CKEditor
			{'undo': 'undo'},                   //trigger to undo changes in CKEditor
			{'redo': 'redo'}                    //trigger to redo changes in CKEditor
		]
	};
};
