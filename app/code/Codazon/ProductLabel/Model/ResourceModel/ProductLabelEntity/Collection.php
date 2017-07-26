<?php
namespace Codazon\ProductLabel\Model\ResourceModel\ProductLabelEntity;

class Collection extends  \Codazon\ProductLabel\Model\ResourceModel\Collection\AbstractCollection//\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
    {
		$this->_init('Codazon\ProductLabel\Model\ProductLabel', 'Codazon\ProductLabel\Model\ResourceModel\ProductLabelEntity');
    }
}
