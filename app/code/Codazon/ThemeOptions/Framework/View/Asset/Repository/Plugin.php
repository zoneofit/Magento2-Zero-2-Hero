<?php
namespace Codazon\ThemeOptions\Framework\View\Asset\Repository;
class Plugin
{
	public function __construct(
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Module\ModuleList $moduleList
    ) {
        $this->store = $storeManager->getStore();
        $this->moduleList = $moduleList;
    }
    public function beforeCreateAsset($subject, $fileId, array $params = [])
    {
    	$storeCode = $this->store->getCode();
    	$fileId = str_replace($storeCode.'/','',$fileId);
    	
    	//=====
    	$parts = explode('/', $fileId, 2);
        if (count($parts) >= 2 && $this->moduleList->has($parts[0])) {
            $params['module'] = $parts[0];
            $fileId = $parts[1];
        }
        //=======
        //$locale = $this->store->getConfig('general/locale/code');
        //$params['locale'] = $locale.'/'.$storeCode;
    	return [$fileId, $params];
    }
}
