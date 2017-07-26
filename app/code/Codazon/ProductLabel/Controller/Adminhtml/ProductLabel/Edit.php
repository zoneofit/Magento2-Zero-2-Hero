<?php
namespace Codazon\ProductLabel\Controller\Adminhtml\ProductLabel;
use Magento\Backend\App\Action;
class Edit extends \Magento\Backend\App\Action
{
	protected $_coreRegistry = null;
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
        return $this->_authorization->isAllowed('Codazon_ProductLabel::save');
    }
	protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codazon_ProductLabel::productlabel')
            ->addBreadcrumb(__('Product Label'), __('Product Label'))
            ->addBreadcrumb(__('Manage Product Labels'), __('Manage Product Labels'));
        return $resultPage;
    }
	public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->_objectManager->create('Codazon\ProductLabel\Model\ProductLabel');
		$storeId = (int)$this->getRequest()->getParam('store');
        if ($id) {
            $model->setStoreId($storeId);
			
			$model->load($id);
			$model->setStore($storeId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This label no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('productlabel', $model);
		$model->getConditions()->setJsFormObject('label_conditions_fieldset');
		
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Product Label') : __('New Product Label'),
            $id ? __('Edit Product Label') : __('New Product Label')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Label'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Product Label'));

        return $resultPage;
    }
}