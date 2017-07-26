<?php

namespace Codazon\ThemeOptions\Block\Adminhtml;
use Magento\Framework\App\Area;
use Magento\Framework\View\Design\ThemeInterface;
use Magento\Theme\Model\Theme\Collection;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Themes extends \Magento\Backend\Block\Template
{
	protected $themeCollectionFactory;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\UrlInterface $url,
        \Magento\Backend\Model\Menu\Filter\IteratorFactory $iteratorFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\Model\Menu\Config $menuConfig,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Theme\Model\ResourceModel\Theme\Grid\CollectionFactory $themeCollectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_url = $url;
        $this->_iteratorFactory = $iteratorFactory;
        $this->_authSession = $authSession;
        $this->_menuConfig = $menuConfig;
        $this->_localeResolver = $localeResolver;
        $this->themeCollectionFactory = $themeCollectionFactory;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $data);
    }
    
    protected function _prepareLayout()
    {
    	$path = 'design/theme/theme_id';
        /** @var $section \Magento\Config\Model\Config\Structure\Element\Section */
        $config = $this->_objectManager->create('Magento\Config\Model\Config');
        $this->session = 'design';
        $this->website = $this->getRequest()->getParam('website');
        $this->store = $this->getRequest()->getParam('store');
        $this->code = $this->getRequest()->getParam('code');
        $config->setData([
        	'session'	=> $this->session,
        	'website'	=> $this->website,
        	'store'		=> $this->store
        ]);
        
        if($this->store)
        {
        	$collection = $this->_objectManager->create('\Magento\Theme\Model\ResourceModel\Design\Collection');
        	$collection->addFieldToFilter('store_id',$this->store);
        	$design = $collection->getFirstItem();
        	$this->currentThemeId = $design->getDesign();
        	//echo $this->currentThemeId;die;
        	if(!$this->currentThemeId)
        	{
        		$this->currentThemeId = $config->getConfigDataValue($path);
        	}
        }
        else
        {
        	$this->currentThemeId = $config->getConfigDataValue($path);
        }
    }
    
    public function getActiveThemeUrl($themeId)
    {
    	$params = array();
    	$params['theme_id'] = $themeId;
    	$params['code'] = $this->code;
    	if($this->website)
    	{
    		$params['website'] = $this->website;
    	}
    	if($this->store)
    	{
    		$params['store'] = $this->store;
    	}
    	return $this->getUrl('themeoptions/theme/save',$params);
    }
    
    public function getCustomThemeUrl($themeId)
    {
    	$params = array();
    	$params['theme_id'] = $themeId;
    	$params['code'] = $this->code;
    	$params['section'] = 'general_section';
    	return $this->getUrl('themeoptions/config/edit',$params);
    }
    
    public function getCurrentThemeId()
    {
    	return $this->currentThemeId;
    }
    
    public function getThemes()
    {
    	$config = $this->_objectManager->create('Magento\Config\Model\Config');
    	$theme_code = $this->code;//$config->getConfigDataValue($this->code.'/name');
    	//$theme_code = $params['code'] = $this->code;
    	$collection = $this->themeCollectionFactory->create();
    	$collection->addFieldToFilter('main_table.code',array('like' => '%'.$theme_code.'%'));
    	return $collection;
    }
    
    public function getCodazonThemes()
    {
    	$collection = $this->themeCollectionFactory->create();
    	$collection->addFieldToFilter('main_table.code',array('like' => 'Codazon/%'));
    	return $collection;
    }
    
    public function getThemeReviewImageUrl($theme)
    {
    	$themeI = $this->_objectManager->create('Magento\Framework\View\Design\ThemeInterface');
    	$themeI->load($theme->getThemeId());
    	return $themeI->getThemeImage()->getPreviewImageUrl();
    }
}
