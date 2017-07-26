<?php
namespace  Codazon\ProductLabel\Model;
class Filter extends \Magento\Cms\Model\Template\Filter
{
	protected $_priceHelper;
	protected $_stockItemModel;
	public function __construct(
        \Magento\Framework\Stdlib\StringUtils $string,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Variable\Model\VariableFactory $coreVariableFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\UrlInterface $urlModel,
        \Pelago\Emogrifier $emogrifier,
        \Magento\Email\Model\Source\Variables $configVariables,
		\Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        $this->_priceHelper = $priceHelper;
        parent::__construct(
            $string,
            $logger,
            $escaper,
            $assetRepo,
            $scopeConfig,
            $coreVariableFactory,
            $storeManager,
            $layout,
            $layoutFactory,
            $appState,
            $urlModel,
            $emogrifier,
            $configVariables
        );
    }
	
	public function filter($object)
    {
		if(!is_string($object))
        {
            $value = $object->getText();
            $product = $object->getProduct();
        }
        else{
            $value = $object;
		}
		$customVariables = $this->getCustomVariable();
		
		foreach (array(
            self::CONSTRUCTION_DEPEND_PATTERN => 'dependDirective',
            self::CONSTRUCTION_IF_PATTERN     => 'ifDirective',
            ) as $pattern => $directive) {
            if (preg_match_all($pattern, $value, $constructions, PREG_SET_ORDER)) {
                foreach($constructions as $index => $construction) {
                    $replacedValue = '';
                    $callback = array($this, $directive);
                    if(!is_callable($callback)) {
                        continue;
                    }
                    try {
                        $replacedValue = call_user_func($callback, $construction);
                    } catch (Exception $e) {
                        throw $e;
                    }
                    $value = str_replace($construction[0], $replacedValue, $value);
                }
            }
        }
		
		if(preg_match_all(self::CONSTRUCTION_PATTERN, $value, $constructions, PREG_SET_ORDER)) {
			
            
            foreach($constructions as $index=>$construction) {
                $replacedValue = '';
                $callback = array($this, $construction[1].'Directive');
                if(!is_callable($callback)) {
                    continue;
                }
                try {
					$replacedValue = call_user_func($callback, $construction);

                                        if(in_array($construction[0], $customVariables))
                                        {
                                            $replacedValue = $this->getCustomVariableValue($construction,$product);
                                        }
                } catch (Exception $e) {
                	throw $e;
                }
                $value = str_replace($construction[0], $replacedValue, $value);
            }
        }
        return $value;
	}
	public function getCustomVariable()
    {
        return array(
            '{{var save_percent}}',
            '{{var save_price}}',
            '{{var product.price}}',
            '{{var product.special_price}}',
            '{{var product.qty}}'
        );
    }
	public function getCustomVariableValue($construction,$_product)
    {
        $type = trim($construction[2]);
        if($type == 'save_percent')
        {
            $specialPrice = $_product->getSpecialPrice();
            
			$regularPrice = $_product->getPrice();
			
            if($specialPrice > 0 && $regularPrice != 0)
                return number_format(100*(float)($regularPrice-$specialPrice)/$regularPrice,0);
            else
                return 0;
        }
        elseif($type == 'save_price'){
            $specialPrice = $_product->getSpecialPrice();
            if($specialPrice > 0)
                return $this->_priceHelper->currency($_product->getPrice() - $specialPrice);
            else
                return $this->_priceHelper->currency(0);
        }
        elseif($type == 'product.price')
        {
            return $this->_priceHelper->currency($_product->getPrice());
        }
        elseif($type == 'product.special_price'){
            return $this->_priceHelper->currency($_product->getSpecialPrice());
        }
        else{
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$stockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
            $qty = $stockState->getStockQty($_product->getId(), $_product->getStore()->getWebsiteId());
            return $qty;
        }
    }
}