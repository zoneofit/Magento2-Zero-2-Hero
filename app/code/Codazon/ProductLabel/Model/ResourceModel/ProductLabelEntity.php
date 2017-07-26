<?php
namespace Codazon\ProductLabel\Model\ResourceModel;

class ProductLabelEntity extends AbstractResource
{
	public function __construct(
		\Magento\Eav\Model\Entity\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Codazon\ProductLabel\Model\Factory $modelFactory,
		$data = []
    ) {
        parent::__construct(
            $context,
            $storeManager,
            $modelFactory,
            $data
        );
        $this->connectionName  = 'product_label';
    }

    protected $_tree;
    protected $_productLabelTable;
    protected $_isActiveAttributeId = null;
    protected $_storeId = null;
    protected $_eventManager = null;
    protected $_categoryCollectionFactory;
    protected $_categoryTreeFactory;
	
	protected $interfaceAttributes = [
        'entity_id',
		'title',
		'content',
		'conditions_serialized',
		'custom_class',
		'custom_css',
		'label_image',
		'label_background',
		'is_active'
    ];
	
	public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType('codazon_product_label_entity');
        }
        return parent::getEntityType();
    }
	
	public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }
	
    public function getStoreId()
    {
        if ($this->_storeId === null) {
            return $this->_storeManager->getStore()->getId();
        }
        return $this->_storeId;
    }
	protected function _beforeDelete(\Magento\Framework\DataObject $object)
    {
        parent::_beforeDelete($object);
    }
	/*public function checkId($entityId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getEntityTable(),
            'entity_id'
        )->where(
            'entity_id = :entity_id'
        );
        $bind = ['entity_id' => $entityId];

        return $this->getConnection()->fetchOne($select, $bind);
    }*/
	public function verifyIds(array $ids)
    {       
	    if (empty($ids)) {
            return [];
        }

        $select = $this->getConnection()->select()->from(
            $this->getEntityTable(),
            'entity_id'
        )->where(
            'entity_id IN(?)',
            $ids
        );

        return $this->getConnection()->fetchCol($select);
    }
	    public function getIsActiveAttributeId()
    {
        if ($this->_isActiveAttributeId === null) {
            $this->_isActiveAttributeId = (int)$this->_eavConfig
                ->getAttribute($this->getEntityType(), 'is_active')
                ->getAttributeId();
        }
        return $this->_isActiveAttributeId;
    }
	
    public function findWhereAttributeIs($entityIdsFilter, $attribute, $expectedValue)
    {
	    $bind = ['attribute_id' => $attribute->getId(), 'value' => $expectedValue];
        $select = $this->getConnection()->select()->from(
            $attribute->getBackend()->getTable(),
            ['entity_id']
        )->where(
            'attribute_id = :attribute_id'
        )->where(
            'value = :value'
        )->where(
            'entity_id IN(?)',
            $entityIdsFilter
        );

        return $this->getConnection()->fetchCol($select, $bind);
    }
	

	 
	  /**
     * Retrieve default entity attributes
     *
     * @return string[]
     */
	protected function _getDefaultAttributes()
    {
        return ['title', 'creation_time', 'update_time', 'is_active', 'conditions_serialized'];
    }
	
}
