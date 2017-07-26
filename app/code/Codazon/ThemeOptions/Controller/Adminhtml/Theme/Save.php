<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Controller\Adminhtml\Theme;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
    	$params = array();
    	$themeConfigOb = $this->_objectManager->create('\Codazon\ThemeOptions\Framework\App\Config');
        $path = \Magento\Framework\View\DesignInterface::XML_PATH_THEME_ID;
        $config = $this->_objectManager->create('Magento\Config\Model\Config');
        $this->theme_id = $this->getRequest()->getParam('theme_id');
        $this->section = 'general_section';
        $this->website = $this->getRequest()->getParam('website');
        $this->store = $this->getRequest()->getParam('store');
        $this->code = $this->getRequest()->getParam('code');
        
        $params['code'] = $this->code;
        
        if($this->website)
        {
        	$params['website'] = $this->website;
        	$config->setData($params);
        	$config->setDataByPath($path,$this->theme_id);
        	$config->save();
        }
        else if($this->store)
        {
        	$params['store'] = $this->store;
        	$config->setData($params);
        	$collection = $this->_objectManager->create('\Magento\Theme\Model\ResourceModel\Design\Collection');
        	$collection->addFieldToFilter('store_id',$this->store);
        	$design = $collection->getFirstItem();
        	
        	if(!$design->getDesign())
        	{
        		$design = $this->_objectManager->create('\Magento\Framework\App\DesignInterface');
        	}
        	$design->setStoreId($this->store);
        	$design->setDesign($this->theme_id);
        	$design->save();
        }
        else
        {
        	$config->setDataByPath($path,$this->theme_id);
        	$config->save();
        }
        
        $cmsHomePage = $themeConfigOb->getValue('cms_home_page', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config->setDataByPath('web/default/cms_home_page',$cmsHomePage);
    	$config->save();
        
    	$resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath(
            'themeoptions/config/edit',
            [
            	'theme_id' => $this->theme_id,
            	'code'	   => $this->code,
            	'section'  => $this->section,
                'website'  => $this->website,
                'store'    => $this->store
            ]
        );
    }
}
