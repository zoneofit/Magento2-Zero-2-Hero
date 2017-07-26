<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Block\Adminhtml\Slideshow;


class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {		
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize codazon slideshow edit block
     *
     * @return void
     */
    protected function _construct()
    {		
        $this->_objectId = 'slideshow_id';
        $this->_blockGroup = 'Codazon_Slideshow';
        $this->_controller = 'adminhtml_slideshow';
        $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        parent::_construct();

      
        
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {		
        if ($this->_coreRegistry->registry('cdz_slideshow')->getId()) {
            return __("Edit Slideshow '%1'", $this->escapeHtml($this->_coreRegistry->registry('cdz_slideshow')->getTitle()));
        } else {
            return __('New Slideshow');
        }
    }

   

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('slideshow/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('slideshow_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'slideshow_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'slideshow_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }

     /**
     * Retrieve customer validation Url.
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('slideshow/*/validate', ['_current' => true]);
    }

}
