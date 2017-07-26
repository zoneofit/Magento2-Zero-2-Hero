<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\Setup\Mvc\Bootstrap;

use Magento\Framework\App\Bootstrap as AppBootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem;
use Magento\Framework\Shell\ComplexParameter;
use Zend\Console\Request;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;

/**
 * A listener that injects relevant Magento initialization parameters and initializes Magento\Filesystem component.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InitParamListener extends \Magento\Setup\Mvc\Bootstrap\InitParamListener
{

    public function onBootstrap(MvcEvent $e)
    {
        /** @var Application $application */
        $application = $e->getApplication();
        $initParams = $application->getServiceManager()->get(self::BOOTSTRAP_PARAM);
        $directoryList = $this->createDirectoryList($initParams);
        $serviceManager = $application->getServiceManager();
        $serviceManager->setService('Magento\Framework\App\Filesystem\DirectoryList', $directoryList);
        $serviceManager->setService('Magento\Framework\Filesystem', $this->createFilesystem($directoryList));
        
		
		/** @var \Magento\Setup\Model\ObjectManagerProvider $objectManagerProvider */
        $objectManagerProvider = $serviceManager->get('Magento\Setup\Model\ObjectManagerProvider');
        /** @var \Magento\Framework\ObjectManagerInterface $objectManager */
        $objectManager = $objectManagerProvider->get();
        $cfReader = $objectManager->get('Magento\Framework\App\DeploymentConfig\Reader');
        
        //set install to null to redirect to landing-install
        $deConfig = new \Magento\Framework\App\DeploymentConfig($cfReader,['install'=>'']);
        $serviceManager->setService('Magento\Framework\App\DeploymentConfig', $deConfig);
    }
}
