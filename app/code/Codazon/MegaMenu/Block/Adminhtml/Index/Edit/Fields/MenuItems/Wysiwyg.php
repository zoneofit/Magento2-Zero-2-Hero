<?php
namespace Codazon\MegaMenu\Block\Adminhtml\Index\Edit\Fields\MenuItems;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
class Wysiwyg extends Generic
{
	 protected $_wysiwygConfig;
	 public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
	protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => ['id' => 'wysiwyg_edit_form', 'action' => $this->getData('action'), 'method' => 'post'],
            ]
        );
        $config['container_class'] = 'hor-scroll';

        $form->addField(
            $this->getData('editor_element_id'),
            'editor',
            [
                'name' => 'content',
                'style' => 'width:725px;height:460px',
                'required' => true,
                'force_load' => true,
                'config' => $this->_wysiwygConfig->getConfig($config)
            ]
        );
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
?>