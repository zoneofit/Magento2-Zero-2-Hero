<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\ThemeOptions\Setup\Model\Blog;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

class Post
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
    protected $postFactory;

    /**
     * @param SampleDataContext $sampleDataContext
     * @param \Magefan\Blog\ModelFactory $postFactory
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magefan\Blog\Model\PostFactory $postFactory,
        \Magefan\Blog\Model\CategoryFactory $categoryFactory,
        \Magefan\Blog\Model\ResourceModel\Category\CollectionFactory $catCollectionFactory,
        \Magefan\Blog\Model\ResourceModel\Post\Collection $postCollection,
        \Magefan\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->postFactory = $postFactory;
        $this->postCollection = $postCollection;
        $this->postCollectionFactory = $postCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->catCollectionFactory = $catCollectionFactory;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(dirname(__DIR__))).'/fixtures/'.$code;
        $list = array (
			array('title','identifier','categories','content_heading','content','post_image','meta_keywords','meta_description','is_active')
		);
		
		$this->postCollection->addFieldToSelect('*');
		foreach($this->postCollection as $post){
			$data = [];
			foreach($list[0] as $attribute){
				if($attribute == 'categories'){
					$catIdfs = array();
					foreach($post->getData($attribute) as $catId){
						$cat = $this->categoryFactory->create()->load($catId);
						$catIdfs[] = $cat->getData('identifier');
					}
					$data[] = implode(',',$catIdfs);
				}else{
					$data[] = $post->getData($attribute);
				}
			}
			$list[] = $data;
		}

		$fp = fopen($path.'/blog/posts.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export post finish'.'<br/>';
    }

    /**
     * @param array $fixtures
     * @throws \Exception
     */
    public function install($code)
    {
    	$fileName = dirname(dirname(dirname(__DIR__))).'/fixtures/'.$code.'/blog/posts.csv';
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
		    
		    $postCollection = $this->postCollectionFactory->create();
            $postCollection->addFilter('identifier', $row['identifier']);
            if ($postCollection->count() > 0) {
                continue;
            }
			$idfs = explode(',', $row['categories']);
			$cats = array();
			foreach($idfs as $idf){
				$coll = $this->catCollectionFactory->create();
				$coll->addFieldToFilter('identifier',$idf);
				$cat = $coll->getFirstItem();
				if($cat){
					$cats[] = $cat->getId();
				}
			}
			$row['categories'] = $cats;
		    $this->postFactory->create()
		        ->load($row['identifier'], 'identifier')
		        ->addData($row)
		        ->setStores([\Magento\Store\Model\Store::DEFAULT_STORE_ID])
		        ->save();
		}
    }
}
