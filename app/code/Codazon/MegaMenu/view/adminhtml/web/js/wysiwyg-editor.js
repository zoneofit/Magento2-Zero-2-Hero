define([
    "jquery",
    "tinymce",
    "Magento_Ui/js/modal/modal",
    "prototype",
    "mage/adminhtml/events",
	"mage/adminhtml/wysiwyg/widget",
	"Codazon_MegaMenu/js/browser",
	"Codazon_MegaMenu/js/icons",
], function(jQuery, tinyMCE, modal){
	Window.keepMultiModalWindow = true;
	window.menuWysiwygEditor = {
		overlayShowEffectOptions : null,
		overlayHideEffectOptions : null,
		modal: null,
		open : function(editorUrl, elementId) {
			window.firedElementId = elementId;
			if (editorUrl && elementId) {
				jQuery.ajax({
					url: editorUrl,
					cache:false,
					data: {
						element_id: elementId + '_editor',
						store_id: ''
					},
					showLoader: true,
					dataType: 'html',
					success: function(data, textStatus, transport) {
						this.openDialogWindow(data, elementId);
					}.bind(this)
				});
			}
		},
		openDialogWindow : function(data, elementId) {
			var self = this;
			if (this.modal) {
				this.modal.html(jQuery(data).html());
			} else {
				this.modal = jQuery(data).modal({
					title: 'WYSIWYG Editor',
					modalClass: 'magento',
					type: 'slide',
					firedElementId: elementId,
					buttons: [{
						text: jQuery.mage.__('Cancel'),
						click: function () {
							self.closeDialogWindow(this);
						}
					},{
						text: jQuery.mage.__('Submit'),
						click: function () {
							self.okDialogWindow(this);
						}
					}],
					close: function () {
						self.closeDialogWindow(this);
					}
				});
			}
			this.modal.modal('openModal');
			$(elementId + '_editor').value = $(elementId).value;
		},
		okDialogWindow : function(dialogWindow) {
			if (dialogWindow.options.firedElementId) {
				wysiwygObj = eval('wysiwyg'+dialogWindow.options.firedElementId+'_editor');
				wysiwygObj.turnOff();
				if (tinyMCE.get(wysiwygObj.id)) {
					$(dialogWindow.options.firedElementId).value = tinyMCE.get(wysiwygObj.id).getContent();
				} else {
					if ($(dialogWindow.options.firedElementId+'_editor')) {
						$(dialogWindow.options.firedElementId).value = $(dialogWindow.options.firedElementId+'_editor').value;
					}
				}
				$(window.firedElementId).value = $(window.firedElementId + '_editor').value;
				//tinyMCE.editors[dialogWindow.options.firedElementId].load();
			}
			this.closeDialogWindow(dialogWindow);
		},
		closeDialogWindow : function(dialogWindow) {
			// remove form validation event after closing editor to prevent errors during save main form
			if (typeof varienGlobalEvents != undefined && editorFormValidationHandler) {
				varienGlobalEvents.removeEventHandler('formSubmit', editorFormValidationHandler);
			}
	
			//IE fix - blocked form fields after closing
			try {
				$(dialogWindow.options.firedElementId).focus();
			} catch (e) {
				//ie8 cannot focus hidden elements
			}
	
			//destroy the instance of editor
			wysiwygObj = eval('wysiwyg'+dialogWindow.options.firedElementId+'_editor');
			if (tinyMCE.get(wysiwygObj.id)) {
			   tinyMCE.execCommand('mceRemoveControl', true, wysiwygObj.id);
			}
	
			dialogWindow.closeModal();
			Windows.overlayShowEffectOptions = this.overlayShowEffectOptions;
			Windows.overlayHideEffectOptions = this.overlayHideEffectOptions;
		}
	};
});