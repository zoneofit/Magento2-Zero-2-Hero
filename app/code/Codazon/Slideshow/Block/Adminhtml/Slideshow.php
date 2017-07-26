<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */


namespace Codazon\Slideshow\Block\Adminhtml;

class Slideshow extends \Magento\Backend\Block\Widget\Container
{
    
    /**
     * @var \Codazon\Slideshow\Model\SlideshowFactory
     */
    protected $_slideshowFactory;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context     
     * @param \Codazon\Slideshow\Model\SlideshowFactory $SlideshowFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,        
        \Codazon\Slideshow\Model\SlideshowFactory $_slideshowFactory,
        array $data = []
    ) {
        $this->_slideshowFactory = $_slideshowFactory;        
        parent::__construct($context, $data);
    }

    /**
     * Prepare button and grid
     *
     * @return \Codazon\Slideshow\Block\Adminhtml\Slideshow
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_slideshow',
            'label' => __('Add Slideshow'),
            'class' => 'add',
            'button_class' => '',                     
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }


    /**
     * Retrieve Slideshow create url
     *
     * @param string $type
     * @return string
     */
    protected function _getSlideshowCreateUrl()
    {
        return $this->getUrl(
            'slideshow/*/new'
        );
    }

}
