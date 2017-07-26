<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Catalog Rule Product Condition data model
 */
namespace Codazon\ProductLabel\Model\ProductLabel\Condition;

/**
 * Class Product
 */
class Product extends \Magento\Rule\Model\Condition\Product\AbstractProduct
{
    /**
     * {@inheritdoc}
     */
    protected $elementName = 'rule';

    /**
     * @var array
     */
    protected $joinedAttributes = [];

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Backend\Helper\Data $backendData
     * @param \Magento\Eav\Model\Config $config
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Backend\Helper\Data $backendData,
        \Magento\Eav\Model\Config $config,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $attrSetCollection,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct(
            $context,
            $backendData,
            $config,
            $productFactory,
            $productRepository,
            $productResource,
            $attrSetCollection,
            $localeFormat,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadAttributeOptions()
    {
        $productAttributes = $this->_productResource->loadAllAttributes()->getAttributesByCode();

        $attributes = [];
        foreach ($productAttributes as $attribute) {
            if (!$attribute->getFrontendLabel() || $attribute->getFrontendInput() == 'text') {
                continue;
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        parent::_addSpecialAttributes($attributes);
        $attributes['sku'] = __('SKU');
    }

    /**
     * Add condition to collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return $this
     */
    public function addToCollection($collection)
    {
        $attribute = $this->getAttributeObject();
        if ('category_ids' == $attribute->getAttributeCode() || $attribute->isStatic()) {
            return $this;
        }

        if ($attribute->getBackend() && $attribute->isScopeGlobal()) {
            $this->addGlobalAttribute($attribute, $collection);
        } else {
            $this->addNotGlobalAttribute($attribute, $collection);
        }

        $attributes = $this->getRule()->getCollectedAttributes();
        $attributes[$attribute->getAttributeCode()] = true;
        $this->getRule()->setCollectedAttributes($attributes);

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return $this
     */
    protected function addGlobalAttribute(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        $storeId =  $this->storeManager->getStore()->getId();

        switch ($attribute->getBackendType()) {
            case 'decimal':
            case 'datetime':
            case 'int':
                $alias = 'at_' . $attribute->getAttributeCode();
                $collection->addAttributeToSelect($attribute->getAttributeCode(), 'inner');
                break;
            default:
                $alias = 'at_'. md5($this->getId()) . $attribute->getAttributeCode();
                $collection->getSelect()->join(
                    [$alias => $collection->getTable('catalog_product_index_eav')],
                    "($alias.entity_id = e.entity_id) AND ($alias.store_id = $storeId)" .
                    " AND ($alias.attribute_id = {$attribute->getId()})",
                    []
                );
        }

        $this->joinedAttributes[$attribute->getAttributeCode()] = $alias;

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return $this
     */
    protected function addNotGlobalAttribute(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        $storeId =  $this->storeManager->getStore()->getId();
        $values = $collection->getAllAttributeValues($attribute);
        $validEntities = [];
        if ($values) {
            foreach ($values as $entityId => $storeValues) {
                if (isset($storeValues[$storeId])) {
                    if ($this->validateAttribute($storeValues[$storeId])) {
                        $validEntities[] = $entityId;
                    }
                } else {
                    if ($this->validateAttribute($storeValues[\Magento\Store\Model\Store::DEFAULT_STORE_ID])) {
                        $validEntities[] = $entityId;
                    }
                }
            }
        }
        $this->setOperator('()');
        $this->unsetData('value_parsed');
        if ($validEntities) {
            $this->setData('value', implode(',', $validEntities));
        } else {
            $this->unsetData('value');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMappedSqlField()
    {
        $result = '';
        if ($this->getAttribute() == 'category_ids') {
            $result = parent::getMappedSqlField();
        } elseif ($this->getAttributeObject()->isStatic()) {
            $result = $this->getAttributeObject()->getAttributeCode();
        } elseif ($this->getAttributeObject()->isScopeGlobal()) {
            if (isset($this->joinedAttributes[$this->getAttribute()])) {
                $result = $this->joinedAttributes[$this->getAttribute()] . '.value';
            } else {
                $result = parent::getMappedSqlField();
            }
        } elseif ($this->getValueParsed()) {
            $result = 'e.entity_id';
        }

        return $result;
    }
	public function validateAttribute($validatedValue)
    {
        if (is_object($validatedValue)) {
            return false;
        }

        /**
         * Condition attribute value
         */
        $value = $this->getValueParsed();

        /**
         * Comparison operator
         */
        $option = $this->getOperatorForValidate();

        // if operator requires array and it is not, or on opposite, return false
        if ($this->isArrayOperatorType() xor is_array($value)) {
            return false;
        }

        $result = false;

        switch ($option) {
            case '==':
            case '!=':
			
                if (is_array($value)) {
                    if (is_array($validatedValue)) {
                        $result = array_intersect($value, $validatedValue);
                        $result = !empty($result);
                    } else {
                        return false;
                    }
                } else {
                    if (is_array($validatedValue)) {
                        $result = count($validatedValue) == 1 && array_shift($validatedValue) == $value;
                    } else {
                        $result = $this->_compareValues($validatedValue, $value);
                    }
                }
                break;

            case '<=':
            case '>':
                if (!is_scalar($validatedValue)) {
                    return false;
                } else {
                    $result = $validatedValue <= $value;
                }
				break;
				
            case '>=':
            case '<':
                if (!is_scalar($validatedValue)) {
                    return false;
                } else {
                    $result = $validatedValue >= $value;
                }
                break;
            case '{}':
            case '!{}':
                if (is_scalar($validatedValue) && is_array($value)) {
                    foreach ($value as $item) {
                        if (stripos($validatedValue, (string)$item) !== false) {
                            $result = true;
                            break;
                        }
                    }
                } elseif (is_array($value)) {
                    if (is_array($validatedValue)) {
                        $result = array_intersect($value, $validatedValue);
                        $result = !empty($result);
                    } else {
                        return false;
                    }
                } else {
                    if (is_array($validatedValue)) {
                        $result = in_array($value, $validatedValue);
                    } else {
                        $result = $this->_compareValues($value, $validatedValue, false);
                    }
                }
                break;

            case '()':
            case '!()':
                if (is_array($validatedValue)) {
                    $result = count(array_intersect($validatedValue, (array)$value)) > 0;
                } else {
                    $value = (array)$value;
                    foreach ($value as $item) {
                        if ($this->_compareValues($validatedValue, $item)) {
                            $result = true;
                            break;
                        }
                    }
                }
                break;
        }

        if ('!=' == $option || '>' == $option || '<' == $option || '!{}' == $option || '!()' == $option) {
            $result = !$result;
        }

        return $result;
    }
	public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $attrCode = $this->getAttribute();

        if ('category_ids' == $attrCode) {
            return $this->validateAttribute($model->getAvailableInCategories());
        } elseif (!isset($this->_entityAttributeValues[$model->getId()])) {
            if (!$model->getResource()) {
                return false;
            }
            $attr = $model->getResource()->getAttribute($attrCode);

            if ($attr && $attr->getBackendType() == 'datetime' && !is_int($this->getValue())) {
                $this->setValue(strtotime($this->getValue()));
                $value = strtotime($model->getData($attrCode));
                return $this->validateAttribute($value);
            }

            if ($attr && $attr->getFrontendInput() == 'multiselect') {
                $value = $model->getData($attrCode);
                $value = strlen($value) ? explode(',', $value) : [];
                return $this->validateAttribute($value);
            }

            return parent::validate($model);
        } else {
            $result = false;
            // any valid value will set it to TRUE
            // remember old attribute state
            $oldAttrValue = $model->hasData($attrCode) ? $model->getData($attrCode) : null;

            foreach ($this->_entityAttributeValues[$model->getId()] as $value) {
                $attr = $model->getResource()->getAttribute($attrCode);
                if ($attr && $attr->getBackendType() == 'datetime') {
                    $value = strtotime($value);
                } elseif ($attr && $attr->getFrontendInput() == 'multiselect') {
                    $value = strlen($value) ? explode(',', $value) : [];
                }

                $model->setData($attrCode, $value);
                $result |= parent::validate($model);

                if ($result) {
                    break;
                }
            }

            if ($oldAttrValue === null) {
                $model->unsetData($attrCode);
            } else {
                $model->setData($attrCode, $oldAttrValue);
            }

            return (bool)$result;
        }
    }
}
