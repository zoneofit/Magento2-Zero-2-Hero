<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

$base = basename($_SERVER['SCRIPT_FILENAME']);

return [
    'cdzNavInstallerTitles' => [
        'install'    => 'Welcome Theme Installer',
    ],
    'cdzNavInstaller' => [
        [
            'id'          => 'root',
            'step'        => 0,
            'views'       => ['root' => []],
        ],
        [
            'id'          => 'root.license',
            'url'         => 'license',
            'templateUrl' => "$base/license",
            'title'       => 'License',
            'main'        => true,
            'nav'         => false,
            'order'       => -1,
            'type'        => 'install'
        ],
        [
            'id'          => 'root.landing-install',
            'url'         => 'landing-install',
            'templateUrl' => "$base/landing-installer",
            'title'       => 'Installation',
            'controller'  => 'landingController',
            'main'        => true,
            'default'     => true,
            'order'       => 0,
            'type'        => 'install'
        ],
        [
            'id'          => 'root.readiness-check-install',
            'url'         => 'readiness-check-install',
            'templateUrl' => "{$base}/readiness-check-installer",
            'title'       => "Readiness \n Check",
            'header'      => 'Step 1: Readiness Check',
            'nav'         => true,
            'order'       => 1,
            'type'        => 'install'
        ],
        [
            'id'          => 'root.readiness-check-install.progress',
            'url'         => 'readiness-check-install/progress',
            'templateUrl' => "{$base}/readiness-check-installer/progress",
            'title'       => 'Readiness Check',
            'header'      => 'Step 1: Readiness Check',
            'controller'  => 'readinessCheckController',
            'nav'         => false,
            'order'       => 2,
            'type'        => 'install'
        ],
        [
            'id'          => 'root.install',
            'url'         => 'install',
            'templateUrl' => "{$base}/install",
            'title'       => 'Install',
            'header'      => 'Step 2: Install',
            'controller'  => 'installController',
            'nav'         => true,
            'order'       => 3,
            'type'        => 'install'
        ],
        [
            'id'          => 'root.success',
            'url'         => 'success',
            'templateUrl' => "{$base}/success",
            'title'       => 'Success',
            'controller'  => 'successController',
            'main'        => true,
            'order'       => 4,
            'type'        => 'install'
        ],
    ],
];
