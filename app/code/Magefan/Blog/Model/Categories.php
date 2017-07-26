<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magefan\Blog\Model;


/**
 * Class Rule
 */
class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
	protected $_categoryCollection;
	public function __construct(
		\Magefan\Blog\Model\ResourceModel\Category\Collection $categoryCollection
	){
		$this->_categoryCollection = $categoryCollection;
	}
	 
    public function toOptionArray()
    {
		$categories[] = ['label' => __('Please select'), 'value' => 0];
        $collection = $this->_categoryCollection->setOrder('position')->getTreeOrderedArray();
		foreach($collection as $item) {
            $categories[] = array(
                'label' => $this->_getSpaces($item->getLevel()).' '.$item->getTitle() . ($item->getIsActive() ? '' : ' ('.__('Disabled').')' ),
                'value' => $item->getId() ,
            );
        }
		
		return $categories;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return $this->toOptionArray();
    }
	protected function _getSpaces($n)
    {
        $s = '';
        for($i = 0; $i < $n; $i++) {
            $s .= '___ ';
        }

        return $s;
    }
}
