<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Codazon\Slideshow\Model\Slideshow\Media;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Image as MagentoImage;

class Config implements ConfigInterface
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var MagentoImage
     */
    protected $_processor;
     /**
     * @var \Magento\Framework\Image\Factory
     */
    protected $_imageFactory;

    protected $_fileSystem;
    
    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Image\Factory $imageFactory,
        \Magento\Framework\Filesystem $fileSystem
        )
    {
        $this->storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
        $this->_fileSystem = $fileSystem;
    }

    /**
     * Filesystem directory path of product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return 'codazon/slideshow';
    }

    /**
     * Web-based directory path of product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return 'codazon/slideshow';
    }

    /**
     * @return string
     */
    public function getBaseMediaPath()
    {
        return 'codazon/slideshow';
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'codazon/slideshow';
    }

    /**
     * Filesystem directory path of temporary product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseTmpMediaPath()
    {
        return 'tmp/' . $this->getBaseMediaPathAddition();
    }

    /**
     * @return string
     */
    public function getBaseTmpMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . 'tmp/' . $this->getBaseMediaUrlAddition();
    }

    /**
     * @param string $file
     * @return string
     */
    public function getMediaUrl($file)
    {
        return $this->getBaseMediaUrl() . '/' . $this->_prepareFile($file);
    }
    public function getMediaThumbnailUrl($resizePath, $file)
    {
        return $this->getBaseMediaUrl() . '/' . 'thumbnails' . '/' . $resizePath . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getMediaPath($file)
    {
        return $this->getBaseMediaPath() . '/' . $this->_prepareFile($file);
    }

    public function getMediaThumbnailPath($resizePath, $file)
    {
        return $this->getBaseMediaPath() . '/' . 'thumbnails' . '/' . $resizePath . '/' . $this->_prepareFile($file);   
    }
    /**
     * @param string $file
     * @return string
     */
    public function getTmpMediaUrl($file)
    {
        return $this->getBaseTmpMediaUrl() . '/' . $this->_prepareFile($file);
    }

    /**
     * Part of URL of temporary product images
     * relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getTmpMediaShortUrl($file)
    {
        return 'tmp/' . $this->getBaseMediaUrlAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * Part of URL of product images relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getMediaShortUrl($file)
    {
        return $this->getBaseMediaUrlAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getTmpMediaPath($file)
    {
        return $this->getBaseTmpMediaPath() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    protected function _prepareFile($file)
    {
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    public function resizeMediaImage($fileName, $width = null, $height = null)
    {        
        
        $dirImgpath = $this->getMediaPath($fileName);
        $checkPath = preg_replace('/\\\\/', '/', 'thumbnails');  
        $resizePath = $width . 'x' . $height;  
        $imageresized = $this->getMediaThumbnailPath($resizePath,$fileName);
        
        $mediaDirectory = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA);
        $dirImgpath = $mediaDirectory->getAbsolutePath($dirImgpath);
        $imageresized = $mediaDirectory->getAbsolutePath($imageresized);
        
        if (!file_exists($imageresized) && file_exists($dirImgpath)) {            
            $dirUrl = $this->getBaseMediaPath() . '/' . 'thumbnails' . '/' . $resizePath;
            $dirUrl = $mediaDirectory->getAbsolutePath($dirUrl);
            if (!file_exists($dirUrl))
            {                         
                mkdir($dirUrl, 0777);
            }

            $this->_processor = $this->_imageFactory->create($dirImgpath);
            $this->_processor->constrainOnly(true);
            $this->_processor->keepAspectRatio(false);
            $this->_processor->keepFrame(false);
            $this->_processor->resize($width, $height);
            $this->_processor->save($imageresized);            
        }
        
        $resizeImageUrl = $this->getMediaThumbnailUrl($resizePath,$fileName);
        return $resizeImageUrl;
    }

   

 
}
