<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
class NewAction extends \Magento\Backend\App\Action
{
	protected $resultForwardFactory;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory)
	{
		$this->resultForwardFactory = $resultForwardFactory;
		parent::__construct($context);
	}
	/**
     * Is the user allowed to view the menu grid.
     *
     * @return bool
     */
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_MegaMenu::save');
    }
	public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}