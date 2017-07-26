<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Model\Config\Reader;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Website implements \Magento\Framework\App\Config\Scope\ReaderInterface
{
    /**
     * @var \Magento\Framework\App\Config\Initial
     */
    protected $_initialConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopePool
     */
    protected $_scopePool;

    /**
     * @var \Magento\Framework\App\Config\Scope\Converter
     */
    protected $_converter;

    /**
     * @var \Magento\Store\Model\ResourceModel\Config\Collection\ScopedFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Framework\App\Config\Initial $initialConfig
     * @param \Magento\Framework\App\Config\ScopePool $scopePool
     * @param \Magento\Framework\App\Config\Scope\Converter $converter
     * @param \Magento\Store\Model\ResourceModel\Config\Collection\ScopedFactory $collectionFactory
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     */
    public function __construct(
        \Codazon\ThemeOptions\Framework\App\Config\Initial $initialConfig,
        \Codazon\ThemeOptions\Framework\App\Config\ScopePool $scopePool,
        \Magento\Framework\App\Config\Scope\Converter $converter,
        \Codazon\ThemeOptions\Model\ResourceModel\Config\Collection\ScopedFactory $collectionFactory,
        \Magento\Store\Model\WebsiteFactory $websiteFactory
    ) {
        $this->_initialConfig = $initialConfig;
        $this->_scopePool = $scopePool;
        $this->_converter = $converter;
        $this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
    }

    /**
     * Read configuration by code
     *
     * @param string $code
     * @return array
     */
    public function read($code = null)
    {
        $config = array_replace_recursive(
            $this->_scopePool->getScope(ScopeConfigInterface::SCOPE_TYPE_DEFAULT)->getSource(),
            $this->_initialConfig->getData("websites|{$code}")
        );

        $website = $this->_websiteFactory->create();
        $website->load($code);
        $collection = $this->_collectionFactory->create(
            ['scope' => \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES, 'scopeId' => $website->getId()]
        );
        $dbWebsiteConfig = [];
        foreach ($collection as $configValue) {
        	//if($configValue->getValue()){
            	$dbWebsiteConfig[$configValue->getPath()] = $configValue->getValue();
            //}
        }
        $dbWebsiteConfig = $this->_converter->convert($dbWebsiteConfig);

        if (count($dbWebsiteConfig)) {
            $config = array_replace_recursive($config, $dbWebsiteConfig);
        }

        return $config;
    }
}
