<?php
namespace Codazon\ProductLabel\Controller\Adminhtml\ProductLabel;
class Index extends \Codazon\ProductLabel\Controller\Adminhtml\Label
{
	protected $resultPageFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
		\Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
		$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context,$coreRegistry,$fileFactory,$dateFilter);
    }
    public function execute(){
		$resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->getConfig()->getTitle()->prepend(__('Product Labels'));
        return $resultPage;
    }    
}
?>
