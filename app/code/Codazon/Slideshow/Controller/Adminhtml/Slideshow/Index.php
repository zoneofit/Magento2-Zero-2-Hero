<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Controller\Adminhtml\Slideshow;

class Index extends \Magento\Backend\App\Action
{
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
    	$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        
    }
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_Slideshow::slideshow');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codazon_Slideshow::slideshow');
        $resultPage->addBreadcrumb(__('Codazon'), __('Codazon'));
        $resultPage->addBreadcrumb(__('Manage Slideshows'), __('Manage Slideshows'));
        $resultPage->getConfig()->getTitle()->prepend(__('Slideshows'));

        return $resultPage;
    }
}
