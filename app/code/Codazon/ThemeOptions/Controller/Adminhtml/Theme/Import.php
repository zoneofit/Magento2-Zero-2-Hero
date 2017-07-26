<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Controller\Adminhtml\Theme;

class Import extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Codazon\ThemeOptions\Model\Config\Structure $configStructure,
        //\Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker,
        \Codazon\ThemeOptions\Model\Config $backendConfig,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        //\Magento\Framework\ObjectManagerInterface $objectManager,
        \Codazon\ThemeOptions\Setup\Model\Page $pageSetup,
		\Codazon\ThemeOptions\Setup\Model\Block $blockSetup,
		\Codazon\ThemeOptions\Setup\Model\Widget $widgetSetup,
		\Codazon\ThemeOptions\Setup\Model\Slideshow $slideshowSetup,
		\Codazon\ThemeOptions\Setup\Model\Blog\Category $blogCategorySetup,
		\Codazon\ThemeOptions\Setup\Model\Blog\Post $blogPostSetup,
		\Codazon\ThemeOptions\Setup\Model\MegaMenu $megaMenuSetup
    ) {
        parent::__construct($context);
        $this->_configStructure = $configStructure;
        $this->resultPageFactory = $resultPageFactory;
        $this->_objectManager = $context->getObjectManager();
        $this->registry = $registry;
        $this->pageSetup = $pageSetup;
		$this->blockSetup = $blockSetup;
		$this->widgetSetup = $widgetSetup;
		$this->slideshowSetup = $slideshowSetup;
		$this->blogCategorySetup = $blogCategorySetup;
		$this->blogPostSetup = $blogPostSetup;
		$this->megaMenuSetup = $megaMenuSetup;
    }
    
    public function execute()
    {
        $code = $this->getRequest()->getParam('code');
    	$this->pageSetup->install($code);
    	$this->blockSetup->install($code);
    	$this->slideshowSetup->install($code);
    	$this->widgetSetup->install($code);
    	$this->blogCategorySetup->install($code);
    	$this->blogPostSetup->install($code);
    	$this->megaMenuSetup->install($code);
    	
    	$resultRedirect = $this->resultRedirectFactory->create();
		    return $resultRedirect->setPath(
		        'themeoptions/theme/install'
		    );
    }
}
