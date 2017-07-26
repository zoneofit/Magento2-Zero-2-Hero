<?php
namespace Magefan\Blog\Block\Adminhtml;
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
		//\Magento\Framework\View\Asset\Repository $assetRepo,
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
        $html .= '>';
        $html .= isset($data['title']) ? '<span><span><span>' . $data['title'] . '</span></span></span>' : '';
        $html .= '</button>';

        return $html;
    }
}
