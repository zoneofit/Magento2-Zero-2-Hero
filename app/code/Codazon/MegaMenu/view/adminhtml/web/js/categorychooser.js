require([
	"jquery","Magento_Ui/js/modal/modal","prototype","mage/adminhtml/wysiwyg/widget"
],function(jQuery,modal) {		
	WysiwygWidget.categoryChooser = Class.create();
	WysiwygWidget.categoryChooser.prototype = {
		// HTML element A, on which click event fired when choose a selection
		chooserId: null,
		// Source URL for Ajax requests
		chooserUrl: null,
		// Chooser config
		config: null,
		// Chooser dialog window
		dialogWindow: null,
		// Chooser content for dialog window
		dialogContent: null,
		overlayShowEffectOptions: null,
		overlayHideEffectOptions: null,

		initialize: function(chooserId, chooserUrl, config) {
			this.chooserId = chooserId;
			this.chooserUrl = chooserUrl;
			this.config = config;
		},

		getResponseContainerId: function() {
			return 'responseCnt' + this.chooserId;
		},

		getChooserControl: function() {
			return $(this.chooserId + 'control');
		},
		getElement: function() {
			return $(this.chooserId);
		},
		getElementLabel: function() {
			return '';
		},
		open: function() {
			$(this.getResponseContainerId()).show();
		},
		close: function() {
			$(this.getResponseContainerId()).hide();
			this.closeDialogWindow();
		},
		choose: function(event) {
			// Open dialog window with previously loaded dialog content
			if (this.dialogContent) {
				this.openDialogWindow(this.dialogContent);
				return;
			}
			// Show or hide chooser content if it was already loaded
			var responseContainerId = this.getResponseContainerId();

			// Otherwise load content from server
			new Ajax.Request(this.chooserUrl,
				{
					parameters: {element_value: this.getElementValue(), element_label: this.getElementLabelText()},
					onSuccess: function(transport) {
						try {
							widgetTools.onAjaxSuccess(transport);
							this.dialogContent = widgetTools.getDivHtml(responseContainerId, transport.responseText);
							this.openDialogWindow(this.dialogContent);
						} catch(e) {
							alert({
								content: e.message
							});
						}
					}.bind(this)
				}
			);
		},
		openDialogWindow: function (content) {
			this.dialogWindow = jQuery('<div/>').modal({
				title: this.config.buttons.open,
				type: 'slide',
				buttons: [],
				opened: function () {
					jQuery(this).addClass('magento_message');
				},
				closed: function (e, modal) {
					modal.modal.remove();
					this.dialogWindow = null;
				}
			});

			this.dialogWindow.modal('openModal').append(content);
		},
		closeDialogWindow: function () {
			this.dialogWindow.modal('closeModal').remove();
		},
		getElementValue: function(value) {
			return this.getElement().value;
		},
		getElementLabelText: function(value) {
			return this.getElementLabel().innerHTML;
		},
		setElementValue: function(value) {
			this.getElement().value = value.replace('category/','');
		},
		setElementLabel: function(value) {
			this.getElementLabel().innerHTML = value;
		}
	};
});