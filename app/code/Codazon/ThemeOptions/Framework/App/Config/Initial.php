<?php
/**
 * Initial configuration data container. Provides interface for reading initial config values
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Framework\App\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Initial
{
    /**
     * Cache identifier used to store initial config
     */
    const CACHE_ID = 'codazon_initial_config';

    /**
     * Config data
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Config metadata
     *
     * @var array
     */
    protected $_metadata = [];

    /**
     * @param \Magento\Framework\App\Config\Initial\Reader $reader
     * @param \Magento\Framework\App\Cache\Type\Config $cache
     */
    public function __construct(
        \Codazon\ThemeOptions\Framework\App\Config\Initial\Reader $reader,
        \Magento\Theme\Model\Design $design,
        \Magento\Framework\App\Config $scopeConfig,
        \Magento\Framework\App\Cache\Type\Config $cache,
        \Magento\Theme\Model\ResourceModel\Theme\Collection $themeCollection
    ) {
    	//============== this use for generate static file =============
    	if(isset($_REQUEST['resource'])){
			$uri = $_REQUEST['resource'];
			$data = explode('/',$uri);
			$themePath = $data[1].'/'.$data[2];
			$themeCollection->addFieldToFilter('theme_path',$themePath);
			$theme = $themeCollection->getFirstItem();
			if($theme->getId()){
				$this->themeId = $theme->getId();
			}
		}
		//==============

		if(!isset($this->themeId)){
			$this->themeId = $scopeConfig->getValue('design/theme/theme_id',\Magento\Store\Model\ScopeInterface::SCOPE_STORES);
			$this->design = $design;
		    if($design->getDesign()){
				$this->themeId = $design->getDesign();
			}			
		}
		
		
        $data = $cache->load(self::CACHE_ID.'_theme_'.$this->themeId);
        
        //$data = $cache->load(self::CACHE_ID);
        if (!$data) {
            $data = $reader->read();
            $cache->save(serialize($data), self::CACHE_ID);
        } else {
            $data = unserialize($data);
        }
        if($data){
		    $this->_data = $data['data'];
		    $this->_metadata = $data['metadata'];
        }
    }

    /**
     * Get initial data by given scope
     *
     * @param string $scope Format is scope type and scope code separated by pipe: e.g. "type|code"
     * @return array
     */
    public function getData($scope)
    {
        list($scopeType, $scopeCode) = array_pad(explode('|', $scope), 2, null);

        if (ScopeConfigInterface::SCOPE_TYPE_DEFAULT == $scopeType) {
            return isset($this->_data[$scopeType]) ? $this->_data[$scopeType] : [];
        } elseif ($scopeCode) {
            return isset($this->_data[$scopeType][$scopeCode]) ? $this->_data[$scopeType][$scopeCode] : [];
        }
        return [];
    }

    /**
     * Get configuration metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }
}
