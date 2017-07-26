<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Model\ResourceModel\Slideshow;


/**
 * Codazon slideshow collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'slideshow_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Define resource model
     *
     * @return void
     */
    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     */
    public function __construct(
    	\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
    	\Psr\Log\LoggerInterface $logger,
    	\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
    	\Magento\Framework\Event\ManagerInterface $eventManager,
    	\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
    	\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    	) {
    		$this->_init('Codazon\Slideshow\Model\Slideshow', 'Codazon\Slideshow\Model\ResourceModel\Slideshow');
    		$this->_map['fields']['slideshow_id'] = 'main_table.slideshow_id';
    		parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    		
    }
    

    /**
     * Returns pairs identifier - title for unique identifiers
     * and pairs identifier|page_id - title for non-unique after first
     *
     * @return array
     */
    public function toOptionIdArray()
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $slideshow_id = $item->getData('slideshow_id');

            $data['value'] = $slideshow_id;
            $data['label'] = $item->getData('title');

            if (in_array($slideshow_id, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('slideshow_id');
            } else {
                $existingIdentifiers[] = $slideshow_id;
            }

            $res[] = $data;
        }

        return $res;
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {    	   
    	return parent::addFieldToFilter($field, $condition);
    }  
    
    /**
     * Get SQL for get record count
     *
     * Extra GROUP BY strip added.
     *
     * @return \Magento\Framework\DB\Select
     */
    public function getSelectCountSql()
    {
    	$countSelect = parent::getSelectCountSql();
    	$countSelect->reset(\Magento\Framework\DB\Select::GROUP);
    
    	return $countSelect;
    }
    
}
