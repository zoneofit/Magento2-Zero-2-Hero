<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

use Codazon\Setup\Mvc\Bootstrap\InitParamListener;

return [
    'modules' => [
        'Magento\Setup',
        'Codazon\Setup'
    ],
    'module_listener_options' => [
        'module_paths' => [
            __DIR__ . '/../../setup/src',
        ],
        'config_glob_paths' => [
            __DIR__ . '/autoload/{,*.}{global,local}.php',
        ],
    ],
    'listeners' => ['Codazon\Setup\Mvc\Bootstrap\InitParamListener'],
    'service_manager' => [
        'factories' => [
            InitParamListener::BOOTSTRAP_PARAM => 'Magento\Setup\Mvc\Bootstrap\InitParamListener',
        ],
    ],
];
