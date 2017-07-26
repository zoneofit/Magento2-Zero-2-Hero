define([
    "jquery","jquery/ui","mage/dataPost", "cdz_slider",'domReady!',"toggleAdvanced","cdz_menu", "matchMedia",'mage/tabs','catalogAddToCart'
],function ($) {
	"use strict";
	$.widget('codazon.FirstLoad', {
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
				self._ajaxFirstLoad();
		},
		_ajaxFirstLoad: function(){
			var self = this;
			var config = this.options;
			var $trigger = self.element.find(config.trigger);
			var $ajaxLoader = self.element.find(config.ajaxLoader);
			var hasLastPage = false;
			//$trigger.hide();
			//$ajaxLoader.show();
			self._currentPage++;
			config.jsonData.cur_page = self._currentPage;
			config.jsonData.current_url = config.currentUrl;
			
			jQuery.ajax({
				url: config.ajaxUrl,
				type: "GET",
				//dataType:"json",
				data: config.jsonData,
				cache: false,
				success: function(res){
					if(res.html)
						self.element.html(res.html);
					self.element.trigger('contentUpdated');
					$(".tocompare").dataPost();
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
					$.fn._buildSlider();
	                $.fn._buildTabs();	
	                $.fn._tooltip();
	                $.fn._buildToggle();
	                $("[data-role=tocart-form], .form.map.checkout").catalogAddToCart({});
	                
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					console.error(textStatus);
				}
			}).always(function(){
				//$ajaxLoader.hide();
				//if(!hasLastPage){
				//	$trigger.show();
				//}else{
				//	$trigger.hide();
				//}
			});
		}
	});
	return $.codazon.FirstLoad;
});
