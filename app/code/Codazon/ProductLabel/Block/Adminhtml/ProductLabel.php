<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductLabel\Block\Adminhtml;

/**
 * Adminhtml cms blocks content block
 */
class ProductLabel extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Codazon_ProductLabel';
        $this->_controller = 'adminhtml_productLabel';
        $this->_headerText = __('Products Label');
        $this->_addButtonLabel = __('Add New Label');
        parent::_construct();
    }
}
