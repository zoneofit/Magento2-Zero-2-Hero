<?php
/**
 * Copyright © 2015 Codazon. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Controller\Export;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Catalog\Controller\Product
{
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Cms\Model\ResourceModel\Page\Collection $pageCollection,
		\Magento\Cms\Model\ResourceModel\Block\Collection $blockCollection,
		\Magento\Cms\Model\Block $block,
		\Magento\Widget\Model\ResourceModel\Widget\Instance\Collection $widgetCollection,
		\Magento\Widget\Model\Widget\InstanceFactory $widgetFactory,
		\Codazon\ThemeOptions\Setup\Model\Page $pageSetup,
		\Codazon\ThemeOptions\Setup\Model\Block $blockSetup,
		\Codazon\ThemeOptions\Setup\Model\Widget $widgetSetup,
		\Codazon\ThemeOptions\Setup\Model\Slideshow $slideshowSetup,
		\Codazon\ThemeOptions\Setup\Model\Blog\Category $blogCategorySetup,
		\Codazon\ThemeOptions\Setup\Model\Blog\Post $blogPostSetup,
		\Codazon\ThemeOptions\Setup\Model\MegaMenu $megaMenuSetup,
		\Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeCollectionFactory
	)
	{
		$this->_widgetFactory = $widgetFactory;
		$this->pageCollection = $pageCollection;
		$this->blockCollection = $blockCollection;
		$this->widgetCollection = $widgetCollection;
		$this->themeCollection = $themeCollectionFactory->create();
		$this->pageSetup = $pageSetup;
		$this->blockSetup = $blockSetup;
		$this->widgetSetup = $widgetSetup;
		$this->slideshowSetup = $slideshowSetup;
		$this->blogCategorySetup = $blogCategorySetup;
		$this->blogPostSetup = $blogPostSetup;
		$this->megaMenuSetup = $megaMenuSetup;
		parent::__construct($context);
	}
    public function execute()
    {
    	$themes = $this->themeCollection->addFieldToFilter('theme_path',array('like' => 'Codazon/%'));
    	$dir = dirname(dirname(__DIR__)).'/fixtures';
    	if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
    	foreach($themes as $theme){
    		$code = $theme->getData('theme_path');
    		$code = str_replace('Codazon/','',$code);
    		if (!file_exists($dir.'/'.$code)) {
				mkdir($dir.'/'.$code, 0777, true);
			}
			if (!file_exists($dir.'/'.$code.'/blog')) {
				mkdir($dir.'/'.$code.'/blog', 0777, true);
			}
			$this->pageSetup->export($code);
			$this->blockSetup->export($code);
			$this->slideshowSetup->export($code);
			$this->widgetSetup->export($code);
			$this->blogCategorySetup->export($code);
			$this->blogPostSetup->export($code);
			$this->megaMenuSetup->export($code);
    	}
    	echo 'done';
        exit;
    }
}
