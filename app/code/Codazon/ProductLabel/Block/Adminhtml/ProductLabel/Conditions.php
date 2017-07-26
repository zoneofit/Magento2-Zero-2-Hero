<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel;
use Magento\Framework\Data\Form\Element\AbstractElement;
class Conditions extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
{
	protected $_attribute = 'conditions_serialized';
	public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }
	public function getAttribute()
    {
        return $this->getElement()->getEntityAttribute();
    }
	public function getAttributeCode()
    {
    	return $this->_attribute;
    }
	public function canDisplayUseDefault()
    {
		if (!$this->isScopeGlobal() &&
			$this->getDataObject() &&
			$this->getDataObject()->getId() &&
			$this->getDataObject()->getStore()
		) {
			return true;
		}
        return false;
    }
	public function usedDefault()
    {
    	$attributeCode = $this->getAttributeCode();
        $defaultValue = $this->getDataObject()->getDefaultValue($attributeCode);
		
		
        if (!$this->getDataObject()->getExistsStoreValueFlag($attributeCode)) {
            return true;
        } elseif ($this->getElement()->getValue() == $defaultValue &&
            $this->getDataObject()->getStore() != $this->_getDefaultStoreId()
        ) {
            return false;
        }
        if ($defaultValue === false && $this->getElement()->getValue()) {
            return false;
        }
        return $defaultValue === false;
    }
	public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }
	protected function isScopeGlobal()
    {
    	return true;
    }
	protected function isScopeStore()
    {
    	return false;
    }
	public function getScopeLabel()
    {
        $html = '';
        if ($this->isScopeGlobal()) {
            $html .= __('[GLOBAL]');
        } elseif ($this->isScopeStore()) {
            $html .= __('[STORE VIEW]');
        }

        return $html;
    }
	protected function _getDefaultStoreId()
    {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }
}