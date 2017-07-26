<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\Setup;

use Magento\Framework\App\Response\HeaderProvider\XssProtection;
use Magento\Setup\Mvc\View\Http\InjectTemplateListener;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $result = array_merge_recursive(
            include __DIR__ . '/../../../config/module.config.php',
            //include __DIR__ . '/../../../config/router.config.php',
            include __DIR__ . '/../../../config/di.config.php',
            include __DIR__ . '/../../../config/states.install.config.php'
            //include __DIR__ . '/../../../config/states.update.config.php',
            //include __DIR__ . '/../../../config/states.home.config.php',
            //include __DIR__ . '/../../../config/states.extensionManager.config.php',
            //include __DIR__ . '/../../../config/states.upgrade.config.php',
            //include __DIR__ . '/../../../config/states.uninstall.config.php',
            //include __DIR__ . '/../../../config/states.enable.config.php',
            //include __DIR__ . '/../../../config/states.disable.config.php',
            //include __DIR__ . '/../../../config/languages.config.php',
            //include __DIR__ . '/../../../config/marketplace.config.php'
        );
        return $result;
    }
}
