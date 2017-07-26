<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Setup;

use Magento\Framework\Setup;
use Magento\Store\Model\Store;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * @var \Magento\CatalogSampleData\Model\Category
     */
    private $category;

    /**
     * Setup class for css
     *
     * @var \Magento\ThemeSampleData\Model\Css
     */
    private $css;

    /**
     * @var \Codazon\ThemeOptions\Setup\Model\Page
     */
    private $page;

    /**
     * @var \Codazon\ThemeOptions\Setup\Model\Block
     */
    private $block;

    /**
     * @param \Magento\CatalogSampleData\Model\Category $category
     * @param \Magento\ThemeSampleData\Model\Css $css
     * @param \Codazon\ThemeOptions\Setup\Model\Page $page
     * @param \Codazon\ThemeOptions\Setup\Model\Block $block
     */
    public function __construct(
        \Codazon\ThemeOptions\Setup\Model\Page $page,
        \Codazon\ThemeOptions\Setup\Model\Block $block,
        \Codazon\ThemeOptions\Setup\Model\Widget $widget,
        \Codazon\ThemeOptions\Setup\Model\Slideshow $slideshow,
        \Codazon\ThemeOptions\Setup\Model\Blog\Category $blogCategory,
        \Codazon\ThemeOptions\Setup\Model\Blog\Post $blogPost,
        \Codazon\ThemeOptions\Setup\Model\MegaMenu $megaMenu,
        \Magento\Theme\Model\Config $config,
        \Codazon\ThemeOptions\Framework\App\Config $themeConfig,
        \Magento\Theme\Model\Theme $theme,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $collectionFactory
    ) {
        $this->pageSetup = $page;
        $this->blockSetup = $block;
        $this->slideshowSetup = $slideshow;
        $this->widgetSetup = $widget;
        $this->blogCategorySetup = $blogCategory;
        $this->blogPostSetup = $blogPost;
        $this->megaMenuSetup = $megaMenu;
		$this->config = $config;
        $this->collectionFactory = $collectionFactory;
        $this->configWriter = $configWriter;
        $this->themeConfig = $themeConfig;
        $this->theme = $theme;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $themes = $this->collectionFactory->create()->addFieldToFilter('theme_path',array('like' => 'Codazon/%'));

    	foreach($themes as $theme){
    		$code = $theme->getData('theme_path');
    		$code = str_replace('Codazon/','',$code);
			$this->pageSetup->install($code);
			$this->blockSetup->install($code);
			$this->slideshowSetup->install($code);
			$this->widgetSetup->install($code);
			$this->blogCategorySetup->install($code);
			$this->blogPostSetup->install($code);
			$this->megaMenuSetup->install($code);
    	}
        
        $this->assignTheme();
    }
    
    protected function assignTheme()
    {
        $themes = $this->collectionFactory->create()->loadRegisteredThemes();
        /** @var \Magento\Theme\Model\Theme $theme */
        $query = 'Codazon';
        foreach ($themes as $theme) {
        	if(substr($theme->getCode(), 0, strlen($query)) === $query)
            {
                $this->config->assignToStore(
                    $theme,
                    [Store::DEFAULT_STORE_ID],
                    ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );
                $_SESSION["theme_id"] = $theme->getThemeId();
                $cmsHomePage = $this->themeConfig->getValue('cms_home_page', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $this->configWriter->save('web/default/cms_home_page', $cmsHomePage, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, Store::DEFAULT_STORE_ID);
                break;
            }
        }
    }
}
