<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magefan\Blog\Model;
class Show implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        return [
        	['value' => 'thumb', 'label' => __('Thumbnail')],
        	['value' => 'name', 'label' => __('Name')],
        	['value' => 'description', 'label' => __('Description')],
        	['value' => 'published_date', 'label' => __('Published Date')],
			['value' => 'author', 'label' => __('Author')]
        ];
    }

    public function toArray()
    {
        return [
        	['value' => 'thumb', 'label' => __('Thumbnail')],
        	['value' => 'name', 'label' => __('Name')],
        	['value' => 'description', 'label' => __('Description')],
        	['value' => 'published_date', 'label' => __('Published Date')],
			['value' => 'author', 'label' => __('Author')]
        ];
    }
}