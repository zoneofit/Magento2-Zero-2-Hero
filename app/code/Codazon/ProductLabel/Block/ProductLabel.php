<?php
namespace Codazon\ProductLabel\Block;
class ProductLabel extends \Magento\Framework\View\Element\Template{
	public $objectManager;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		array $data = [])
    {
		$this->objectManager = $objectManager;
        parent::__construct($context, $data);
    }
	protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('productlabel.phtml');
    }
	public function addObject($_object)
    {
        $this->setData('object',$_object);
        return $this;
    }
	public function getObject()
    {
        return $this->getData('object');
    }
}