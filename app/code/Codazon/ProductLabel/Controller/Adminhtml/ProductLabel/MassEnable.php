<?php
namespace Codazon\ProductLabel\Controller\Adminhtml\ProductLabel;
use Codazon\ProductLabel\Controller\Adminhtml\AbstractMassStatus;
class MassEnable extends AbstractMassStatus{
	const ID_FIELD = 'entity_id';
	protected $collection = 'Codazon\ProductLabel\Model\ResourceModel\ProductLabel\Collection';
	protected $model = 'Codazon\ProductLabel\Model\ProductLabel';
	protected $status = true;
}