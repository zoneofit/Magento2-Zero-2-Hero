define([
    "jquery","jquery/ui", "cdz_slider",'domReady!',"toggleAdvanced","cdz_menu", "matchMedia",'mage/tabs'
],function ($) {
	//"use strict";
		
	
	
	
		$.fn._buildToggle = function(){
			$("[data-cdz-toggle]").each(function(){						
				$(this).toggleAdvanced({
					selectorsToggleClass: "active", 
					baseToggleClass: "expanded",
					toggleContainers: $(this).data('cdz-toggle'),
				});
			});

		};
		$.fn._buildTabs = function(){
			if($('.cdz-tabs').length > 0){
				$('.cdz-tabs').each(function(){
					var $tab = $(this);
					mediaCheck({
						  media: '(min-width: 768px)',
						  // Switch to Desktop Version
						  entry: function () {								
							//setTimeout(function () {							
							    $tab.tabs({
							    	openedState: "active",
							    	openOnFocus: true,
							    	collapsible: false,
							    });
							//}, 1000);
						  },
						  // Switch to Mobile Version
						  exit: function () {						
							//setTimeout(function () {
							    $tab.tabs({
							    	openedState: "active",
							    	openOnFocus: false,
							    	collapsible: true
							    });
							//}, 1000);
						  }
					    });
				});
			}
		};
	
		$.fn._buildSlider = function(){			
			if($('.cdz-slider').length > 0){				
				$('.cdz-slider').each(function(){
					var $owl = $(this);				
					$owl.addClass('owl-carousel');
					var sliderItem = typeof($owl.data('items')) !== 'undefined' ? $owl.data('items') : 5;									
					$owl.owlCarousel({
						loop: typeof($owl.data('loop')) !== 'undefined' ? $owl.data('loop') : true,
						margin: typeof($owl.data('margin')) !== 'undefined' ? $owl.data('margin') : 0,
						responsiveClass: true,
						nav: typeof($owl.data('nav')) !== 'undefined' ? $owl.data('nav') : true,
						dots: typeof($owl.data('dots')) !== 'undefined' ? $owl.data('dots') : false,
						items: sliderItem,
						autoWidth: typeof($owl.data('autoWidth')) !== 'undefined' ? $owl.data('autoWidth') : false,
						rtl: ThemeOptions.rtl_layout == 1 ? true : false, 
						responsive:{
							0:{items: typeof($owl.data('items-0')) !== 'undefined' ? $owl.data('items-0'):sliderItem},
							480:{items: typeof($owl.data('items-480')) !== 'undefined' ? $owl.data('items-480'):sliderItem},
							768:{items: typeof($owl.data('items-768')) !== 'undefined' ? $owl.data('items-768'):sliderItem},
							1024:{items: typeof($owl.data('items-1024')) !== 'undefined' ? $owl.data('items-1024'):sliderItem},
							1280:{items: typeof($owl.data('items-1280')) !== 'undefined' ? $owl.data('items-1280'):sliderItem},
							1440:{items: typeof($owl.data('items-1440')) !== 'undefined' ? $owl.data('items-1440'):sliderItem}
						}	
					});									
				});				
			}
		};	
		
		$.fn._tooltip = function(){
		$( '.show-tooltip' ).each(function(){
			$(this).tooltip({
			position: {
			  my: "center top-80%",
			  at: "center top",
			  using: function( position, feedback ) {
			    $( this ).css( position );
			    $(this).addClass("cdz-tooltip");
			  }
			}
    			});
		})
	};	
		
	$.fn._buildSlider();
	$.fn._buildTabs();	
	$.fn._tooltip();
	$.fn._buildToggle();
});
