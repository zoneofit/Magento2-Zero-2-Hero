<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
class Wysiwyg extends \Magento\Backend\App\Action
{
	
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;
	public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
		array $data = []
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
    }
	public function execute()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore($storeId)
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $content = $this->layoutFactory->create()->createBlock(
            'Codazon\MegaMenu\Block\Adminhtml\Index\Edit\Fields\MenuItems\Wysiwyg',
            '',
            [
                'data' => [
                    'editor_element_id' => $elementId,
                    'store_id' => $storeId,
                    'store_media_url' => $storeMediaUrl,
                ]
            ]
        );

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content->toHtml());
    }
	
}