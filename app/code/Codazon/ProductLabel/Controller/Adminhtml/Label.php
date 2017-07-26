<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductLabel\Controller\Adminhtml;

/**
 * Cms manage blocks controller
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
abstract class Label extends\Magento\Backend\App\Action
{
    protected $_coreRegistry = null;
	protected $_dateFilter;
	 protected $_fileFactory;

	public function __construct(
	\Magento\Backend\App\Action\Context $context,
	\Magento\Framework\Registry $coreRegistry,
	\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
	\Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter)
    {
		parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->_dateFilter = $dateFilter;
    }
    protected function initPage($resultPage)
    {
		$resultPage->setActiveMenu('Codazon_ProductLabel::productlabel')
            ->addBreadcrumb(__('Product Labels'), __('Product Labels'))
            ->addBreadcrumb(__('Product Labels'), __('Product Labels'));
        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Codazon_ProductLabel::productlabel');
    }
}
