<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ProductLabel\Model\Wysiwyg;
use Magento\Framework\Filesystem;
/**
 * Wysiwyg Config for Editor HTML Element
 */
class Config extends \Magento\Cms\Model\Wysiwyg\Config
{
    const WYSIWYG_ENABLED = 'enabled';
    const WYSIWYG_STATUS_CONFIG_PATH = 'cms/wysiwyg/enabled';
    const WYSIWYG_SKIN_IMAGE_PLACEHOLDER_ID = 'Magento_Cms::images/wysiwyg_skin_image.png';
    const WYSIWYG_HIDDEN = 'hidden';
    const WYSIWYG_DISABLED = 'disabled';
    const IMAGE_DIRECTORY = 'wysiwyg';

    public function getConfig($data = [])
    {
        $config = new \Magento\Framework\DataObject();

        $config->setData(
            [
                'enabled' => $this->isEnabled(),
                'hidden' => $this->isHidden(),
                'use_container' => false,
                'add_variables' => true,
                'add_widgets' => false,
                'no_display' => false,
                'encode_directives' => true,
                'directives_url' => $this->_backendUrl->getUrl('cms/wysiwyg/directive'),
                'popup_css' => $this->_assetRepo->getUrl(
                    'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/dialog.css'
                ),
                'content_css' => $this->_assetRepo->getUrl(
                    'mage/adminhtml/wysiwyg/tiny_mce/themes/advanced/skins/default/content.css'
                ),
                'width' => '100%',
                'plugins' => [],
            ]
        );

        $config->setData('directives_url_quoted', preg_quote($config->getData('directives_url')));

        if ($this->_authorization->isAllowed('Magento_Cms::media_gallery')) {
            $config->addData(
                [
                    'add_images' => true,
                    'files_browser_window_url' => $this->_backendUrl->getUrl('cms/wysiwyg_images/index'),
                    'files_browser_window_width' => $this->_windowSize['width'],
                    'files_browser_window_height' => $this->_windowSize['height'],
                ]
            );
        }

        if (is_array($data)) {
            $config->addData($data);
        }

        if ($config->getData('add_variables')) {
            $settings = $this->_variableConfig->getWysiwygPluginSettings($config);
            $config->addData($settings);
        }

        if ($config->getData('add_widgets')) {
            $settings = $this->_widgetConfig->getPluginSettings($config);
            $config->addData($settings);
        }

        return $config;
    }
}
