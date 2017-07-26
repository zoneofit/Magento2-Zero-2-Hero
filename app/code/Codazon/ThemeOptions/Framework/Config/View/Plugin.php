<?php
namespace Codazon\ThemeOptions\Framework\Config\View;
use Magento\Framework\App\Config\ScopeConfigInterface;
class Plugin
{
	public function __construct(
        \Codazon\ThemeOptions\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }
    
    public function aroundGetVarValue($subject, $procede, $module, $var)
    {
		if($module == 'Magento_Catalog'){
			if($var == 'gallery/navdir'){
				return $this->_helper->getConfig('general_section/product_view/moreview_thumb_style');
			}
			elseif($var == 'gallery/allowfullscreen'){
				if($this->_helper->getConfig('general_section/product_view/disable_product_zoom')){
					return 'false';
				}else{
					return 'true';
				}
			}
		}
    	$result = $procede($module, $var);
    	return $result;
    }
}
