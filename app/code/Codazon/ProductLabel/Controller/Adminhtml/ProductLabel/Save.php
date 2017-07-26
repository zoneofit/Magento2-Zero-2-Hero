<?php
namespace Codazon\ProductLabel\Controller\Adminhtml\ProductLabel;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Codazon\ProductLabel\Controller\Adminhtml\Label
{
	/*public function __construct(Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        parent::__construct($context,$coreRegistry);
    }*/
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_ProductLabel::save');
    }	
	public function execute()
    {
        $data = $this->getRequest()->getPostValue();
		$storeId = (int)$this->getRequest()->getParam('store');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		
		
		/*$this->_eventManager->dispatch(
			'productlabel_prepare_save',
			['productlabel' => $model, 'request' => $this->getRequest()]
		);*/
        $resultRedirect = $this->resultRedirectFactory->create();
		
        if ($data) {
		
			
			$id = $this->getRequest()->getParam('entity_id');
            $model = $this->_objectManager->create('Codazon\ProductLabel\Model\ProductLabel');
			$model->setStoreId($storeId);
			
			$this->_eventManager->dispatch(
				'adminhtml_controller_productlabel_prepare_save',
				['request' => $this->getRequest()]
			);
			
			
			
			if ($id) {
                $model->load($id);
				if ($id != $model->getId()) {
					throw new LocalizedException(__('Wrong label specified.'));
				}
            }
						
			$inputFilter = new \Zend_Filter_Input(['from_date' => $this->_dateFilter, 'to_date' => $this->_dateFilter],[],$data);
			$data = $inputFilter->getUnescaped();
			$data['conditions'] = $data['rule']['conditions'];
			unset($data['rule']);
			$model->loadPost($data);
			$useDefaults = $this->getRequest()->getPost('use_default');
            if ($useDefaults) {
                foreach ($useDefaults as $attributeCode) {
                    $model->setData($attributeCode, false);
                }
            }
            try {
                $model->save();
				
                $this->messageManager->addSuccess(__('You saved this label.'));
                //$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
				$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true, 'store'=>$storeId]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
				$this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the label.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
	
}