<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Model\Config\Source;

/**
 * Source model for merchant countries supported by PayPal
 */
class Slideshow implements \Magento\Framework\Option\ArrayInterface
{
    

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\CollectionFactory
     */
    protected $_slideshowCollectionFactory;

    /**
     * @param \Magento\Paypal\Model\ConfigFactory $configFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     */
    public function __construct(        
        \Codazon\Slideshow\Model\ResourceModel\Slideshow\CollectionFactory $slideshowCollectionFactory
    ) {

        $this->_slideshowCollectionFactory = $slideshowCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {        
    	$options	= array();
		$options[] = array('value' => '0','label' => 'Please choose slideshow');
				
        $data = $this->_slideshowCollectionFactory->create()->addFieldToFilter("is_active",1)->loadData();
        foreach($data as $value)				
			$options[] = array('value' => $value['identifier'],'label' => $value['title']);		

        return $options;
    }
}
