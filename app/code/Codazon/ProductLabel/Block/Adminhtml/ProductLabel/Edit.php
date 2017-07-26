<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel;
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
	protected $_coreRegistry = null;
	public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
	
	protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Codazon_ProductLabel';
        $this->_controller = 'adminhtml_productLabel';
		
        parent::_construct();

        if ($this->_isAllowedAction('Codazon_ProductLabel::save')) {
            $this->buttonList->update('save', 'label', __('Save Label'));
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
        } else {
            $this->buttonList->remove('save');
        }
		
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }
        ";
        if ($this->_isAllowedAction('Codazon_ProductLabel::productlabel_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Label'));
        } else {
            $this->buttonList->remove('delete');
        }
    }
	public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('productlabel')->getId()) {
            return __("Edit Label '%1'", $this->escapeHtml($this->_coreRegistry->registry('productlabel')->getTitle()));
        } else {
            return __('New Label');
        }
    }
	protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
	protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('productlabel/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}