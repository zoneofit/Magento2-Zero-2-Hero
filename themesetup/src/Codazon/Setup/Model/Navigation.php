<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codazon\Setup\Model;

use Zend\ServiceManager\ServiceLocatorInterface;
use Magento\Framework\App\DeploymentConfig;

class Navigation extends \Magento\Setup\Model\Navigation
{
	const CDZ_NAV_INSTALLER = 'cdzNavInstaller';
    const CDZ_NAV_UPDATER = 'cdzNavUpdater';
    
	public function __construct(ServiceLocatorInterface $serviceLocator, DeploymentConfig $deploymentConfig)
    {
		parent::__construct($serviceLocator,$deploymentConfig);
        $this->navStates = $serviceLocator->get('config')[self::CDZ_NAV_INSTALLER];
        $this->navType = self::CDZ_NAV_INSTALLER;
        $this->titles = $serviceLocator->get('config')[self::CDZ_NAV_INSTALLER . 'Titles'];
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->navType;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->navStates;
    }

    /**
     * Retrieve array of menu items
     *
     * Returns only items with 'nav' equal to TRUE
     *
     * @return array
     */
    public function getMenuItems()
    {
        return array_values(array_filter(
            $this->navStates,
            function ($value) {
                return isset($value['nav']) && (bool)$value['nav'];
            }
        ));
    }

    /**
     * Retrieve array of menu items
     *
     * Returns only items with 'main' equal to TRUE
     *
     * @return array
     */
    public function getMainItems()
    {
        $result = array_values(array_filter(
            $this->navStates,
            function ($value) {
                return isset($value['main']) && (bool)$value['main'];
            }
        ));
        return $result;
    }

    /**
     * Returns titles of the navigation pages
     *
     * @return array
     */
    public function getTitles()
    {
        return $this->titles;
    }
}
