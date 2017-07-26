<?php
namespace Codazon\ThemeOptions\Framework\View\Asset\File\FallbackContextFactory;
class Plugin
{
	public function __construct(
        \Magento\Store\Model\StoreManager $storeManager
    ) {
        $this->store = $storeManager->getStore();
    }
    public function beforeCreate($subject, $data)
    {
    	$storeCode = $this->store->getCode();
    	//$locale = $this->store->getConfig('general/locale/code');
    	$data['localeCode'] .= '/'.$storeCode;
    	return [$data];
    }
}
