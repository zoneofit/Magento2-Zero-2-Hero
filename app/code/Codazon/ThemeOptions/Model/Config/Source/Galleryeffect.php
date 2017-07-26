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

class Galleryeffect implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
              ['value' => 'slide', 'label' => __('Slide')], 
              ['value' => 'crossfade', 'label' => __('Crossfade')],
              ['value' => 'dissolve', 'label' => __('Dissolve')]
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
              'slide' => __('Slide'), 
              'crossfade' => __('Crossfade'),
              'dissolve' => __('Dissolve'),
        ];
    }
}
