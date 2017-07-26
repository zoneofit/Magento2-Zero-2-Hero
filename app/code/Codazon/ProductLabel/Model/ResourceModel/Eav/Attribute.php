<?php
namespace Codazon\ProductLabel\Model\ResourceModel\Eav;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class Attribute extends \Magento\Eav\Model\Entity\Attribute implements \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface
{
	const MODULE_NAME = 'Codazon_ProductLabel';
    const ENTITY = 'product_label_eav_attribute';
    const KEY_IS_GLOBAL = 'is_global';
	protected $_eventObject = 'attribute';
	protected $_eventPrefix = 'codazon_product_label_entity_attribute';
	protected function _construct()
    {
        $this->_init('Codazon\ProductLabel\Model\ResourceModel\Attribute');
    }
	public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }
	 public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }
	 public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }
}