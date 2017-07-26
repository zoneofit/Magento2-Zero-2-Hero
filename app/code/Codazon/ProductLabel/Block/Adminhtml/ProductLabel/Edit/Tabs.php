<?php
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel\Edit;
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('product_label_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Product Label'));
    }
}
