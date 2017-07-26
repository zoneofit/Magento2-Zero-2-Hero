<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
use \Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Controller\ResultFactory;


class MassDelete extends \Magento\Backend\App\Action
{
	const ID_FIELD = 'menu_id';
	const REDIRECT_URL = '*/*/';
	protected $collection = 'Codazon\MegaMenu\Model\ResourceModel\Megamenu\Collection';
	protected $model = 'Codazon\MegaMenu\Model\Megamenu';
	public function execute(){
		$selected = $this->getRequest()->getParam('selected');
        $excluded = $this->getRequest()->getParam('excluded');
		try {
            if (isset($excluded)) {
                if (!empty($excluded)) {
                    $this->excludedDelete($excluded);
                } else {
                    $this->deleteAll();
                }
            } elseif (!empty($selected)) {
                $this->selectedDelete($selected);
            } else {
                $this->messageManager->addError(__('Please select item(s).'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath(static::REDIRECT_URL);	
	}
	protected function deleteAll()
	{
		/** @var AbstractCollection $collection */
		$collection = $this->_objectManager->get($this->collection);
		$this->setSuccessMessage($this->delete($collection));
	}
	protected function excludedDelete(array $excluded)
    {
        /** @var AbstractCollection $collection */
		$collection = $this->_objectManager->get($this->collection);
		$collection->addFieldToFilter(static::ID_FIELD, ['nin' => $excluded]);
		$this->setSuccessMessage($this->delete($collection));
    }
	protected function selectedDelete(array $selected)
    {
        /** @var AbstractCollection $collection */
		$collection = $this->_objectManager->get($this->collection);
		$collection->addFieldToFilter(static::ID_FIELD, ['in' => $selected]);
		$this->setSuccessMessage($this->delete($collection));
    }
	protected function delete($collection)
    {
        $count = 0;
        foreach ($collection->getAllIds() as $id) {
			/** @var \Magento\Framework\Model\AbstractModel $model */
			$model = $this->_objectManager->get($this->model);
			$model->load($id);
			$model->delete();
			++$count;
        }
		return $count;
	}
	protected function setSuccessMessage($count)
    {
		$this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $count));
	}
}
?>