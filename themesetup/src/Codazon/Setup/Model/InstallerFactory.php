<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\Setup\Model;

use Zend\ServiceManager\ServiceLocatorInterface;
use Magento\Setup\Module\ResourceFactory;
use Magento\Framework\App\ErrorHandler;
use Magento\Framework\App\State\CleanupFiles;
use Magento\Framework\Setup\LoggerInterface;

class InstallerFactory extends \Magento\Setup\Model\InstallerFactory
{
	public function __construct(ServiceLocatorInterface $serviceLocator, ResourceFactory $resourceFactory)
    {
        $this->serviceLocator = $serviceLocator;
        $this->resourceFactory = $resourceFactory;
        // For Setup Wizard we are using our customized error handler
        $handler = new ErrorHandler();
        set_error_handler([$handler, 'handler']);
        
        $this->factory = new \Magento\Setup\Model\InstallerFactory($serviceLocator, $resourceFactory);
    }
    
    public function create(LoggerInterface $log)
    {
        //return $this->factory->create($log);
        return new Installer(
            $this->serviceLocator->get('Magento\Framework\Setup\FilePermissions'),
            $this->serviceLocator->get('Magento\Framework\App\DeploymentConfig\Writer'),
            $this->serviceLocator->get('Magento\Framework\App\DeploymentConfig\Reader'),
            $this->serviceLocator->get('Magento\Framework\App\DeploymentConfig'),
            $this->serviceLocator->get('Magento\Framework\Module\ModuleList'),
            $this->serviceLocator->get('Magento\Framework\Module\ModuleList\Loader'),
            $this->serviceLocator->get('Magento\Setup\Model\AdminAccountFactory'),
            $log,
            $this->serviceLocator->get('Magento\Setup\Module\ConnectionFactory'),
            $this->serviceLocator->get('Magento\Framework\App\MaintenanceMode'),
            $this->serviceLocator->get('Magento\Framework\Filesystem'),
            $this->serviceLocator->get('Magento\Setup\Model\ObjectManagerProvider'),
            new \Magento\Framework\Model\ResourceModel\Db\Context(
                $this->getResource(),
                $this->serviceLocator->get('Magento\Framework\Model\ResourceModel\Db\TransactionManager'),
                $this->serviceLocator->get('Magento\Framework\Model\ResourceModel\Db\ObjectRelationProcessor')
            ),
            $this->serviceLocator->get('Magento\Setup\Model\ConfigModel'),
            $this->serviceLocator->get('Magento\Framework\App\State\CleanupFiles'),
            $this->serviceLocator->get('Magento\Setup\Validator\DbValidator'),
            $this->serviceLocator->get('Magento\Setup\Module\SetupFactory'),
            $this->serviceLocator->get('Magento\Setup\Module\DataSetupFactory'),
            $this->serviceLocator->get('Magento\Framework\Setup\SampleData\State'),
            new \Magento\Framework\Component\ComponentRegistrar(),
            $this->serviceLocator->get('Magento\Setup\Model\PhpReadinessCheck')
        );
    }
    
    private function getResource()
    {
        $deploymentConfig = $this->serviceLocator->get('Magento\Framework\App\DeploymentConfig');
        return $this->resourceFactory->create($deploymentConfig);
    }
}
