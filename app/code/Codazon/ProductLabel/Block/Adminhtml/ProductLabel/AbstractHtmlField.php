<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel;
use Magento\Framework\Escaper;
class AbstractHtmlField extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
	protected $assetRepo;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = [])
    {
		$this->assetRepo = $context->getAssetRepository();
        parent::__construct($context, $data);
    }
    protected $_element;

    /**
     * @var string
     */
    protected $_template = 'productlabel/content_html/renderer/fieldset/element.phtml';

    /**
     * Retrieve an element
     *
     * @return \Magento\Framework\Data\Form\Element\Fieldset
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Render element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }

    /**
     * Return html for store switcher hint
     *
     * @return string
     */
    public function getHintHtml()
    {
        $html = $this->_getButtonHtml(
                [
                    'title' => __('Insert Variable...'),
                    'onclick' => "variable_".$this->_element->getHtmlId().".openVariableChooser(lbVariables)",
                ]
            );
        return $html;
    }
	protected function _getButtonHtml($data)
    {
        $html = '<button type="button"';
        $html .= ' class="scalable ' . (isset($data['class']) ? $data['class'] : '') . '"';
        $html .= isset($data['onclick']) ? ' onclick="' . $data['onclick'] . '"' : '';
        $html .= isset($data['style']) ? ' style="' . $data['style'] . '"' : '';
        $html .= isset($data['id']) ? ' id="' . $data['id'] . '"' : '';
        $html .= (isset($data['disabled']) && $data['disabled'] == true)? ' disabled' : '';
		$html .= '>';
        $html .= isset($data['title']) ? '<span><span><span>' . $data['title'] . '</span></span></span>' : '';
        $html .= '</button>';

        return $html;
    }
	
	public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }
	public function getAttribute()
    {
        return $this->getElement()->getEntityAttribute();
    }
	public function getAttributeCode()
    {
    	return $this->getElement()->getName();
    }
	public function canDisplayUseDefault()
    {
		if (!$this->isScopeGlobal() &&
			$this->getDataObject() &&
			$this->getDataObject()->getId() &&
			$this->getDataObject()->getStore()
		) {
			return true;
		}
        return false;
    }
	public function usedDefault()
    {
    	$attributeCode = $this->getElement()->getName();
        $defaultValue = $this->getDataObject()->getDefaultValue($attributeCode);
		
        if (!$this->getDataObject()->getExistsStoreValueFlag($attributeCode)) {
            return true;
        } elseif ($this->getElement()->getValue() == $defaultValue &&
            $this->getDataObject()->getStore() != $this->_getDefaultStoreId()
        ) {
            return false;
        }
        if ($defaultValue === false && $this->getElement()->getValue()) {
            return false;
        }
        return $defaultValue === false;
    }
	public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }
	protected function isScopeGlobal()
    {
    	$names = ['entity_id','store','title','is_active'];
    	if(in_array($this->getElement()->getName(), $names)){
    		return true;
    	}else{
    		return false;
    	}
    }
	protected function isScopeStore()
    {
    	$names = ['content','label_image','label_background','custom_class','custom_css'];
    	if(in_array($this->getElement()->getName(), $names)){
    		return true;
    	}else{
    		return false;
    	}
    }
	public function getScopeLabel()
    {
        $html = '';
        if ($this->isScopeGlobal()) {
            $html .= __('[GLOBAL]');
        } elseif ($this->isScopeStore()) {
            $html .= __('[STORE VIEW]');
        }

        return $html;
    }
	protected function _getDefaultStoreId()
    {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }
}
