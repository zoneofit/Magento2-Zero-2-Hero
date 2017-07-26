<?php
/**
 * Copyright © 2015 Codazon . All rights reserved.
 */
namespace Codazon\ThemeOptions\Helper;
use Magento\Framework\App\Filesystem\DirectoryList;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_storeManager;
	protected $_scopeConfig;
	protected $_pageConfig;
	/**
     * @param \Magento\Framework\App\Helper\Context $context
     */
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Codazon\ThemeOptions\Framework\App\Config $themeConfig,
		\Magento\Framework\App\Config $scopeConfig,
		\Codazon\ThemeOptions\Model\ConfigFactory $configFactory,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\View\Page\Config $pageConfig
	) {
		parent::__construct($context);
		$this->_storeManager = $storeManager;
		$this->_scopeConfig = $scopeConfig;
		$this->_themeConfig = $themeConfig;		
		$this->_pageConfig = $pageConfig;
		$this->_configFactory = $configFactory;
		
	}
	
	public function getConfig($fullPath){		
		return $this->_themeConfig->getValue($fullPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
	}
	public function getThemeOptionsLabel(){
		return $this->_scopeConfig->getValue('themeoptions/general/label', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
	}
	public function getBaseUrl(){		
		return $this->_storeManager->getStore()->getBaseUrl();
	}
		
	public function getPageColumns(){
	      return $this->_pageConfig->getPageLayout();
	}
	
	public function setBodyClass($class){			
		$this->_pageConfig->addBodyClass($class);		
	}

    public function isHomePage()
    {
        $currentUrl = $this->_urlBuilder->getUrl('', ['_current' => true]);
        $urlRewrite = $this->_urlBuilder->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);        
        return $currentUrl == $urlRewrite;
    }
}
