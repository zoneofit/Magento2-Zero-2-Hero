<?php
namespace Codazon\ProductLabel\Model\ResourceModel;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult as SearchResult;
use Magento\Framework\Api;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Psr\Log\LoggerInterface as Logger;

class Grid extends SearchResult{

	public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable,
        $resourceModel
    ) {
        
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
			$mainTable,
			$resourceModel
        );
        $this->setMainTable($this->_resource->getTable($mainTable));
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
		//$this->getModel()->setCustomAttribute('title',"AA");
    }
	
	public function setMainTable($table)
    {
        $table = $this->getTable($table);
        if ($this->_mainTable !== null && $table !== $this->_mainTable && $this->getSelect() !== null) {
            $from = $this->getSelect()->getPart(\Magento\Framework\DB\Select::FROM);
            if (isset($from['main_table'])) {
                $from['main_table']['tableName'] = $table;
            }
            $this->getSelect()->setPart(\Magento\Framework\DB\Select::FROM, $from);
        }
		
        $this->_mainTable = $table;
				
        return $this;
    }
	protected function _beforeLoad()
    {
        /*$this->getSelect()
			->join(array('evar'=>$this->getTable('codazon_product_label_entity_varchar')),
				'main_table.entity_id = evar.entity_id AND evar.store_id = 0',['title' => 'evar.value'])
			->join(array('eav'=>$this->getTable('eav_attribute')),'eav.attribute_id = evar.attribute_id',[])
			->where('eav.attribute_code = "title"')
			->group('main_table.entity_id');*/
        return $this;
    }
	
}
?>