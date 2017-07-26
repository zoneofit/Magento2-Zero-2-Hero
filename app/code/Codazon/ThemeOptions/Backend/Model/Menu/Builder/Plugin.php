<?php
namespace Codazon\ThemeOptions\Backend\Model\Menu\Builder;
class Plugin
{
	public function __construct(
        \Magento\Backend\Model\Menu\Item\Factory $menuItemFactory,
        \Magento\Config\Model\ConfigFactory $configFactory
    ) {
        $this->_itemFactory = $menuItemFactory;
        $this->_config = $configFactory->create();
    }
    
    public function getThemeId()
    {
    	$path = 'design/theme/theme_id';
        /** @var $section \Magento\Config\Model\Config\Structure\Element\Section */
        //$config = $this->_objectManager->create('Magento\Config\Model\Config');
        $this->session = 'design';
        $this->website = '';
        $this->store = '';
        $this->code = '';
        $this->_config->setData([
        	'session'	=> $this->session,
        	'website'	=> $this->website,
        	'store'		=> $this->store
        ]);

        $this->currentThemeId = $this->_config->getConfigDataValue($path);
        return $this->currentThemeId;
    }
    
    public function afterGetResult($subject, $menu)
    {	
    	$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))).'/design/frontend/Codazon';
    	$dirs = glob($path . '/*' , GLOB_ONLYDIR);
    	
    	$params = [];
		foreach($dirs as $dir){
			$code = explode('/',$dir);
			$code = end($code);
			$id = 'Codazon_ThemeOptions::'.$code.'_options';
			$params[$id] = [
				'type'	=> 'add',
				'id'	=> $id,
				'title'	=> $code.' Options',
				'module'=> 'Codazon_ThemeOptions',
				'action'=> 'themeoptions/config/edit/code/'.$code.'/section/general_section/theme_id/'.$this->getThemeId(),
				'resource'=> 'Codazon_Options::themes_options'
			];
    	}
    	$parent = $menu->get('Codazon_Options::themes_options');
    	foreach($params as $id => $param){
    		$item = $this->_itemFactory->create($param);
    		$parent->getChildren()->add($item,null,10);
    	}
    	
    	return $menu;
    }
}
