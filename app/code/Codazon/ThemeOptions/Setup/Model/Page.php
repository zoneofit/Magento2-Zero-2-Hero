<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\ThemeOptions\Setup\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

class Page
{
    /**
     * @var \Magento\Framework\Setup\SampleData\FixtureManager
     */
    private $fixtureManager;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvReader;

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    protected $pageFactory;

    /**
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\ResourceModel\Page\Collection $pageCollection,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->pageFactory = $pageFactory;
        $this->pageCollection = $pageCollection;
        $this->pageCollectionFactory = $pageCollectionFactory;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(__DIR__)).'/fixtures/'.$code;
        $list = array (
			array('title','page_layout','meta_keywords','meta_description','identifier','content_heading','content','is_active','sort_order','layout_update_xml','custom_theme','custom_root_template','custom_layout_update_xml','custom_theme_from','custom_theme_to')
		);
		
		$this->pageCollection->addFieldToSelect('*');
		foreach($this->pageCollection as $page){
			$data = [];
			foreach($list[0] as $attribute){
				$data[] = $page->getData($attribute);
			}
			$list[] = $data;
		}

		$fp = fopen($path.'/pages.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export page finish'.'<br/>';
    }

    /**
     * @param array $fixtures
     * @throws \Exception
     */
    public function install($code)
    {
    	$fileName = dirname(dirname(__DIR__)).'/fixtures/'.$code.'/pages.csv';
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
		    
		    $pageCollection = $this->pageCollectionFactory->create();
            $pageCollection->addFilter('identifier', $row['identifier']);
            if ($pageCollection->count() > 0) {
                continue;
            }

		    $this->pageFactory->create()
		        ->load($row['identifier'], 'identifier')
		        ->addData($row)
		        ->setStores([\Magento\Store\Model\Store::DEFAULT_STORE_ID])
		        ->save();
		}
    }
}
