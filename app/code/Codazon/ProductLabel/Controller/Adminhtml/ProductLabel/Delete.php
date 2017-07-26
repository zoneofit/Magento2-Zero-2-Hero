<?php
namespace Codazon\ProductLabel\Controller\Adminhtml\ProductLabel;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Backend\App\Action
{
	const ID_FIELD = 'entity_id';	
	protected $collection = 'Codazon\ProductLabel\Model\ResourceModel\ProductLabel\Collection';
	protected $model = 'Codazon\ProductLabel\Model\ProductLabel';
	public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create($this->model);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the label.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a label to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
?>