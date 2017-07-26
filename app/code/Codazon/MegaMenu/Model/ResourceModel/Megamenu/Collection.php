<?php
namespace Codazon\MegaMenu\Model\ResourceModel\Megamenu;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
	{
		$this->_init('Codazon\MegaMenu\Model\Megamenu','Codazon\MegaMenu\Model\ResourceModel\Megamenu');
	}
	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
		
	}
	
	
}
