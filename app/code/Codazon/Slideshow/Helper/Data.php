<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Helper;

/**
 * Catalog data helper
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_config;
    protected $_filterProvider;
   public function __construct(
        \Magento\Framework\App\Helper\Context $context,       
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Codazon\Slideshow\Model\Slideshow\Media\Config $config
    ) {        
        parent::__construct($context);
        $this->_assetRepo = $assetRepo;
        $this->viewConfig = $viewConfig;
        $this->_config = $config;
        $this->_filterProvider = $filterProvider;
    }

    public function decodeJson($obj)
    {
        return json_decode($obj,true);
    }

    public function getImagePath($imagePath)
    {
        return $this->_config->getMediaUrl($imagePath);
    }

    public function resizeMediaImage($imageFile,$width,$height)
    {
        return $this->_config->resizeMediaImage($imageFile,$width,$height);
    }    

    public function filterTemplate($content)
    {

        $html = $this->_filterProvider->getBlockFilter()->filter($content);        
        return $html;
    }
}
