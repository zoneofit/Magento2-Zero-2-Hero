/**
 * Copyright © 2016 Codazon. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            menuLayout: 'Codazon_MegaMenu/js/menu-layout',
			jqueryTmpl: "Codazon_MegaMenu/js/jquery.tmpl",
			cdzJqueryUi: "Codazon_MegaMenu/js/jquery-ui.min",
			categoryChooser: "Codazon_MegaMenu/js/categorychooser",
			megamenu: 'Codazon_MegaMenu/js/menu',
			wysiwygEditor: 'Codazon_MegaMenu/js/wysiwyg-editor'
        }
    },
	shim:{
		"Codazon_MegaMenu/js/menu-layout": ["jqueryTmpl","cdzJqueryUi","categoryChooser","wysiwygEditor"],
		"Codazon_MegaMenu/js/jquery.tmpl": ["jquery"],
		"Codazon_MegaMenu/js/jquery-ui.min": ["jquery/jquery-ui"],
		"Codazon_MegaMenu/js/menu": ["jquery"],
		"Codazon_MegaMenu/js/wysiwyg-editor": ["jquery"]
	}
};
