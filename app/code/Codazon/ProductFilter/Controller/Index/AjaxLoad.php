<?php
/**
 * Product controller.
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductFilter\Controller\Index;

use Magento\Catalog\Controller\Product\View\ViewInterface;
use Magento\Catalog\Model\Product as ModelProduct;

class AjaxLoad extends \Magento\Framework\App\Action\Action
{
   	const PAGE_VAR_NAME = 'np';
	protected $resultPageFactory;
	protected $productsListBlock;
	
	
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Codazon\ProductFilter\Block\Product\Ajax $productsListBlock
    ) {
		$this->resultPageFactory = $resultPageFactory;
        	$this->productsListBlock = $productsListBlock;
		parent::__construct($context);
		
    }
   
    public function execute()
    {
		$this->getResponse()->setHeader('Content-type','application/json');		
		$page = $this->resultPageFactory->create(false, ['isIsolated' => true, 'template'=>'Codazon_ProductFilter::blank.phtml']);
		return $page;
	}
}
