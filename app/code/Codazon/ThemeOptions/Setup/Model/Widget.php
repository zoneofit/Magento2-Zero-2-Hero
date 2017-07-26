<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\ThemeOptions\Setup\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * Launches setup of sample data for Widget module
 */
class Widget
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Widget\Model\Widget\InstanceFactory
     */
    protected $widgetFactory;

    /**
     * @var \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory
     */
    protected $themeCollectionFactory;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $cmsBlockFactory;

    /**
     * @var \Magento\Widget\Model\ResourceModel\Widget\Instance\CollectionFactory
     */
    protected $appCollectionFactory;

    /**
     * @var \Magento\Framework\Setup\SampleData\FixtureManager
     */
    protected $fixtureManager;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvReader;

    /**
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory
     * @param \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeCollectionFactory
     * @param \Magento\Cms\Model\BlockFactory $cmsBlockFactory
     * @param \Magento\Widget\Model\ResourceModel\Widget\Instance\CollectionFactory $appCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory,
        \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeCollectionFactory,
        \Magento\Cms\Model\BlockFactory $cmsBlockFactory,
        \Magento\Widget\Model\ResourceModel\Widget\Instance\CollectionFactory $appCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryFactory,
        \Magento\Widget\Model\ResourceModel\Widget\Instance\CollectionFactory $widgetCollectionFactory,
        \Codazon\Slideshow\Model\SlideshowFactory $slideshowFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->widgetFactory = $widgetFactory;
        $this->themeCollectionFactory = $themeCollectionFactory;
        $this->cmsBlockFactory = $cmsBlockFactory;
        $this->cmsBlock = $cmsBlockFactory->create();
        $this->appCollectionFactory = $appCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->widgetCollectionFactory = $widgetCollectionFactory;
        $this->slideshow = $slideshowFactory->create();
    }
    
    protected function _initWidgetInstance($widget)
    {
        /** @var $widgetInstance \Magento\Widget\Model\Widget\Instance */
        $widgetInstance = $this->widgetFactory->create();

        $code = 'cms_static_block';
        $instanceId = $widget->getInstanceId();
        if ($instanceId) {
            $widgetInstance->load($instanceId)->setCode($code);
            if (!$widgetInstance->getId()) {
                $this->messageManager->addError(__('Please specify a correct widget.'));
                return false;
            }
        } else {
            // Widget id was not provided on the query-string.  Locate the widget instance
            // type (namespace\classname) based upon the widget code (aka, widget id).
            $themeId = $widget->getThemeId();
            $type = $code != null ? $widgetInstance->getWidgetReference('code', $code, 'type') : null;
            $widgetInstance->setType($type)->setCode($code)->setThemeId($themeId);
        }
        return $widgetInstance;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(__DIR__)).'/fixtures/'.$code;
    	
        $list = array (
			array('identifier', 'instance_type', 'theme_path', 'title', 'page_groups', 'sort_order')
		);
		
		$themePath = 'frontend/Codazon/'.$code;
		$theme = $this->themeCollectionFactory->create()->getThemeByFullPath($themePath);
		$themeId = $theme->getId();
		
		$widgetCollection = $this->widgetCollectionFactory->create();
		$widgetCollection->addFieldToSelect('*');
		$widgetCollection->addFieldToFilter('main_table.theme_id', $themeId);
		//$widgetCollection->addFieldToFilter('instance_id',20);
		//$widgetCollection->join(array('wip' => 'widget_instance_page'), 'main_table.instance_id = wip.instance_id');
		$widgetCollection->join(array('t' => 'theme'), 'main_table.theme_id = t.theme_id',array('theme_path'));

		foreach($widgetCollection as $widget){
			
			$data = [];
			$params = $widget->getWidgetParameters();
			$data['identifier'] = '';
			if($params){
				if(isset($params['block_id'])){
					$data['identifier'] = $this->cmsBlock->load($params['block_id'])->getData('identifier');
				}else if(isset($params['slideshow_id'])){
					$data['identifier'] = $this->slideshow->load($params['slideshow_id'])->getData('identifier');
				}
			}
			$data['instance_type'] = $widget->getData('instance_type');
			$data['theme_path'] = 'frontend/'.$widget->getData('theme_path');
			$data['title'] = $widget->getTitle();
			//$data['page_group'] = $widget->getPageGroup();
			//$params = [];
			//$params['block'] = $widget->getData('block_reference');
			//$params['layout_handle'] = $widget->getData('layout_handle');
			$widget = $this->_initWidgetInstance($widget);
			$pageGroups = $widget->getPageGroups();
			$tmpPg = [];
			foreach($pageGroups as $pageGroup){
				$tmp = [];
				$pg = $pageGroup['page_group'];
				$tmp['page_group'] = $pg;
				$tmp[$pg] = [];
				$tmp[$pg]['for'] = $pageGroup['page_for'];
				$tmp[$pg]['layout_handle'] = $pageGroup['layout_handle'];
				$tmp[$pg]['block'] = $pageGroup['block_reference'];
				//$tmp[$pg]['template'] = $pageGroup['page_template'];
				//$tmp[$pg]['page_id'] = '';
				$tmpPg[] = $tmp;
			}
			$pageGroups = $tmpPg;
			$data['page_groups'] = serialize($pageGroups);
			
			$data['sort_order'] = $widget->getData('sort_order');
			$list[] = $data;
		}

		$fp = fopen($path.'/widgets.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export widget finish'.'<br/>';
    }

    /**
     * {@inheritdoc}
     */
    public function install($code)
    {
        $pageGroupConfig = [
            'pages' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'default',
                'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'all_pages' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'default',
                'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'all_products' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'catalog_product_view',
                'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'anchor_categories' => [
                'entities' => '',
                'block' => '',
                'for' => 'all',
                'is_anchor_only' => 0,
                'layout_handle' => 'catalog_category_view_type_layered',
                'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
        ];
        
        $pageGroupConfigSlideshow = [
            'pages' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'default',
                //'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'all_pages' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'default',
                //'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'all_products' => [
                'block' => '',
                'for' => 'all',
                'layout_handle' => 'catalog_product_view',
                //'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
            'anchor_categories' => [
                'entities' => '',
                'block' => '',
                'for' => 'all',
                'is_anchor_only' => 0,
                'layout_handle' => 'catalog_category_view_type_layered',
                //'template' => 'widget/static_block/default.phtml',
                'page_id' => '',
            ],
        ];

       	$fileName = dirname(dirname(__DIR__)).'/fixtures/'.$code.'/widgets.csv';
        //$fileName = $this->fixtureManager->getFixture($fileName);
        if (!file_exists($fileName)) {
            return;
        }

        $rows = $this->csvReader->getData($fileName);
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = [];
            foreach ($row as $key => $value) {
                $data[$header[$key]] = $value;
            }
            $row = $data;
            
            if(!class_exists("\\".$row['instance_type'])){
            	continue;
            }
            /** @var \Magento\Widget\Model\ResourceModel\Widget\Instance\Collection $instanceCollection */
            $instanceCollection = $this->appCollectionFactory->create();
            $instanceCollection->addFilter('title', $row['title']);
            if ($instanceCollection->count() > 0) {
                continue;
            }

            /*$block = $this->cmsBlockFactory->create()->load($row['identifier'], 'identifier');
            if (!$block) {
                continue;
            }*/
            $parameters = [];
            switch($row['instance_type']){
            	case 'Magento\Cms\Block\Widget\Block':
            		$block = $this->cmsBlockFactory->create()->load($row['identifier'], 'identifier');
				    if ($block) {
				        $parameters = ['block_id' => $block->getId()];
				    }
				    break;
			    case 'Codazon\Slideshow\Block\Widget\Slideshow':
			    	$slideshow = $this->slideshow->load($row['identifier'], 'identifier');
				    if ($slideshow) {
				        $parameters = ['slideshow_id' => $slideshow->getId()];
				        $pageGroupConfig = $pageGroupConfigSlideshow;
				    }
				    break;
				default:
					continue;
            }
            $widgetInstance = $this->widgetFactory->create();

            $code = $row['instance_type'];
            $themeId = $this->themeCollectionFactory->create()->getThemeByFullPath($row['theme_path'])->getId();
            $type = $widgetInstance->getWidgetReference('code', $code, 'type');
            /*$pageGroup = [];
            $group = $row['page_group'];
            $pageGroup['page_group'] = $group;
            $pageGroup[$group] = array_merge($pageGroupConfig[$group], unserialize($row['group_data']));
            if (!empty($pageGroup[$group]['entities'])) {
                $pageGroup[$group]['entities'] = $this->getCategoryByUrlKey(
                    $pageGroup[$group]['entities']
                )->getId();
            }*/
            $pageGroups = unserialize($row['page_groups']);
            $tmpPg = [];
            foreach($pageGroups as $pageGroup){
            	$group = $pageGroup['page_group'];
	            $pageGroup[$group] = array_merge($pageGroupConfig[$group], $pageGroup[$group]);
	            if (!empty($pageGroup[$group]['entities'])) {
	                $pageGroup[$group]['entities'] = $this->getCategoryByUrlKey(
	                    $pageGroup[$group]['entities']
	                )->getId();
	            }
	            $tmpPg[] = $pageGroup;
            }
			$pageGroups = $tmpPg;
			//print_r($pageGroups);die;
            $widgetInstance->setType($type)->setInstanceType($code)->setThemeId($themeId);
            $widgetInstance->setTitle($row['title'])
            	->setSortOrder($row['sort_order'])
                ->setStoreIds([\Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setWidgetParameters($parameters)
                ->setPageGroups($pageGroups);
            $widgetInstance->save();
        }
    }

    /**
     * @param string $urlKey
     * @return \Magento\Framework\DataObject
     */
    protected function getCategoryByUrlKey($urlKey)
    {
        $category = $this->categoryFactory->create()
            ->addAttributeToFilter('url_key', $urlKey)
            ->addUrlRewriteToResult()
            ->getFirstItem();
        return $category;
    }
}
