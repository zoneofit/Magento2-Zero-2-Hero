var config = {
  map: {
        "*": {
            "cdz_slider": "js/owlcarousel/owlslider",
            "modal" : "Magento_Ui/js/modal/modal",
			"cdz_menu": "js/menu/cdz_menu"
        }
    },
    paths:  {
        "owlslider" : "js/owlcarousel/owl.carousel.min",

    },
    "shim": {
		"js/owlcarousel/owl.carousel.min": ["jquery"]
	},
	deps: [
        "Magento_Theme/js/fastest"        
    ]
  
};
 
