<?php namespace Codazon\MegaMenu\Block\Adminhtml\Index\Edit\Fields;
class MenuItems extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
	protected $_assetRepo;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = [])
    {
		$this->_assetRepo = $context->getAssetRepository();
        parent::__construct($context, $data);	
    }
	protected $_template = 'megamenu/fields/menu_items.phtml';
	public function getElement()
    {
		$element = $this->_element;
        return $this->_element;
    }
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
}
?>