/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	//config.toolbar = 'Basic',
	config.toolbar = [
		//['Styles','Format','Font','FontSize'],
		//'/',
		['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
		//'/',
		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','Table','-','Link','Flash','Smiley','TextColor','BGColor']
	]
	//config.startupFocus  = true,
	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};
