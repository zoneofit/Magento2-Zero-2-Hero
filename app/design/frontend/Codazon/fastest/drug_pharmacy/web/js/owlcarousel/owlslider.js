/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'owlslider'
], function($){
    return function (config, element) {  
        if($(element).length) {
                $(element).addClass("owl-carousel");
   	        $(element).owlCarousel(config);
        }
    }
});

