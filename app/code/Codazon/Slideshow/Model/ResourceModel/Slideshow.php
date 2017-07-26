<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Codazon\Slideshow\Model\ResourceModel;

/**
 * Codazon slideshow mysql resource
 */
class Slideshow extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
   
    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context     
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,        
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);        
        $this->dateTime = $dateTime;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('cdz_slideshow', 'slideshow_id');
    }

    

    /**
     * Process page data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {        

        if (!$this->isValidSlideshowIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The slideshow identifier key contains capital letters or disallowed symbols.')
            );
        }

        if ($this->isNumericSlideshowIdentifier($object)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The slideshow identifier key cannot be made of only numbers.')
            );
        }
        return parent::_beforeSave($object);
    }

    

    /**
     * Load an object using 'identifier' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Codazon\Slideshow\Model\Page $object
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);        

        return $select;
    }

    /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['cs' => $this->getMainTable()]
        )->where(
            'cs.identifier = ?',
            $identifier
        );

        if (!is_null($isActive)) {
            $select->where('cs.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     *  Check whether slideshow identifier is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericSlideshowIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether slideshow identifier is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidSlideshowIdentifier(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     * Check if slideshow identifier exist for specific store
     * return slideshow id if slideshow exists
     *
     * @param string $identifier     
     * @return int
     */
    public function checkIdentifier($identifier)
    {        
        $select = $this->_getLoadByIdentifierSelect($identifier, 1);
        $select->reset(\Magento\Framework\DB\Select::COLUMNS)->columns('cs.slideshow_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }   

    /**
     * Retrieves cms page identifier from DB by passed id.
     *
     * @param string $id
     * @return string|false
     */
   /*  public function getCmsPageIdentifierById($id)
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from($this->getMainTable(), 'identifier')->where('page_id = :page_id');

        $binds = ['page_id' => (int)$id];

        return $connection->fetchOne($select, $binds);
    } */

   
}
