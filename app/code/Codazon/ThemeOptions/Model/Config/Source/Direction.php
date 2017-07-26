<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace Codazon\ThemeOptions\Model\Config\Source;

class Direction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
        ['value' => 'horizontal', 'label' => __('Horizontal')], 
        ['value' => 'vertical', 'label' => __('Vertical')]
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
        'horizontal' => __('Horizontal'), 
        'vertical' => __('Vertical')
        ];
    }
}
