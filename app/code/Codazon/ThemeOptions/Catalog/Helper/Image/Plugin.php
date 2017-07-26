<?php
namespace Codazon\ThemeOptions\Catalog\Helper\Image;
class Plugin
{
	public function __construct(
        \Codazon\ThemeOptions\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }
    public function beforeInit($subject, $product, $imageId, $attributes = [])
    {
    	if($imageId == 'category_page_grid'){
    		$attributes['aspect_ratio'] = (bool)$this->_helper->getConfig('general_section/category_view/keep_image_ratio');
    		$attributes['width'] = $this->_helper->getConfig('general_section/category_view/image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/category_view/image_height');
    	}
    	if($imageId == 'category_page_grid_hover'){
    		$attributes['aspect_ratio'] = (bool)$this->_helper->getConfig('general_section/category_view/keep_image_ratio');
    		$attributes['width'] = $this->_helper->getConfig('general_section/category_view/image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/category_view/image_height');
    	}
    	if($imageId == 'category_page_list'){
    		$attributes['aspect_ratio'] = (bool)$this->_helper->getConfig('general_section/category_view/keep_image_ratio');
    		$attributes['width'] = $this->_helper->getConfig('general_section/category_view/image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/category_view/image_height');
    	}
    	if($imageId == 'category_page_list_hover'){
    		$attributes['aspect_ratio'] = (bool)$this->_helper->getConfig('general_section/category_view/keep_image_ratio');
    		$attributes['width'] = $this->_helper->getConfig('general_section/category_view/image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/category_view/image_height');
    	}
    	if($imageId == 'product_page_image_medium'){
    		$attributes['aspect_ratio'] = (bool)$this->_helper->getConfig('general_section/product_view/keep_image_ratio');
    		$attributes['width'] = $this->_helper->getConfig('general_section/product_view/base_image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/product_view/base_image_height');
    	}
    	if($imageId == 'product_page_image_small'){
    		$attributes['width'] = $this->_helper->getConfig('general_section/product_view/moreview_image_width');
    		$attributes['height'] = $this->_helper->getConfig('general_section/product_view/moreview_image_height');
    	}
    	return [$product, $imageId, $attributes];
    }
}
