(function (factory) {
    if (typeof define === "function" && define.amd) {
        define([
            "jquery",
            'jquery/ui'
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($) {
	"use strict";
	$.widget('codazon.AjaxLoad', {
		options: {
			trigger: '.cdz-ajax-trigger',
			itemsWrap: '.product-items',
			ajaxLoader: '.ajax-loader',
			ajaxUrl: null,
			jsonData: null,
			currentUrl: '' 
		},
		_currentPage: 1,
		_create: function(){
			var self = this;
			self.element.find(self.options.trigger).click(function(){
				self._ajaxLoadProducts();
			});
		},
		_ajaxLoadProducts: function(){
			var self = this;
			var config = this.options;
			var $trigger = self.element.find(config.trigger);
			var $ajaxLoader = self.element.find(config.ajaxLoader);
			var hasLastPage = false;
			$trigger.hide();
			$ajaxLoader.show();
			self._currentPage++;
			config.jsonData.cur_page = self._currentPage;
			config.jsonData.current_url = config.currentUrl;
			
			jQuery.ajax({
				url: config.ajaxUrl,
				type: "POST",
				//dataType:"json",
				data: config.jsonData,
				cache: false,
				success: function(res){
					if(res.html)
						$(config.itemsWrap,self.element).append(res.html);
					if(res.last_page == self._currentPage){
						hasLastPage = true;
					}
					self.element.trigger('contentUpdated');
					if(typeof qsModal !== 'undefined'){
						$('.qs-button',self.element).each(function(){
							var $handler = $(this);
							if(!$handler.data('quickshop')){
								$handler.data('quickshop',true);
								$handler.click(function(){
									window.qsUrl = $(this).data('href');
									qsModal.modal('openModal');
								});
							}						
						});
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					console.error(textStatus);
				}
			}).always(function(){
				$ajaxLoader.hide();
				if(!hasLastPage){
					$trigger.show();
				}else{
					$trigger.hide();
				}
			});
		}
	});
	return $.codazon.AjaxLoad;
}));
