<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Codazon\Slideshow\Block\Widget;

/**
 * Codazon slideshow content 
 */
class Slideshow extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    

    /**
     * Slideshow factory
     *
     * @var \Codazon\Slideshow\Model\SlideshowFactory
     */
    protected $_slideshowFactory;

    protected $_objectSlideshow;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context    
     * @param \Codazon\Slideshow\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,     
        \Codazon\Slideshow\Model\SlideshowFactory $slideshowFactory,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);     
        $this->httpContext = $httpContext;   
        $this->_slideshowFactory = $slideshowFactory;        
        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => ['CDZ_SLIDESHOW',
        ], ]);        
    }

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function getSlideshow()
    {
        $slideshowId = $this->getSlideshowId();            
        
        if ($slideshowId) {                        
            $slideshow = $this->_slideshowFactory->create();
            $slideshow->load($slideshowId,'identifier');            
            if ($slideshow->isActive()) {
                return $slideshow;
            }            
        }          
    }
    protected function _beforeToHtml()
    {
        $this->setSlideshowData($this->getSlideshow());
        return parent::_beforeToHtml();
    }
    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $slideshow = serialize($this->getData());
            
        return [
            'CODAZON_SLIDESHOW',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),                    
            $slideshow
        ];
    }
    public function getTemplate()
    {        
        $template = "slideshow.phtml";    
        return $template;        
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Codazon\Slideshow\Model\Block::CACHE_TAG . '_' . $this->getSlideshowId()];
    }
      
}
