<?php
namespace Codazon\ProductLabel\Model;
use Codazon\ProductLabel\Api\Data\ProductLabelInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\IdentityInterface;

class ProductLabel extends \Magento\Rule\Model\AbstractModel implements ProductLabelInterface, IdentityInterface{
	const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
	const CACHE_TAG = 'product_label';
	protected $_cacheTag = 'product_label';
    protected $_eventPrefix = 'productlabel';
	protected $_eventObject = 'label';
	
	protected $_combineFactory;
	protected $_actionCollectionFactory;
	protected $_storeManager;
	protected $_productFactory;
	protected $_productCollectionFactory;
	protected $dateTime;
	protected $_ruleProductProcessor;
	 
	protected $_conditions;
    protected $_actions;
	protected $_storeValuesFlags = [];
	public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Codazon\ProductLabel\Model\ProductLabel\Condition\CombineFactory $conditionsFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->conditionsFactory = $conditionsFactory;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }
	
	public function validateData(\Magento\Framework\DataObject $dataObject){
		return true;	
	}

	
	public function getActionsInstance()
    {
        return null;
    }
	public function getConditionsInstance()
    {
        return $this->conditionsFactory->create();
    }
	
	protected function _construct()
    {
        parent::_construct();
		$this->_init('Codazon\ProductLabel\Model\ResourceModel\ProductLabelEntity');
		$this->setIdFieldName('entity_id');
    }
	
	protected $interfaceAttributes = [
        'id',
		'title',
		'content',
		'conditions_serialized',
		'custom_class',
		'custom_css',
		'label_image',
		'label_background',
		'is_active'
    ];
	
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
	public function getId()
    {
		return $this->getData(self::ENTITY_ID);
    }
	public function getTitle(){
		return $this->getData(self::TITLE);
	}
	public function getContent(){
        return $this->getData(self::CONTENT);
	}
	public function getCreationTime(){
        return $this->getData(self::CREATION_TIME);
	}
	public function getUpdateTime(){
        return $this->getData(self::UPDATE_TIME);
	}
	public function isActive(){
        return (bool) $this->getData(self::IS_ACTIVE);		
	}
	public function getCustomClass(){
		return $this->getData(self::CUSTOM_CLASS);
	}
	public function getCustomCss(){
		return $this->getData(self::CUSTOM_CSS);
	}
	public function getLabelImage(){
		return $this->getData(self::LABEL_IMAGE);
	}
	public function getLabelBackground(){
		return $this->getData(self::LABEL_BACKGROUND);
	}
	
	
	public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
	public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
	public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }
	public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }
	public function setUpdateTime($updateTime)
    {
		return $this->setData(self::UPDATE_TIME, $updateTime);
    }
	public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }
	/*public function setCustomClass($customClass){
		return $this->setData(self::CUSTOM_CLASS, $customClass);
	}
	public function setCustomCss($customCss){
		return $this->setData(self::CUSTOM_CSS, $customCss);
	}
	public function setLabelImage($labelImage){
		return $this->setData(self::LABEL_IMAGE, $labelImage);
	}
	public function setLabelBackground($labelBackground){
		return $this->setData(self::LABEL_BACKGROUND, $labelBackground);
	}*/
	
	
	public function getActions(){
		return null;
	}
	
	public function loadPost(array $data)
    {
        $arr = $this->_convertFlatToRecursive($data);
        if (isset($arr['conditions'])) {
			$this->getConditions()->setConditions([])->loadArray($arr['conditions'][1]);
        }
        return $this;
    }
	
	public function setExistsStoreValueFlag($attributeCode)
    {
        $this->_storeValuesFlags[$attributeCode] = true;
        return $this;
    }
	public function getExistsStoreValueFlag($attributeCode)
    {
        return array_key_exists($attributeCode, $this->_storeValuesFlags);
    }
}