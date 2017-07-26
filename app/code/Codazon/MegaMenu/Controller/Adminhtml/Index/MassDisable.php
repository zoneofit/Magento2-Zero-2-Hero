<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Index;
use Codazon\MegaMenu\Controller\Adminhtml\AbstractMassStatus;
class MassDisable extends AbstractMassStatus{
	const ID_FIELD = 'menu_id';
	protected $collection = 'Codazon\MegaMenu\Model\ResourceModel\Megamenu\Collection';
	protected $model = 'Codazon\MegaMenu\Model\Megamenu';
	protected $status = false;
}