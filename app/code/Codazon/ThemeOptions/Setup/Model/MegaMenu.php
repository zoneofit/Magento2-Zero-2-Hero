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
class MegaMenu
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
    protected $MegamenuFactory;

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
     * @param \Magento\Cms\Model\MegamenuFactory $MegamenuFactory
     * @param Block\Converter $converter
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        Block\Converter $converter,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Codazon\MegaMenu\Model\MegamenuFactory $megaMenuFactory,
        \Codazon\MegaMenu\Model\ResourceModel\Megamenu\CollectionFactory $megaMenuCollectionFactory
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->megaMenuFactory = $megaMenuFactory;
        $this->converter = $converter;
        $this->categoryRepository = $categoryRepository;
        $this->megaMenuCollection = $megaMenuCollectionFactory->create();
        $this->megaMenuCollectionFactory = $megaMenuCollectionFactory;
    }
    
    public function export($code)
    {
    	$path = dirname(dirname(__DIR__)).'/fixtures/'.$code;
    	$code = str_replace('_','-',$code);
        $list = array (
			array('identifier', 'title', 'type', 'content','is_active', 'style')
		);
		
		$this->megaMenuCollection->addFieldToSelect('*');
		//$this->megaMenuCollection->addFieldToFilter('identifier',array('like' => '%'.$code.'%'));
		foreach($this->megaMenuCollection as $block){
			$data = [];
			foreach($list[0] as $attribute){
				$data[] = $block->getData($attribute);
			}
			$list[] = $data;
		}

		$fp = fopen($path.'/megamenus.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
		echo 'export megamenus finish'.'<br/>';
    }

    public function install($code)
    {
    	$fileName = dirname(dirname(__DIR__)).'/fixtures/'.$code.'/megamenus.csv';
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
            
            $megaMenuCollection = $this->megaMenuCollectionFactory->create();
            $megaMenuCollection->addFilter('identifier', $row['identifier']);
            if ($megaMenuCollection->count() > 0) {
                continue;
            }
            
            //$data = $this->converter->convertRow($row);
            $megaMenu = $this->saveMegaMenu($data);
            $megaMenu->unsetData();
        }
    }

    /**
     * @param array $data
     * @return \Magento\Cms\Model\Block
     */
    protected function saveMegaMenu($data)
    {
        $megaMenu = $this->megaMenuFactory->create();
        $megaMenu->getResource()->load($megaMenu, $data['identifier']);
        if (!$megaMenu->getData()) {
            $megaMenu->setData($data);
        } else {
            $megaMenu->addData($data);
        }
        $megaMenu->save();
        return $megaMenu;
    }
}
