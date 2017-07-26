<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\ThemeOptions\Setup\Model\Blog;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

class Category
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
     * @var \Magefan\Blog\ModelFactory
     */
    protected $categoryFactory;

    /**
     * @param SampleDataContext $sampleDataContext
     * @param \Magefan\Blog\ModelFactory $categoryFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magefan\Blog\Model\CategoryFactory $categoryFactory,
        \Magefan\Blog\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magefan\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->categoryFactory = $categoryFactory;
        $this->categoryCollection = $categoryCollection;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(dirname(__DIR__))).'/fixtures/'.$code;
        $list = array (
			array('title', 'category_image','identifier','content_heading','content','path','is_active')
		);
		
		$this->categoryCollection->addFieldToSelect('*');
		foreach($this->categoryCollection as $category){
			$data = [];
			foreach($list[0] as $attribute){
				$data[] = $category->getData($attribute);
			}
			$list[] = $data;
		}

		$fp = fopen($path.'/blog/categories.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export category finish'.'<br/>';
    }

    /**
     * @param array $fixtures
     * @throws \Exception
     */
    public function install($code)
    {
    	$fileName = dirname(dirname(dirname(__DIR__))).'/fixtures/'.$code.'/blog/categories.csv';
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
		    
		    $categoryCollection = $this->categoryCollectionFactory->create();
            $categoryCollection->addFilter('identifier', $row['identifier']);
            if ($categoryCollection->count() > 0) {
                continue;
            }

		    $this->categoryFactory->create()
		        ->load($row['identifier'], 'identifier')
		        ->addData($row)
		        ->setStores([\Magento\Store\Model\Store::DEFAULT_STORE_ID])
		        ->save();
		}
    }
}
