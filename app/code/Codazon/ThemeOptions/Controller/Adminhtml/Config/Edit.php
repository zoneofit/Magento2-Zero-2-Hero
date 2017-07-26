<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Controller\Adminhtml\Config;
class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker
     * @param \Magento\Config\Model\Config $backendConfig
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Codazon\ThemeOptions\Model\Config\Structure $configStructure,
        \Codazon\ThemeOptions\Model\Config $backendConfig,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->_configStructure = $configStructure;
        $this->resultPageFactory = $resultPageFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->registry = $registry;
    }
    
    public function getCurrentThemeId(){
    	$path = 'design/theme/theme_id';
        $config = $this->_objectManager->create('Magento\Config\Model\Config');
        $this->session = 'design';
        $this->website = $this->getRequest()->getParam('website');
        $this->store = $this->getRequest()->getParam('store');
        $this->code = $this->getRequest()->getParam('code');
        $config->setData([
        	'session'	=> $this->session,
        	'website'	=> $this->website,
        	'store'		=> $this->store
        ]);
        
        if($this->store)
        {
        	$collection = $this->_objectManager->create('\Magento\Theme\Model\ResourceModel\Design\Collection');
        	$collection->addFieldToFilter('store_id',$this->store);
        	$design = $collection->getFirstItem();
        	$this->currentThemeId = $design->getDesign();
        	//echo $this->currentThemeId;die;
        	if(!$this->currentThemeId)
        	{
        		$this->currentThemeId = $config->getConfigDataValue($path);
        	}
        }
        else
        {
        	$this->currentThemeId = $config->getConfigDataValue($path);
        }
        return $this->currentThemeId;
    }

    /**
     * Edit configuration section
     *
     * @return \Magento\Framework\App\ResponseInterface|void
     */
    public function execute()
    {
        $current = $this->getRequest()->getParam('section');
        $website = $this->getRequest()->getParam('website');
        $store = $this->getRequest()->getParam('store');
        $code = $this->getRequest()->getParam('code');
        $theme = $this->getRequest()->getParam('theme_id');
        
		$currentThemeId = $this->getCurrentThemeId();
		if($theme != $currentThemeId){
			$resultRedirect = $this->resultRedirectFactory->create();
		    return $resultRedirect->setPath(
		        'themeoptions/config/edit',
		        [
		        	'theme_id' => $currentThemeId,
		        	'code'	   => $code,
		        	'section'  => $current,
		            'website'  => $website,
		            'store'    => $store
		        ]
		    );
		}
        $section = $this->_configStructure->getElement($current);
        if ($current && !$section->isVisible($website, $store)) {
        }
        $resultPage = $this->resultPageFactory->create();

        if($theme){
		    $themeModel = $this->_objectManager->get('\Magento\Theme\Model\Theme');
		    $themeModel->load($theme);
		    if(strpos($themeModel->getThemePath(), 'Codazon')!==false){
			    $resultPage->getConfig()->getTitle()->prepend(__($themeModel->getThemeTitle()));		    
			    $layout = $resultPage->getLayout();
			    $layout->addBlock('Codazon\ThemeOptions\Block\Adminhtml\Config\Tabs','adminhtml.system.config.tabs','left');
			    $layout->addBlock('Codazon\ThemeOptions\Block\Adminhtml\Config\Edit','adminhtml.system.config.edit','content');
			}
        }
        
        $resultPage->getLayout()->getBlock('menu')->setAdditionalCacheKeyInfo([$current]);
        $resultPage->addBreadcrumb(__('System'), __('System'), $this->getUrl('*\/system'));
        
        return $resultPage;
    }
}
