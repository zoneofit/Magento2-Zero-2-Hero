<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Setup;

use Magento\Framework\Setup;
use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\RequestInterface;
use Magento\Theme\Model\Theme\Registration as ThemeRegistration;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\State as AppState;
use Magento\Theme\Model\Theme\Collection as ThemeCollection;
use Magento\Theme\Model\ResourceModel\Theme\Collection as ThemeLoader;
use Magento\Framework\Config\Theme;

class InstallData implements Setup\InstallDataInterface
{
    /**
     * @var Setup\SampleData\Executor
     */
    protected $executor;

    /**
     * @var Installer
     */
    protected $installer;
    
    public function __construct(
        Setup\SampleData\Executor $executor, 
        Installer $installer,
        ThemeRegistration $themeRegistration,
        ThemeCollection $themeCollection,
        ThemeLoader $themeLoader,
        LoggerInterface $logger,
        AppState $appState
    ) {
        $this->executor = $executor;
        $this->installer = $installer;
        $this->themeRegistration = $themeRegistration;
        $this->themeCollection = $themeCollection;
        $this->themeLoader = $themeLoader;
        $this->logger = $logger;
        $this->appState = $appState;
    }

    /**
     * {@inheritdoc}
     */
    public function install(Setup\ModuleDataSetupInterface $setup, Setup\ModuleContextInterface $moduleContext)
    {
    	//$setup->getEventManager()->dispatch('theme_registration_from_filesystem'); //old version
    	try {
            if ($this->appState->getMode() != AppState::MODE_PRODUCTION) {
                $this->themeRegistration->register();
                $this->updateThemeData();
            }
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
        }
        $this->executor->exec($this->installer);
    }
    
    protected function updateThemeData()
    {
        $themesFromConfig = $this->themeCollection->loadData();
        /** @var \Magento\Theme\Model\Theme $themeFromConfig */
        foreach ($themesFromConfig as $themeFromConfig) {
            /** @var \Magento\Theme\Model\Theme $themeFromDb */
            $themeFromDb = $this->themeLoader->getThemeByFullPath(
                $themeFromConfig->getArea()
                . Theme::THEME_PATH_SEPARATOR
                . $themeFromConfig->getThemePath()
            );

            if ($themeFromConfig->getParentTheme()) {
                $parentThemeFromDb = $this->themeLoader->getThemeByFullPath(
                    $themeFromConfig->getParentTheme()->getFullPath()
                );
                $themeFromDb->setParentId($parentThemeFromDb->getId());
            }

            $themeFromDb->setThemeTitle($themeFromConfig->getThemeTitle());
            $themeFromDb->save();
        }
    }
}
