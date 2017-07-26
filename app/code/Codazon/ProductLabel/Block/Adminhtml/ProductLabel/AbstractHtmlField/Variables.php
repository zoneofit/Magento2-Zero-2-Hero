<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel\AbstractHtmlField;
use Magento\Framework\Escaper;
class Variables extends \Codazon\ProductLabel\Block\Adminhtml\ProductLabel\AbstractHtmlField
{
    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_element;

    /**
     * @var string
     */
    protected $_template = 'productlabel/content_html/renderer/fieldset/variables.phtml';

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
		$element = $this->_element;
        $html = $this->_getButtonHtml(
                [
                    'title' => __('Insert Variable...'),
                    'onclick' => "variable_".$element->getHtmlId().".openVariableChooser(lbVariables)",
					'disabled' => $element->getDisabled()
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
	public function getVariables()
    {
        $data = array(
            'label' => 'Variables',
            'value' => array(
                array(
                'label' => 'Save Percent',
                'value' => '{{var save_percent}}'
                ),
                array(
                    'label' => 'Save Price',
                    'value' => '{{var save_price}}'
                ),
                array(
                    'label' => 'Product Price',
                    'value' => '{{var product.price}}'
                ),
                array(
                    'label' => 'Product Special Price',
                    'value' => '{{var product.special_price}}'
                ),
                array(
                    'label' => 'The Quantity Of Product',
                    'value' => '{{var product.qty}}'
                )
            )
        );
        $variables = array($data);

        return $variables;
    }
}
