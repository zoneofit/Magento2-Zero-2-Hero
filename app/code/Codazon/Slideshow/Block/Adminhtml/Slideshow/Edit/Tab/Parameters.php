<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Block\Adminhtml\Slideshow\Edit\Tab;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Parameters extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{   

	protected $_listEffect;
    protected $_boolean;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Codazon\Slideshow\Model\Config\Source\ListEffect $listEffect        
     * @param array $data
     */
    public function __construct(    
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,                
        \Codazon\Slideshow\Model\Config\Source\ListEffect $listEffect,
        \Codazon\Slideshow\Model\Config\Source\Truefalse $boolean,
        array $data = []
    ) {  
		$this->_listEffect = $listEffect;
        $this->_boolean = $boolean;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form tab configuration
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setShowGlobalIcon(true);
    }

    protected function _prepareForm()
    {        

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(['data' => ['html_id_prefix' => 'parameters_']]);
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $model = $this->_coreRegistry->registry('cdz_slideshow');
				        

        $fieldset = $form->addFieldset(
            'settings_fieldset',
            ['legend' => __('Slideshow Options'), 'class' => 'fieldset-wide']
        );
            
        $fieldset->addField(
            'width',
            'text',
            [
                'name' => 'parameters[width]',
                'label' => __('Width'),
                'title' => __('Width'),                                     
            ]
        );
        $fieldset->addField(
            'height',
            'text',
            [
                'name' => 'parameters[height]',
                'label' => __('Height'),
                'title' => __('Height'),                                     
            ]
        );

        $fieldset->addField(
            'animateIn',
            'select',
            [
                'name' => 'parameters[animateIn]', 
                'label' => __('Animate In'), 
                'title' => __('Animate In'),
                'values' => $this->_listEffect->toOptionArray(),
                'note'  => __('The CSS3 animation type applied when the background animates into view')
            ]
        );


        $fieldset->addField(
            'animateOut',
            'select',
            [
                'name' => 'parameters[animateOut]', 
                'label' => __('Animate Out'), 
                'title' => __('Animate Out'),
                'values' => $this->_listEffect->toOptionArray(),                
                'note'  => __('The CSS3 animation type applied when the background animates out of view')
            ]
        );
        $fieldset->addField(
            'startPosition',
            'text',
            [
                'name' => 'parameters[startPosition]',
                'label' => __('Start Slide'),
                'title' => __('Start Slide'),
                'note' => __('Start position. For example: value 0 starts first slide, value 1 starts second slide')                        
            ]
        );
        $fieldset->addField(
            'autoplay',
            'select',
            [
                'name' => 'parameters[autoplay]', 
                'label' => __('Autoplay'), 
                'title' => __('Autoplay'),
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0'
            ]
        );
		
        $fieldset->addField(
            'autoplayHoverPause',
            'select',
            [
                'name' => 'parameters[autoplayHoverPause]',
                'label' => __('Pause on mouse over'),
                'title' => __('Pause on mouse over'),
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0'                        
            ]
        );

		$fieldset->addField(
            'autoplaySpeed',
            'text',
            [
                'name' => 'parameters[autoplaySpeed]',
                'label' => __('Autoplay Speed'),
                'title' => __('Autoplay Speed'),
                //'value' => '3000'                        
            ]
        );

        $fieldset->addField(
            'loop',
            'select',
            [
                'name' => 'parameters[loop]',
                'label' => __('Loop'),
                'title' => __('Loop'),
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0',
                'note' => __('Inifnity loop. Duplicate last and first items to get loop illusion.')

            ]
        );
        
        $fieldset->addField(
            'nav',
            'select',
            [
                'label' => __('Next & Prev navigation'),
                'title' => __('Next & Prev navigation'),
                'name' => 'parameters[nav]',                
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0'
            ]
        );
        $fieldset->addField(
            'dots',
            'select',
            [
                'label' => __('Paging navigation'),
                'title' => __('Paging navigation'),
                'name' => 'parameters[dots]',                
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '1'
            ]
        );
        $fieldset->addField(
            'controlNavThumbs',
            'select',
            [
                'label' => __('Show Thumbnails'),
                'title' => __('Show Thumbnails'),
                'name' => 'parameters[controlNavThumbs]',                
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0'
            ]
        );
        $fieldset->addField(
            'thumbWidth',
            'text',
            [
                'label' => __('Thumbnail Width'),
                'title' => __('Thumbnail Width'),
                'name' => 'parameters[thumbWidth]',                                                            
            ]
        );
        $fieldset->addField(
            'thumbHeight',
            'text',
            [
                'label' => __('Thumbnail Height'),
                'title' => __('Thumbnail Height'),
                'name' => 'parameters[thumbHeight]',                                                            
            ]
        );
        
        $fieldset->addField(
            'lazyLoad',
            'select',
            [
                'label' => __('Lazy Load'),
                'title' => __('Lazy Load'),
                'name' => 'parameters[lazyLoad]',                
                'values' =>  $this->_boolean->toOptionArray(),
                'value' => '0'
            ]
        );
        
        if($model->getSlideshowId())
            $form->setValues(json_decode($model->getParameters(),true));

        $this->setForm($form);
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            )->addFieldMap(
            "{$htmlIdPrefix}controlNavThumbs",
            'controlNavThumbs'
            )
            ->addFieldMap(
                "{$htmlIdPrefix}thumbWidth",
                'thumbWidth'
            )
            ->addFieldMap(
                "{$htmlIdPrefix}thumbHeight",
                'thumbHeight'
            )
            ->addFieldDependence(
                'thumbWidth',
                'controlNavThumbs',
                '1'
            )
            ->addFieldDependence(
                'thumbHeight',
                'controlNavThumbs',
                '1'
            )
    );
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Slideshow Options');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Slideshow Options');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

}
