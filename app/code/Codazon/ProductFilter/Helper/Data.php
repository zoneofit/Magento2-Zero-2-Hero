<?php
/**
 * Copyright © 2015 Codazon . All rights reserved.
 */
namespace Codazon\ProductFilter\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	 /**
     * @var \Magento\Catalog\Model\ProductTypes\ConfigInterface
     */
    protected $productTypeConfig;
    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;
	/**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     */
     
	public function __construct(
		\Magento\Framework\App\Helper\Context $context,   
		\Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
		\Magento\Catalog\Block\Product\Context $productContext     
	) {
		$this->productTypeConfig = $productTypeConfig;
		$this->stockRegistry = $productContext->getStockRegistry();
		parent::__construct($context);
		
	
	}
	
	/**
     * Gets minimal sales quantity
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return int|null
     */
    public function getMinimalQty($product)
    {
        $stockItem = $this->stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
        $minSaleQty = $stockItem->getMinSaleQty();
        return $minSaleQty > 0 ? $minSaleQty : null;
    }
    
    /**
     * Get default qty - either as preconfigured, or as 1.
     * Also restricts it by minimal qty.
     *
     * @param null|\Magento\Catalog\Model\Product $product
     * @return int|float
     */
    public function getProductDefaultQty($product = null)
    {
        $qty = $this->getMinimalQty($product);
        $config = $product->getPreconfiguredValues();
        $configQty = $config->getQty();
        if ($configQty > $qty) {
            $qty = $configQty;
        }

        return $qty;
    }
	
	/**
     * Check whether quantity field should be rendered
     *
     * @return bool
     */
    public function shouldRenderQuantity($product)
    {
        return !$this->productTypeConfig->isProductSet($product->getTypeId());
    }
    
    /**
     * Get Validation Rules for Quantity field
     *
     * @return array
     */
    public function getQuantityValidators()
    {
        $validators = [];
        $validators['required-number'] = true;
        return $validators;
    }
	
}
