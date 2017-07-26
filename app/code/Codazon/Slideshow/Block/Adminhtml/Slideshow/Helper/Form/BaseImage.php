<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product form image field helper
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Codazon\Slideshow\Block\Adminhtml\Slideshow\Helper\Form;

/**
 * Class BaseImage
 */
class BaseImage extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * Element output template
     */
    const ELEMENT_OUTPUT_TEMPLATE = 'Codazon_Slideshow::slideshow/edit/form/renderer/content.phtml';

    /**
     * Model Url instance
     *
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $url;

    /**
     * @var \Codazon\Slideshow\Helper\Data
     */
    protected $sliderHelperData;

    /**
     * @var \Magento\Framework\File\Size
     */
    protected $fileConfig;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Backend\Model\UrlFactory $backendUrlFactory
     * @param \Codazon\Slideshow\Helper\Data $sliderHelperData
     * @param \Magento\Framework\File\Size $fileConfig
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Backend\Model\UrlFactory $backendUrlFactory,
        \Codazon\Slideshow\Helper\Data $sliderHelperData,
        \Magento\Framework\File\Size $fileConfig,
        \Magento\Framework\View\LayoutInterface $layout,
        array $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);

        $this->assetRepo = $assetRepo;
        $this->url = $backendUrlFactory->create();
        $this->sliderHelperData = $sliderHelperData;
        $this->fileConfig = $fileConfig;
        $this->maxFileSize = $this->getFileMaxSize();
        $this->layout = $layout;
    }

    /**
     * Get label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Images');
    }

    /**
     * Return element html code
     *
     * @return string
     */
    public function getElementHtml()
    {
        $block = $this->createElementHtmlOutputBlock();
        $this->assignBlockVariables($block);
        return $block->toHtml();
    }

    /**
     * @param \Magento\Framework\View\Element\Template $block
     * @return \Magento\Framework\View\Element\Template
     */
    public function assignBlockVariables(\Magento\Framework\View\Element\Template $block)
    {
        $block->assign([
            'htmlId' => $this->_escaper->escapeHtml($this->getHtmlId()),
            'fileMaxSize' => $this->maxFileSize,
            'uploadUrl' => $this->_escaper->escapeHtml($this->_getUploadUrl()),
            'spacerImage' => $this->assetRepo->getUrl('images/spacer.gif'),
            'imagePlaceholderText' => __('Click here or drag and drop to add images.'),
            'deleteImageText' => __('Delete image'),
            'makeBaseText' => __('Make Base'),
            'hiddenText' => __('Hidden'),
            'imageManagementText' => __('Images'),
        ]);

        return $block;
    }


    /**
     * @return \Magento\Framework\View\Element\Template
     */
    public function createElementHtmlOutputBlock()
    {
        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $this->layout->createBlock(
            'Magento\Framework\View\Element\Template',
            'slideshow.details.form.base.image.element'
        );
        $block->setTemplate(self::ELEMENT_OUTPUT_TEMPLATE);

        return $block;
    }

    /**
     * Get url to upload files
     *
     * @return string
     */
    protected function _getUploadUrl()
    {
        return $this->url->getUrl('slideshow/slideshow_gallery/upload');
    }

    /**
     * Get maximum file size to upload in bytes
     *
     * @return int
     */
    protected function getFileMaxSize()
    {
        return $this->fileConfig->getMaxFileSize();
    }
}
