<?php
/**
 * Scoped config data collection
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Model\ResourceModel\Config\Collection;

class Scoped extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Scope to filter by
     *
     * @var string
     */
    protected $_scope;

    /**
     * Scope id to filter by
     *
     * @var int
     */
    protected $_scopeId;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Config\Model\ResourceModel\Config\Data $resource
     * @param string $scope
     * @param mixed $connection
     * @param mixed $scopeId
     */
    public function __construct(
    	\Magento\Framework\App\Config $scopeConfig,
        \Magento\Theme\Model\Design $design,
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Codazon\ThemeOptions\Model\ResourceModel\Config\Data $resource,
        \Magento\Theme\Model\ResourceModel\Theme\Collection $themeCollection,
        $scope,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        $scopeId = null
    ) {
        $this->_scope = $scope;
        $this->_scopeId = $scopeId;
        //============== this use for generate static file =============
    	if(isset($_REQUEST['resource'])){
			$uri = $_REQUEST['resource'];
			$data = explode('/',$uri);
			$themePath = $data[1].'/'.$data[2];
			$themeCollection->addFieldToFilter('theme_path',$themePath);
			$theme = $themeCollection->getFirstItem();
			if($theme->getId()){
				$this->themeId = $theme->getId();
			}
		}
		//==============
		if(!isset($this->themeId)){
		    $this->themeId = $scopeConfig->getValue('design/theme/theme_id');
		    $this->design = $design;
		    if($design->getDesign()){
				$this->themeId = $design->getDesign();
			}
		}
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Initialize select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFieldToSelect(['path', 'value'])->addFieldToFilter('scope', $this->_scope);
		$this->addFieldToFilter('theme_id', $this->themeId);
        if ($this->_scopeId !== null) {
            $this->addFieldToFilter('scope_id', $this->_scopeId);
        }
        return $this;
    }
}
