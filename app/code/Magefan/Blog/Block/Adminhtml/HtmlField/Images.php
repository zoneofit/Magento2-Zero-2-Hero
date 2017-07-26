<?php
namespace Magefan\Blog\Block\Adminhtml\HtmlField;
use Magento\Framework\Escaper;
class Images extends \Magefan\Blog\Block\Adminhtml\AbstractHtmlField
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
    protected $_template = 'renderer/fieldset/images.phtml';

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
	public function getBaseUrl()
    {		
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getHintHtml()
    {
		$element = $this->_element;
		$elementId = $element->getHtmlId();
		$imageId = $elementId.'_image';
		
		if( !empty( $element->getValue() ) ){
			$imageUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$element->getValue();
		}else{
			$imageUrl = $this->assetRepo->getUrl('Magefan_Blog/images/placeholder_thumbnail.jpg');	
		}
		
		$html = $this->_getButtonHtml(
			[
				'title' => __('Insert Image'),
				'style' => ' float: left; margin-right: 10px;',
				'onclick' => "MediabrowserUtility.openDialog('" . $this->getUrl('cms/wysiwyg_images/index',
					['target_element_id' => $elementId])
					. "', null, null,'" . $this->escapeQuote(
						__('Upload Images'), true) . "');",
			]
		);
        $html .= '<a class="attached_image" style="float: left;" href="'.$imageUrl.'" onclick="imagePreview(\''.$imageId.'\'); return false;"><img height="33" id="'.$imageId.'" src="'.$imageUrl.'" /></a>';
        return $html;
    }
}
