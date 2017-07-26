<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Codazon\ProductFilter\Block\Product;
/**
 * Catalog Products List widget block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    protected $urlHelper;
    protected $productCollectionFactory;
    protected $bestSellerCollectionFactory;
    protected $imageHelperFactory;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Codazon\ProductFilter\Model\ResourceModel\Bestsellers\CollectionFactory $bestSellerCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Codazon\ProductFilter\Block\ImageBuilderFactory $customImageBuilderFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->bestSellerCollectionFactory = $bestSellerCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext = $httpContext;
        $this->sqlBuilder = $sqlBuilder;
        $this->rule = $rule;
        $this->urlHelper = $urlHelper;
        $this->conditionsHelper = $conditionsHelper;
        $this->imageHelperFactory = $imageHelperFactory;
        
        //$this->reviewFactory = $reviewFactory;
        $this->customImageBuilderFactory = $customImageBuilderFactory;
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $sqlBuilder,
            $rule,
            $conditionsHelper
        );
    }

    public function getCacheKeyInfo()
    {
        $conditions = $this->getData('conditions')
            ? $this->getData('conditions')
            : $this->getData('conditions_encoded');
		$conditions = json_encode($this->getData());
        return [
            'PRODUCT_FILTER_WIDGET',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            intval($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1)),
            $this->getProductsPerPage(),
            $conditions
        ];
    }
    
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product, $additional = [])
    {
        $url = $this->getAddToCartUrl($product,$additional);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
    
    protected function _getBestSellerProductCollection()
    {
        $collection = $this->bestSellerCollectionFactory->create();
        $bestSellerTable = $collection->getTable('sales_bestsellers_aggregated_daily');
        $collection->getSelect()->join(array('r' => $bestSellerTable), 'r.product_id=e.entity_id', array('*'))->group('e.entity_id');
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1));

        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        return $collection;
    }
    
    protected function _getAllProductProductCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1));

        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        return $collection;
    }

    public function createCollection()
    {
        $displayType = $this->getDisplayType();
        $collection = null;
        switch($displayType)
        {
            case 'all_products': $collection = $this->_getAllProductProductCollection();break;
            case 'bestseller_products': $collection = $this->_getBestSellerProductCollection();break;
        }
        $sort = explode(' ', $this->getData('order_by'));
        $collection->addAttributeToSort($sort[0],$sort[1]);

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );
        return $collection;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Catalog\Model\Product::CACHE_TAG];
    }

    /**
     * Get value of widgets' title parameter
     *
     * @return mixed|string
     */

    public function getTemplate()
    {
        $template = $this->getData('filter_template');
        if($template == 'custom')
        {
            return $this->getData('custom_template');
        }
        else
        {
            return $template;
        }
    }
    
    public function isShow($item)
    {
    	$show = explode(",",$this->getData('show'));    	    	
    	if (in_array($item,$show) !== false) {
			return true;
		}else{
			return false;
		}
    }
    
    public function getImage($product, $imageId, $attributes = [])
    {
        $width = $this->getData('thumb_width');
        $height = $this->getData('thumb_height');
        $attributes = array('resize_width'=>$width,'resize_height'=>$height);

        $imageBuilder = $this->customImageBuilderFactory->create();
        return $imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
        return $html;
    }
    
    public function getBlockId()
    {
    	return uniqid("cdz_block_");
    }
    
    protected function _toHtml(){
        $isAjax = $this->getData('is_ajax');
        //$isAjax = true;
        if($isAjax){
            return parent::_toHtml();
		}else{
		    $data = [
                'is_ajax'           =>  1,
                'title'             =>  $this->getData('title'),
                'display_type'      =>  $this->getData('display_type'),
                'products_count'    =>  $this->getData('products_count'),
                'order_by'          =>  $this->getData('order_by'),
                'show'              =>  $this->getData('show'),
                'thumb_width'       =>  $this->getData('thumb_width'),
                'thumb_height'      =>  $this->getData('thumb_height'),
                'filter_template'   =>  $this->getData('filter_template'),
                'custom_template'   =>  $this->getData('custom_template'),
                'show_slider'       =>  $this->getData('show_slider'),
                'slider_item'       =>  $this->getData('slider_item'),
                'conditions_encoded'        =>  $this->getData('conditions_encoded')
            ];
            $block = $this->getLayout()->createBlock('\Magento\Framework\View\Element\Template');
            $block->setTemplate('Codazon_ProductFilter::ajax/first_load.phtml');
            $block->setData('json_data',json_encode($data));
            return $block->toHtml();
		}
	}
}
