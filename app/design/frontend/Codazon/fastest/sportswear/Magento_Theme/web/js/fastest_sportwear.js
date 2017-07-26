(function (factory) {
    if (typeof define === "function" && define.amd) {
        define([
            "jquery","jquery/ui", "cdz_slider",'domReady!','cdz_menu'
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($) {
	"use strict";
	$.widget('custom.FastedSportswear', {
		options: {
		},
		_create: function(){			
			this._backTopButton();
			if(ThemeOptions.sticky_header){
				this._stickyMenu();
			}
			this._alignMenu();
			this._buildMenu();
			this._sameHeightItems();
			this._resize();
		},		
		
		
		_stickyMenu: function(){
			var $stickyMenu = $('.sticky-menu').first();
			if( $stickyMenu.length > 0 ){
				var threshold = $stickyMenu.height() + $stickyMenu.offset().top;
				
				$(window).scroll(function(){
					var $win = $(this);
					var newHeight = 0;
					if($('.sticky-menu.active').length > 0)
						newHeight = $('.sticky-menu.active').height();																			
					var curWinTop = $win.scrollTop() + newHeight;
					if(curWinTop > threshold){
						$stickyMenu.addClass('active');
					}else{
						$stickyMenu.removeClass('active');
					}
				});
			}
		},
		
		_backTopButton: function(){
			var $backTop = $('#back-top');
			$('a', $backTop).unbind('click');
			if($backTop.length){
				$backTop.hide();
				$(window).scroll(function() {
					if ($(this).scrollTop() > 100) {
						$backTop.fadeIn();
					} else {
						$backTop.fadeOut();
					}
				});
				$('a', $backTop).click(function() {
					$('body,html').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
			}
		},
		_alignMenu: function(){                            
			$('.cdz-main-menu > .groupmenu > .level-top > .groupmenu-drop').parent().hover(function() {
				var dropdownMenu = $(this).children('.groupmenu-drop');
				if ($(this).hasClass('parent')) 
					dropdownMenu.css('left', 0);
				var menuContainer = $(this).parents('.header.content').first();
				if(menuContainer.length){
					var left = menuContainer.offset().left + menuContainer.outerWidth() - (dropdownMenu.offset().left + dropdownMenu.outerWidth());
					var leftPos = dropdownMenu.offset().left + left - menuContainer.offset().left;
					if (leftPos < 0) left = left - leftPos;
					if (left < 0) {
						dropdownMenu.css('left', left  + 'px');
					}
				}
			}, function() {
				$(this).children('.groupmenu-drop').css('left', '0px');
			});
		},
		_buildMenu: function(){
			$('.cdz-main-menu > .groupmenu').cdzmenu({
				responsive: true,
				expanded: true,
				delay: 300
			});
		},
		_sameHeightItems: function(){	
			var maxHeight = 0;			
			if($('.same-height').length > 0){
				$('.same-height').each(function(){
					var $ul = $(this);			
					//setTimeout(function () {
						$ul.find('.product-item-details').removeAttr('style');
						$ul.find('.product-item-details').each(function()				
						{																				
							if($(this).height() > maxHeight){
								maxHeight = $(this).height();	
							}							
						});											
						$ul.find('.product-item-details').height(maxHeight);
					//},100);							
				});
			}
		}, 
		_resize: function () {
			var self = this;					
			$(window).resize(function () {
				if(typeof timeResize != 'undefined'){
			        clearTimeout(timeResize);
			    }
			    var timeResize = setTimeout(function(){			        
					self._sameHeightItems();
			    },250);	
			});					
		}
		
		
	});
	return $.custom.FastedSportswear;
}));

