var config = {
    map: {
        '*': {
			codazonSidebar: 'Codazon_AjaxCartPro/js/sidebar',
            catalogAddToCart: 'Codazon_AjaxCartPro/js/catalog-add-to-cart',
			catalogAddToCompare: 'Codazon_AjaxCartPro/js/catalog-add-to-compare'
        },
		'shim': {
    		'Codazon_AjaxCartPro/js/catalog-add-to-cart': ['catalogAddToCart'],
			'Codazon_AjaxCartPro/js/catalog-add-to-compare': ['mage/dataPost']
    	}
    }
};
