<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
* See COPYING.txt for license details.
*/
namespace Codazon\Slideshow\Block\Adminhtml\Slideshow;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
	/**
	 * @var \Codazon\Slideshow\Model\ResourceModel\Slideshow\CollectionFactory
	 */
	protected $_collectionFactory;

	/**
	 * @var \Codazon\Slideshow\Model\Slideshow
	 */
	protected $_slideshow;

	/**
	 * @var \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface
	 */
	protected $pageLayoutBuilder;

	/**
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Backend\Helper\Data $backendHelper
	 * @param \Codazon\Slideshow\Model\Slideshow $slideshow,
	 * @param \Codazon\Slideshow\Model\ResourceModel\Slideshow\CollectionFactory $collectionFactory
	 * @param \Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Backend\Helper\Data $backendHelper,
		\Codazon\Slideshow\Model\Slideshow $slideshow,
		\Codazon\Slideshow\Model\ResourceModel\Slideshow\Collection $collectionFactory,
		\Magento\Framework\View\Model\PageLayout\Config\BuilderInterface $pageLayoutBuilder,
		array $data = []
		) {
			$this->_collectionFactory = $collectionFactory;
			$this->$_slideshow = $slideshow;
			$this->pageLayoutBuilder = $pageLayoutBuilder;
			parent::__construct($context, $backendHelper, $data);
	}

	/**
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setId('slideshowGrid');
		$this->setDefaultSort('identifier');
		$this->setDefaultDir('ASC');
	}

	/**
	 * Prepare collection
	 *
	 * @return \Magento\Backend\Block\Widget\Grid
	 */
	protected function _prepareCollection()
	{
		$collection = null;
		/* @var $collection \Codazon\Slideshow\Model\ResourceModel\Slideshow\CollectionFactory */		
		$this->setCollection($collection);

		return parent::_prepareCollection();
	}

	/**
	 * Prepare columns
	 *
	 * @return \Magento\Backend\Block\Widget\Grid\Extended
	 */
	protected function _prepareColumns()
	{
		$this->addColumn('title', ['header' => __('Title'), 'index' => 'title']);

		$this->addColumn('identifier', ['header' => __('Identifier'), 'index' => 'identifier']);

		

		$this->addColumn(
			'is_active',
			[
					'header' => __('Status'),
					'index' => 'is_active',
					'type' => 'options',
					'options' => $this->_slideshow->getAvailableStatuses()				
			]
			);

		$this->addColumn(
			'creation_time',
			[
					'header' => __('Created'),
					'index' => 'creation_time',
					'type' => 'datetime',
					'header_css_class' => 'col-date',
					'column_css_class' => 'col-date'
			]
			);

		$this->addColumn(
			'update_time',
			[
					'header' => __('Modified'),
					'index' => 'update_time',
					'type' => 'datetime',
					'header_css_class' => 'col-date',
					'column_css_class' => 'col-date'
			]
			);

	

		return parent::_prepareColumns();
	}

	/**
	 * After load collection
	 *
	 * @return void
	 */
	protected function _afterLoadCollection()
	{
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}

	

	/**
	 * Row click url
	 *
	 * @param \Magento\Framework\DataObject $row
	 * @return string
	 */
	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', ['slideshow_id' => $row->getId()]);
	}
}
