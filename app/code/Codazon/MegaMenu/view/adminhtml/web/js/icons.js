/**
 * Copyright Â© 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'jquery/ui',
    'prototype',
	'Codazon_MegaMenu/js/jquery.tmpl',
], function(jQuery, $t){

window.Icons = {
    textareaElementId: null,
    iconsContent: null,
    dialogWindow: null,
    dialogWindowId: 'icons-chooser',
    overlayShowEffectOptions: null,
    overlayHideEffectOptions: null,
    insertFunction: 'Icons.insertIcon',
	title: $t('Insert Icon...'),
	tmplId: 'menu-item-icons-tmpl',
    init: function(textareaElementId, insertFunction) {
        if ($(textareaElementId)) {
            this.textareaElementId = textareaElementId;
        }
        if (insertFunction) {
            this.insertFunction = insertFunction;
        }
    },

    resetData: function() {
        this.iconsContent = null;
        this.dialogWindow = null;
    },
	getTmplHtml: function(tmplId,data){
		if(typeof data == 'undefined'){
			var data = {};	
		}
		var $content = jQuery('<div />');
		jQuery('#'+tmplId).tmpl(data).appendTo($content);
		return $content.html();
	},
    openIconChooser: function(id,tmplId,title,data) {
		this.textareaElementId = id;
        if (this.iconsContent == null || (tmplId != this.tmplId )) {
            this.iconsContent = this.getTmplHtml(tmplId,data);
			this.tmplId = id;
        }
		if(title){
			this.title = title;	
		}
        if (this.iconsContent) {
            this.openDialogWindow(this.iconsContent);
        }
    },
    openDialogWindow: function (iconsContent) {
		var self = this;
        var windowId = this.dialogWindowId;
        jQuery('<div id="' + windowId + '">' + Icons.iconsContent + '</div>').modal({
            title: self.title,
            type: 'slide',
            buttons: [],
            closed: function (e, modal) {
                modal.modal.remove();
            }
        });

        jQuery('#' + windowId).modal('openModal');

        iconsContent.evalScripts.bind(iconsContent).defer();
    },
    closeDialogWindow: function() {
        jQuery('#' + this.dialogWindowId).modal('closeModal');
    },
    prepareIconRow: function(varValue, varLabel) {
        var value = (varValue).replace(/"/g, '&quot;').replace(/'/g, '\\&#39;');
        var content = '<a href="#" onclick="'+this.insertFunction+'(\''+ value +'\');return false;">' + varLabel + '</a>';
        return content;
    },
    insertIcon: function(value) {
		var self = this;
        var windowId = this.dialogWindowId;
        jQuery('#' + windowId).modal('closeModal');
        var textareaElm = $(this.textareaElementId);
		
		if (textareaElm) {
			if(jQuery(textareaElm).is('textarea')){
				var scrollPos = textareaElm.scrollTop;
				updateElementAtCursor(textareaElm, value);
				textareaElm.focus();
				textareaElm.scrollTop = scrollPos;
				textareaElm = null;
			}else{
				textareaElm.value = value;
				if(jQuery('#preview-'+this.textareaElementId).length){
					var preview = jQuery('#preview-'+this.textareaElementId);
					jQuery('i',preview).removeAttr('class').addClass('fa fa-'+value);
				}
			}
			jQuery(textareaElm).trigger('change');
		}
        return;
    },
	insertTemplate: function(tmplId) {
        var windowId = this.dialogWindowId;
        jQuery('#' + windowId).modal('closeModal');
        var textareaElm = $(this.textareaElementId);	
		var html = jQuery('#'+tmplId).html().trim();
		
		if (textareaElm) {
			if(textareaElm.type == 'textarea'){
				var scrollPos = textareaElm.scrollTop;
				textareaElm.value = html;
				textareaElm.focus();
				textareaElm.scrollTop = scrollPos;
				textareaElm = null;
			}
		}
        return;
    }
};

window.MagentoiconPlugin = {
    editor: null,
    icons: null,
    textareaId: null,
    setEditor: function(editor) {
        this.editor = editor;
    },
    loadChooser: function(url, textareaId) {
        this.textareaId = textareaId;
        if (this.icons == null) {
            new Ajax.Request(url, {
                parameters: {},
                onComplete: function (transport) {
                    if (transport.responseText.isJSON()) {
                        Icons.init(null, 'MagentoiconPlugin.insertIcon');
                        this.icons = transport.responseText.evalJSON();
                        this.openChooser(this.icons);
                    }
                }.bind(this)
             });
        } else {
            this.openChooser(this.icons);
        }
        return;
    },
    openChooser: function(icons) {
        Icons.openIconChooser(icons);
    },
    insertIcon : function (value) {
        if (this.textareaId) {
            Icons.init(this.textareaId);
            Icons.insertIcon(value);
        } else {
            Icons.closeDialogWindow();
            this.editor.execCommand('mceInsertContent', false, value);
        }
        return;
    }
};

});