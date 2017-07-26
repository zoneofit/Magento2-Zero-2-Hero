<?php
namespace Codazon\ThemeOptions\Framework\App\Action\Action;
class Plugin
{
	public function __construct(
		\Codazon\ThemeOptions\Helper\Data $optionHelper
	)
	{
		$this->_optionHelper = $optionHelper;
	}
	
	public function isMobile()  
	{  
		$regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|"  
		             . "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|"  
		             . "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|"  
		             . "symbian|smartphone|mmp|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|"  
		             . "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220"  
		             . ")/i";  

		if (preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']))) {  
		    return TRUE;  
		}  

		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {  
		    return TRUE;  
		}      

		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));  
		$mobile_agents = array(  
		    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
		    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
		    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
		    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
		    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
		    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
		    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
		    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
		    'wapr','webc','winw','winw','xda ','xda-');  

		if (in_array($mobile_ua,$mobile_agents)) {  
		    return TRUE;  
		}  

		if (isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {  
		    return TRUE;  
		}  

		return FALSE;  
	}  
	
	public function is_mobile() {
		$user_agent=strtolower(getenv('HTTP_USER_AGENT'));
		$accept=strtolower(getenv('HTTP_ACCEPT'));

		if ((strpos($accept,'text/vnd.wap.wml')!==false) ||
		  (strpos($accept,'application/vnd.wap.xhtml+xml')!==false)) {
			return 1; 
		}

		if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
			return 2;
		} 
		return 0;
	}
	
	public function progress($layout)
	{
		if($this->_optionHelper->getConfig('general_section/header/default_menu')){//use default menu
			$layout->unsetElement('menu.container');
		}else{//use mega menu
			$layout->unsetElement('catalog.topnav');
		}
		if($this->is_mobile()){
			if(!$this->_optionHelper->getConfig('general_section/header/ajaxcart_pro_mobile')){
				$layout->unsetElement('ajaxcart_head_components');
				$layout->unsetElement('ajax_cart_form');
				$layout->unsetElement('ajax_cart_sidebar');
			}
		}else{
			if(!$this->_optionHelper->getConfig('general_section/header/ajaxcart_pro_desk')){
				$layout->unsetElement('ajaxcart_head_components');
				$layout->unsetElement('ajax_cart_form');
				$layout->unsetElement('ajax_cart_sidebar');
			}
		}
		//product detail show hide tabs
		if(!$this->_optionHelper->getConfig('general_section/product_view/product_tabs')){
			$block = $layout->getBlock('product.info.details');
			if($block){
				$block->setTemplate('Codazon_ThemeOptions::catalog/product/view/details.phtml');
			}
		}
		//show hide compare top link
		if(!$this->_optionHelper->getConfig('general_section/product_compare/show_compare_link_top')){
			$layout->unsetElement('catalog.compare.link');
		}
		//show hide newsletter popup
		if($this->_optionHelper->getConfig('general_section/newsletter_popup/enable')){
			if($layout->getParentName('block.popup.container')){
				$layout->addBlock('Magento\Cms\Block\Block','newsletter.popup','block.popup.container');
				$block = $layout->getBlock('newsletter.popup');
				$identify = $this->_optionHelper->getConfig('general_section/newsletter_popup/static_block_id');
				$block->setBlockId($identify);
			}
		}else{
			$layout->unsetElement('popup_block');
		}
		if($this->_optionHelper->getConfig('general_section/layout/box_wide'))
			$this->_optionHelper->setBodyClass('box-layout');
		if($this->_optionHelper->getConfig('general_section/layout/enable_rtl'))
			$this->_optionHelper->setBodyClass('rtl-layout');
	}
	
    public function afterDispatch($subject, $result)
    {
    	if(get_class($result) == 'Magento\Framework\View\Result\Page\Interceptor'){
    		$layout = $result->getLayout();
    		$this->progress($layout);
		}
    	return $result;
    }
}
