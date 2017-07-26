<?php
namespace Codazon\QuickShop\Block\Product\Renderer;
class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
	const SWATCH_RENDERER_TEMPLATE = 'Codazon_QuickShop::product/view/renderer.phtml';
	const CONFIGURABLE_RENDERER_TEMPLATE = 'Magento_ConfigurableProduct::product/view/type/options/configurable.phtml';
	protected function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute ?
            self::SWATCH_RENDERER_TEMPLATE : self::CONFIGURABLE_RENDERER_TEMPLATE;
    }
}