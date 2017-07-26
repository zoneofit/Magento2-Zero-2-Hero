<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Theme\Helper\Storage;
class Main extends Generic implements TabInterface
{
	protected $_systemStore;	
	protected $_groupRepository;
	protected $_searchCriteriaBuilder;
	protected $_objectConverter;
	protected $_wysiwygConfig;
	
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        \Magento\Store\Model\System\Store $systemStore,
		\Codazon\ProductLabel\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;
		$this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
	public function getTabLabel()
    {
        return __('Label Information');
    }
	public function getTabTitle()
    {
        return __('Label Information');
    }
	public function canShowTab()
    {
        return true;
    }
	 public function isHidden()
    {
        return false;
    }
	protected function _prepareForm()
    {
		
		$model = $this->_coreRegistry->registry('productlabel');
		$form = $this->_formFactory->create();
		$form->setHtmlIdPrefix('label_');
		
		

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getEntityId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }
		
		$fieldset->addField('store', 'hidden', ['name' => 'store']);
		
        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Label Title'), 'title' => __('Label Title'), 'required' => true]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
		if (!$model->getId()) {
            $model->setData('is_active', '1');
        }
		$field = $fieldset->addField(
            'content',
            'textarea',
            [
                'label' => __('Label Content'),
                'title' => __('Label Content'),
                'name' => 'content',
                'required' => false,
				//'config' => $this->_wysiwygConfig->getConfig()
            ]
        );
		$renderer = $this->getLayout()->createBlock(
			'Codazon\ProductLabel\Block\Adminhtml\ProductLabel\AbstractHtmlField\Variables'
		);
		$field->setRenderer($renderer);
		$field = $fieldset->addField(
            'label_image',
            'hidden',
            [
                'label' => __('Label Image'),
                'title' => __('Label Image'),
				'name' => 'label_image',
				'class' => 'image_type'
            ]
        );
		$renderer = $this->getLayout()->createBlock(
			'Codazon\ProductLabel\Block\Adminhtml\ProductLabel\AbstractHtmlField\Images'
		);
		$field->setRenderer($renderer);
		
		$field = $fieldset->addField(
            'label_background',
            'hidden',
            [
                'label' => __('Label Background'),
                'title' => __('Label Background'),
				'name' => 'label_background',
				'class' => 'image_type'
            ]
        );
		$renderer = $this->getLayout()->createBlock(
			'Codazon\ProductLabel\Block\Adminhtml\ProductLabel\AbstractHtmlField\Images'
		);
		
		$field->setRenderer($renderer);
		
		$fieldset->addField(
            'custom_class',
            'text',
            [
                'label' => __('Custom Class'),
                'title' => __('Custom Class'),
                'name' => 'custom_class'
            ]
        );
		$fieldset->addField(
            'custom_css',
            'textarea',
            [
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS'),
                'name' => 'custom_css'
            ]
        );
		/* Check is single store mode */
        /*if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }*/
		//$model->setStore($storeId);
		//$model->setStoreId($storeId);
		$form->setDataObject($model);
        $form->setValues($model->getData());
		$this->setForm($form);
		return parent::_prepareForm();	
	}
	
}
