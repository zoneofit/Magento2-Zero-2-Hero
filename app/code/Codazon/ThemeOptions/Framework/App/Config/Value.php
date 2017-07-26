<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Framework\App\Config;

/**
 * Config data model
 *
 * @method \Magento\Framework\Model\ResourceModel\Db\AbstractDb getResource()
 * @method string getScope()
 * @method \Magento\Framework\App\Config\ValueInterface setScope(string $value)
 * @method int getScopeId()
 * @method \Magento\Framework\App\Config\ValueInterface setScopeId(int $value)
 * @method string getPath()
 * @method \Magento\Framework\App\Config\ValueInterface setPath(string $value)
 * @method \Magento\Framework\App\Config\ValueInterface setValue(string $value)
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class Value extends \Magento\Framework\App\Config\Value
{
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_config = $config;
        $this->cacheTypeList = $cacheTypeList;
        $resource = $objectManager->get('\Codazon\ThemeOptions\Model\ResourceModel\Config\Data');
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }
}
