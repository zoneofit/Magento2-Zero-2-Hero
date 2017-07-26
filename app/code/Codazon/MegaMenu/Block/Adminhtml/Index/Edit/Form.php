<?php
namespace Codazon\MegaMenu\Block\Adminhtml\Index\Edit;
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	/**
	 * @var \Magento\Store\Model\System\Store
	 */
	protected $_systemStore;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\Data\FormFactory $formFactory,
		\Magento\Store\Model\System\Store $systemStore,
		array $data = []
	) {
		$this->_systemStore = $systemStore;
		parent::__construct($context, $registry, $formFactory, $data);
	}
	protected function _construct()
	{
		parent::_construct();
		$this->setId('megamenu_form');
		$this->setTitle(__('Menu Information'));
	}
	protected function _prepareForm()
    {
		$model = $this->_coreRegistry->registry('megamenu');	
		$form = $this->_formFactory->create(
			['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
		);
		$form->setHtmlIdPrefix('menu_');
		$fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Settings'), 'class' => 'fieldset-wide']
        );
		if ($model->getId()) {
			$fieldset->addField('menu_id', 'hidden', ['name' => 'menu_id']);
		}
		$fieldset->addField(
			'title',
			'text',
			['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
		);
		$fieldset->addField(
			'identifier',
			'text',
			['name' => 'identifier', 'label' => __('Identifier'), 'title' => __('Identifier'), 'required' => true]
		);
		$fieldset->addField(
			'type',
			'select',
			[
				'label' => __('Menu type'),
				'title' => __('Menu type'),
				'name' => 'type',
				'required' => true,
				'options' => ['0' => __('Horizontal'), '1' => __('Vertical')],
			]
		);
		$fieldset->addField(
			'css_class',
			'text',
			[
				'label' => __('Wrapper CSS Class'),
				'title' => __('Wrapper CSS Class'),
				'name' => 'css_class',
				'required' => false
			]
		);
		$fieldset->addField(
			'dropdown_animation',
			'select',
			[
				'label' => __('Dropdown Animation'),
				'title' => __('Dropdown Animation'),
				'name' => 'dropdown_animation',
				'options' => ['normal' => 'Normal', 'fade' => __('Fade'),'slide' => __('Slide'), 'translate' => 'Translate'],
				'required' => false
			]
		);
		if($style = $model->getData('style')){
			$style = json_decode($style);
			$model->setData('css_class',$style->css_class);
			$model->setData('dropdown_animation',$style->dropdown_animation);
		}
		if(!$model->getId()){
			$model->setData('is_active',1);
		}
		$checked = $model->getData('is_active');
		
		$html = '<div class="admin__scope-old">
			<div class="product-actions">
				<div class="switcher" onselectstart="return false;" style="float:left; margin-top: 7px;">
					<input type="checkbox" onchange="document.getElementById(\'menu_is_active\').value = (this.checked)?1:0;" id="switch_is_active" '.( ($checked)?'checked':'' ).'>
					<label class="switcher-label" for="switch_is_active" data-text-on="'.__('Enabled').'" data-text-off="'.__('Disabled').'" title="Product online status"></label>
				</div>
			</div>
		</div>';		
		$field = $fieldset->addField(
			'is_active',
			'text',
			[
				'style' => 'display:none',
				'label' => __('Status'),
				'title' => __('Status'),
				'name' => 'is_active',
				'required' => true,
			]
		)->setBeforeElementHtml(
			$html
		);
		
		$renderer = $this->getLayout()->createBlock(
			'Codazon\MegaMenu\Block\Adminhtml\Index\Edit\Fields\MenuItems'
		);
		$field = $fieldset->addField('content', 'hidden', ['name' => 'content', 'label'=>'Menu Content']);
		$field->setRenderer($renderer);
		
		
		$form->setValues($model->getData());
		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}