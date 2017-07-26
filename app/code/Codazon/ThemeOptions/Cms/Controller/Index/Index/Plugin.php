<?php
namespace Codazon\ThemeOptions\Cms\Controller\Index\Index;
class Plugin
{
	public function __construct(
        \Magento\Cms\Controller\Index\Index $action,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Cms\Helper\Page $helperPage
    ) {
        $this->_action = $action;
        $this->_scopeConfig = $scopeConfig;
        $this->_helperPage = $helperPage;
        $this->resultForwardFactory = $resultForwardFactory;
    }
    
    public function aroundExecute($subject, $procede)
    {
    	$pageId = $this->_scopeConfig->getValue(
            \Magento\Cms\Helper\Page::XML_PATH_HOME_PAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );

        $resultPage = $this->_helperPage->prepareResultPage($subject, $pageId);
        if (!$resultPage) {
            /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('defaultIndex');
            return $resultForward;
        }
        return $resultPage;
    	//$result = $procede($coreRoute);
    	
		
    	//return $result;
    }
}
