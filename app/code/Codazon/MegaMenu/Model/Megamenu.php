<?php
namespace Codazon\MegaMenu\Model;
class Megamenu extends \Magento\Framework\Model\AbstractModel
{
	const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
	const CACHE_TAG = 'megamenu';
	protected $_cacheTag = 'megamenu';
	protected $_eventPrefix = 'megamenu';
	
	protected function _construct()
	{
		$this->_init('Codazon\MegaMenu\Model\ResourceModel\Megamenu');
	}
	public function getAvailableStatuses()
	{
		return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
	}
	public function getAvailableTypes()
	{
		return [0 => __('Horizontal'), 1 => __('Vertical')];
	}
	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}
}
