<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
class Delete extends \Magento\Backend\App\Action
{
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_MegaMenu::delete');
		
    }
	public function execute()
	{
		$id = $this->getRequest()->getParam('menu_id');
		$resultRedirect = $this->resultRedirectFactory->create();
		if($id){
			try {	
				$model = $this->_objectManager->create('Codazon\MegaMenu\Model\Megamenu');
				$model->load($id);
				$model->delete();
				$this->messageManager->addSuccess(__('The menu has been deleted.'));
				return $resultRedirect->setPath('*/*/');
			}catch(\Exception $e){
				$this->messageManager->addError($e->getMessage());
				return $resultRedirect->setPath('*/*/edit', ['menu_id' => $id]);
			}
		}
		$this->messageManager->addError(__('We can\'t find a menu to delete.'));
		return $resultRedirect->setPath('*/*/');
	}
}