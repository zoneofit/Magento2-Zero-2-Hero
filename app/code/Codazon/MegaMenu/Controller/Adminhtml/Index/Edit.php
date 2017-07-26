<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
class Edit extends \Magento\Backend\App\Action
{
	/**
	* Core registry
	*
	* @var \Magento\Framework\Registry
	*/
	protected $_coreRegistry = null;
	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;
	
	public function __construct(
		Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Framework\Registry $registry
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->_coreRegistry = $registry;
		parent::__construct($context);
	}
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_MegaMenu::save');
    }
	protected function _initAction()
	{
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Codazon_MegaMenu::megamenu')
			->addBreadcrumb(__('Megamenu'), __('Megamenu'))
			->addBreadcrumb(__('Manage Menus'), __('Manage Menus'));
		return $resultPage;
	}
	public function execute()
	{
		$id = $this->getRequest()->getParam('menu_id');
		$model = $this->_objectManager->create('Codazon\MegaMenu\Model\Megamenu');
		$model->setMenuId($id);
		if ($id) {
			$model->load($id);
			if (!$model->getId()) {
				$this->messageManager->addError(__('This menu no longer exists.'));
				/** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
				$resultRedirect = $this->resultRedirectFactory->create();
				return $resultRedirect->setPath('*/*/');
			}
		}
	
		$data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}
	
		$this->_coreRegistry->register('megamenu', $model);
	
		/** @var \Magento\Backend\Model\View\Result\Page $resultPage */
		$resultPage = $this->_initAction();
		$resultPage->addBreadcrumb(
			$id ? __('Edit Menu') : __('New Menu'),
			$id ? __('Edit Menu') : __('New Menu')
		);
		$resultPage->getConfig()->getTitle()->prepend(__('Megamenu'));
		$resultPage->getConfig()->getTitle()
			->prepend($model->getId() ? $model->getTitle() : __('New Menu'));
	
		return $resultPage;
	}
}