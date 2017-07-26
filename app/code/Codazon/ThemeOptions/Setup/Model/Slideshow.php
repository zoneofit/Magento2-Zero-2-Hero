<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Setup\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * Class Block
 */
class Slideshow
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
     * @var \Magento\Cms\Model\slideshowFactory
     */
    protected $slideshowFactory;

    /**
     * @var Block\Converter
     */
    protected $converter;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Cms\Model\slideshowFactory $slideshowFactory
     * @param Block\Converter $converter
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        Block\Converter $converter,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Codazon\Slideshow\Model\SlideshowFactory $slideshowFactory,
        \Codazon\Slideshow\Model\ResourceModel\Slideshow\CollectionFactory $slideshowCollectionFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->slideshowFactory = $slideshowFactory;
        $this->converter = $converter;
        $this->categoryRepository = $categoryRepository;
        $this->slideshowCollection = $slideshowCollectionFactory->create();
        $this->slideshowCollectionFactory = $slideshowCollectionFactory;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(__DIR__)).'/fixtures/'.$code;
    	$code = str_replace('_','-',$code);
        $list = array (
			array('title', 'identifier', 'content', 'parameters', 'is_active')
		);
		
		$this->slideshowCollection->addFieldToSelect('*');
		//$this->slideshowCollection->addFieldToFilter('identifier',array('like' => '%'.$code.'%'));
		foreach($this->slideshowCollection as $block){
			$data = [];
			foreach($list[0] as $attribute){
				$data[] = $block->getData($attribute);
			}
			$list[] = $data;
		}

		$fp = fopen($path.'/slideshows.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export slideshows finish'.'<br/>';
    }

    public function install($code)
    {
    	$fileName = dirname(dirname(__DIR__)).'/fixtures/'.$code.'/slideshows.csv';
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
            
            $slideshowCollection = $this->slideshowCollectionFactory->create();
            $slideshowCollection->addFilter('identifier', $row['identifier']);
            if ($slideshowCollection->count() > 0) {
                continue;
            }
            
            //$data = $this->converter->convertRow($row);
            $slideshow = $this->saveSlideshow($data);
            $slideshow->unsetData();
        }
    }

    /**
     * @param array $data
     * @return \Magento\Cms\Model\Block
     */
    protected function saveSlideshow($data)
    {
        $slideshow = $this->slideshowFactory->create();
        $slideshow->getResource()->load($slideshow, $data['identifier']);
        if (!$slideshow->getData()) {
            $slideshow->setData($data);
        } else {
            $slideshow->addData($data);
        }
        $slideshow->save();
        return $slideshow;
    }

    /**
     * @param string $blockId
     * @param string $categoryId
     * @return void
     */
    protected function setCategoryLandingPage($blockId, $categoryId)
    {
        $categoryCms = [
            'landing_page' => $blockId,
            'display_mode' => 'PRODUCTS_AND_PAGE',
        ];
        if (!empty($categoryId)) {
            $category = $this->categoryRepository->get($categoryId);
            $category->setData($categoryCms);
            $this->categoryRepository->save($categoryId);
        }
    }
}
