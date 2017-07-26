<?php
namespace Codazon\QuickShop\Block;
class QuickShopLink extends \Magento\Framework\View\Element\Template
{
	protected $_targetId;
	protected $_htmlClass;
	protected $_label;
	
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Codazon\QuickShop\Helper\Data $devToolHelper,
        array $data = [] ){
		
		parent::__construct(
            $context,
            $data
        );
    }
	/*public function _construct()
    {
        parent::_construct();
        $this->setTemplate('quickshop.link.phtml');
    }*/

	protected function _toHtml(){
		$this->_htmlClass = $this->getHtmlClass();
		$this->_targetId = $this->getTargetId();
		$this->_label = $this->getLabel();
		
		//return "<div class=\"{$this->_htmlClass} qs-title \"><a href=\"#{$this->_targetId}\">{$this->_label}</a></div>";
	}
}