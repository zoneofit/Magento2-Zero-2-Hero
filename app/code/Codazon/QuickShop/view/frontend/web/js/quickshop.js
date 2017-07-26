(function (factory) {
    if (typeof define === "function" && define.amd) {
        define([
            "jquery",
            "jquery/ui",
			"Magento_Ui/js/modal/modal",
			"Codazon_QuickShop/js/jquery.nicescroll.min"
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($,ui,modal) {
	"use strict";
	
	$.widget('custom.CodazonQuickShop', {
		options: {
			itemClass: '.products.grid .item.product-item, .products.list .item.product-item',
			qsLabel: 'Quick Shop',
			handlerClass: 'qs-button',
			baseUrl: '/',
			modalId: 'quickshop',
			autoAddButtons: true,
			target: '.product-item-info',
			currentUrl: ''
		},
		_create: function(){
			 this._buildQuickShop(this.options);
		 },
		_addButton: function(config){
			if(config.autoAddButtons){
				$(config.itemClass).each(function() {
					var $elem = $(this);
					if($elem.find('.'+config.handlerClass).length == 0){
						var productId = $elem.find('.price-final_price').data('product-id');
						var url = config.baseUrl + 'quickshop/index/view/id/' + productId;
						var html = '<div class="qs-btn-container"><a class="'+config.handlerClass+'" href="javascript:void(0)" data-href="'+url+'"><span>';
						html += config.qsLabel;
						html += '</span></a></div>';
						$elem.find(config.target).prepend(html);
					}
				});
			}
		},
		_buildQuickShop: function(config){
			var self = this;
			this._addButton(config);
			var $modal = $('#'+config.modalId);
			
			var qsModal = $modal.modal({
				innerScroll: true,
				title: config.qsLabel,
				//trigger: '.'+config.handlerClass,
				wrapperClass: 'qs-modal',
				buttons: [],
				opened: function(){
					var $loader = $modal.find('.qs-loading-wrap');
					var $content = $modal.find('.qs-content');
					$loader.show(); $content.hide();
					var qsUrl = window.qsUrl;
					
					$.ajax({
						url:qsUrl,
						type: 'POST',
						cache:false,
						success: function(res){
							$('body').addClass('cdz-qs-view');
							$content.html(res);
							$content.trigger('contentUpdated');
							$content.show();
							$('.qs-modal .modal-content').niceScroll({cursorcolor:'#9E9E9E', cursorborder:'#747070'});
							//If product type is bundle
							if($content.find('#bundle-slide').length > 0){
								var $bundleBtn = $content.find('#bundle-slide');
								var $bundleTabLink = $('#tab-label-quickshop-product-bundle-title');
								setTimeout(function(){
									$bundleBtn.unbind('click').click(function(e){
										e.preventDefault();
										$bundleTabLink.parent().show();
										$bundleTabLink.click();
										return false;
									});
								},500);
							}
						}
					}).always(function(){$loader.hide();});
				},
				closed: function(){
					$modal.find('.qs-content').html('');
					$('body').removeClass('cdz-qs-view');
				}
			});
			
			self.element.find('.'+config.handlerClass).each(function(){
				var $handler = $(this);
				if(!$handler.data('quickshop')){
					$handler.data('quickshop',true);
					$handler.click(function(){
						window.qsUrl = $(this).data('href');
						qsModal.modal('openModal');
					});
				}
			});
			window.qsModal = qsModal;
		}
	});
	return $.custom.CodazonQuickShop;
}));