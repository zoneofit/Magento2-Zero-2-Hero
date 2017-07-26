(function (factory) {
    if (typeof define === "function" && define.amd) {
        define([
            "jquery",""
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($) {
	"use strict";
	$.widget('custom.CodazonIsotope', {
		options: {
			isoWrap: '.isotope',
			isoItem: '.iso-item',
			layoutMode: 'masonry',
			colNum: 5	
		},
		_create: function(){
			if(typeof window !== 'undefined'){
				this._buildIsoGrid(this.options);
			}else{
				$(window).bind('load', function () {
					this._buildIsoGrid(this.options);
				});
			}
		},
		_buildIsoGrid: function(config){
			var $this = this;
			var $isoWrap = $(config.isoWrap);
			$isoWrap.find(config.isoItem).each(function(){
				var $isoItem = $(this);
				var itemWidth = $isoItem.width();
				$isoItem.height(itemWidth);
			});
			require(['Codazon_ProductFilter/js/isotope/isotope.pkgd.min'],function(Isotope){
				require(['Codazon_ProductFilter/js/isotope/jquery-bridget'],function(){
					$.bridget( 'isotope', Isotope );
					$isoWrap.isotope({
						itemSelector: config.isoItem,
						isResizeBound: false,
						layoutMode: config.layoutMode,
						masonry: {
							columnWidth: $(config.isoWrap).width()/config.colNum
						}
					});
					$this._fixHiddenTab(config,$isoWrap);
					$this._fixWindowResize(config,$isoWrap);
				});
		    });
		},
		_fixWindowResize: function(config,$obj){
			var $this = this;
			$(window).resize(function(){
				setTimeout(function(){
					$this._fixIsotop(config,$obj);
				},300);
			});				
		},
		_fixHiddenTab: function(config,$obj){
			var $this = this;
			$obj.parents('.isotope-wrap').first().parent().prev().find('a').click(function(){
				setTimeout(function(){
					$this._fixIsotop(config,$obj);
				},300);
			});
		},
		_fixIsotop: function(config,$obj){
			var $isoWrap = $(config.isoWrap);
			$isoWrap.find(config.isoItem).each(function(){
				var $isoItem = $(this);
				var itemWidth = $isoItem.width();
				$isoItem.height(itemWidth);
				$isoWrap.isotope({
					masonry: {
						columnWidth: $(config.isoWrap).width()/config.colNum
					}
				});
			});
		}
	});
	return $.custom.CodazonIsotope;
}));