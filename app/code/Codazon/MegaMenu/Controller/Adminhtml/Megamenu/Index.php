<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Megamenu;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;
	public function __construct(Context $context, PageFactory $resultPageFactory)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}
	public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codazon_MegaMenu::megamenu');
        $resultPage->addBreadcrumb(__('Megamenu'), __('Megamenu'));
        $resultPage->addBreadcrumb(__('Manage Menus'), __('Manage Menus'));
        $resultPage->getConfig()->getTitle()->prepend(__('Megamenu'));

        return $resultPage;
    }
	/**
     * Is the user allowed to view the menu grid.
     *
     * @return bool
     */
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_MegaMenu::manage_menus');
    }	
}