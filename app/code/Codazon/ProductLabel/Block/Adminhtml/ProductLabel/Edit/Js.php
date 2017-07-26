<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * description
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Codazon\ProductLabel\Block\Adminhtml\ProductLabel\Edit;
class Js extends \Magento\Backend\Block\Template
{
	protected $assetRepo;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = []
	)
    {
		$this->assetRepo = $context->getAssetRepository();
        parent::__construct($context, $data);
    }
	public function getAssetRepo(){
		return $this->assetRepo;
	}
}
