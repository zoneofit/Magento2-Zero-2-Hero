<?php
namespace Codazon\MegaMenu\Block\Adminhtml\Index\Edit;
class Js extends \Magento\Backend\Block\Template
{
	protected $_assetRepo;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = []
	)
    {
		$this->_assetRepo = $context->getAssetRepository();
        parent::__construct($context, $data);
    }
	public function getAssetRepo(){
		return $this->_assetRepo;
	}
	public function getMediaUrl()
    {		
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
