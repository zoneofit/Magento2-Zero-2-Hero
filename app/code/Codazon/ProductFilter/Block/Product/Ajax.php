<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductFilter\Block\Product;
/**
 * Catalog Products List Ajax block
 * Class ProductsList
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Ajax extends \Magento\Framework\View\Element\Template
{
	const PAGE_VAR_NAME = 'np';
	protected $productsListBlock;
	protected $urlHelper;
	public function __construct(
		\Magento\Catalog\Block\Product\Context $context,
		\Codazon\ProductFilter\Block\Product\ProductsList $productsListBlock,
		\Codazon\ProductFilter\Block\ImageBuilderFactory $customImageBuilderFactory,
        array $data = [] ){
		$this->productsListBlock = $productsListBlock;
		$this->customImageBuilderFactory = $customImageBuilderFactory;
		parent::__construct(
			$context,
			$data
        );
    }
	
	protected function _toHtml(){
		$data = $this->getRequest()->getParams();
		$templateFile = explode('/',$data['custom_template']);
		$templateFile = $templateFile[count($templateFile)-1];
		$templatePath = str_replace($templateFile,'',$data['custom_template']);
		$data['custom_template'] = $templatePath.'ajax_'.$templateFile;
		$data['products_per_page'] = $data['products_count'];
		
		$this->getRequest()->setParam(self::PAGE_VAR_NAME, $data['cur_page']);
		$productBlock = $this->productsListBlock->setData($data);
		$productBlock->getLayout()->createBlock('Magento\Framework\View\Element\FormKey','formkey');
		$productBlock->getLayout()->createBlock('Magento\Framework\Pricing\Render','product.price.render.default')
			->setData('price_render_handle','catalog_product_prices')
			->setData('use_link_for_as_low_as',true);
		$result['last_page'] = $productBlock->createCollection()->getLastPageNumber();
		if($result['last_page'] >= $data['cur_page']){
			$result['html'] = $productBlock->toHtml();
		}else{
			$result['html'] = '';
		}
		$resutl['template'] = $data['custom_template'];
		return json_encode($result);
	}
	
	public function getImage($product, $imageId, $attributes = [])
    {
    	$data = $this->getRequest()->getParams();
        $width = $data['thumb_width'];
        $height = $data['thumb_height'];
        $attributes = array('width'=>$width,'height'=>$height);

        $imageBuilder = $this->customImageBuilderFactory->create();
        return $imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
        return $html;
    }
}
