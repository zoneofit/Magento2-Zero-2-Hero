<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * CatalogRule data helper
 */
namespace Codazon\ProductLabel\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_saleRuleModel;
	protected $_labelModel;
    protected $_labels;
	protected $_storeManager;
	protected $_labelBlock;
	
	public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\SalesRule\Model\Rule $saleRuleModel,
		\Codazon\ProductLabel\Model\ProductLabel $labelModel,
		\Codazon\ProductLabel\Block\ProductLabel $labelBlock,
		\Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
		$this->_saleRuleModel = $saleRuleModel;
		$this->_labelModel = $labelModel;
		$this->_storeManager = $storeManager;
		$this->_labelBlock = $labelBlock;
    }
	public function getLabels(){
		if($this->_labels){
			return $this->_labels;
		}
		$labels = $this->_labelModel->getCollection()->setStoreId($this->_storeManager->getStore(true)->getId())
			->addFieldToFilter('is_active',array('eq' => 1))
			->addAttributeToSelect('content')
			->addAttributeToSelect('label_image')
			->addAttributeToSelect('label_background')
			->addAttributeToSelect('custom_class')
			->addAttributeToSelect('custom_css');
			$this->_labels = $labels;
		return $this->_labels;
	}
	public function showLabel($_product){
		$labels = $this->getLabels();
		$validLabels = [];
		foreach ($labels as $label)
        {
			$conditionsArr = $label->getConditions();
			$this->_labelModel->getConditions()->setConditions([])->loadArray($conditionsArr);
			/*$object = new \Magento\Framework\DataObject();
			$object->setData('product', $_product);
			$object->setData('label', $label);*/
			
			if($validate = (bool)$this->_labelModel->validate($_product)){
				//echo $this->_labelBlock->toHtml();
				$validLabels[] = $label;
            }
		}
		if(!empty($validLabels)){
			$this->_labelBlock->addObject(['labels'=>$validLabels, 'product'=>$_product]);
			echo $this->_labelBlock->toHtml();
		}
	}
	
    public function calcPriceRule($actionOperator, $ruleAmount, $price)
    {
        $priceRule = 0;
        switch ($actionOperator) {
            case 'to_fixed':
                $priceRule = min($ruleAmount, $price);
                break;
            case 'to_percent':
                $priceRule = $price * $ruleAmount / 100;
                break;
            case 'by_fixed':
                $priceRule = max(0, $price - $ruleAmount);
                break;
            case 'by_percent':
                $priceRule = $price * (1 - $ruleAmount / 100);
                break;
        }
        return $priceRule;
    }
}
