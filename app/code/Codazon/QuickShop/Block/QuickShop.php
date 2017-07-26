<?php
namespace Codazon\QuickShop\Block;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Catalog\Model\Product;
use \Magento\Catalog\Block\Product\View;
class QuickShop extends \Magento\Framework\View\Element\Template
{
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,     
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);     
        $this->httpContext = $httpContext;
        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => ['QUICKSHOP',
        ], ]);        
    }
	public function getCacheKeyInfo()
    {
        return [
            'QUICKSHOP',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->getRequest()->getParam('id'),
			$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            $this->getMenu()
        ];
    }
	public function getIdentities()
    {
        return ['quickshop_' . $this->getMenu()];
    }
}
