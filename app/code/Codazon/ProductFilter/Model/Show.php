<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductFilter\Model;


/**
 * Class Rule
 */
class Show implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
        	['value' => 'thumb', 'label' => __('Thumbnail')],
        	['value' => 'name', 'label' => __('Name')],
        	['value' => 'sku', 'label' => __('SKU')],
        	['value' => 'description', 'label' => __('Description')],
        	['value' => 'review', 'label' => __('Review')],
        	['value' => 'price', 'label' => __('Price')],
        	['value' => 'addtocart', 'label' => __('Add to cart')],
        	['value' => 'addto', 'label' => __('Wishlist - Compare')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
        	['value' => 'thumb', 'label' => __('Thumbnail')],
        	['value' => 'name', 'label' => __('Name')],
        	['value' => 'sku', 'label' => __('SKU')],
        	['value' => 'description', 'label' => __('Description')],
        	['value' => 'review', 'label' => __('Review')],
        	['value' => 'price', 'label' => __('Price')],
        	['value' => 'addtocart', 'label' => __('Add to cart')],
        	['value' => 'addto', 'label' => __('Wishlist - Compare')]
        ];
    }
}
