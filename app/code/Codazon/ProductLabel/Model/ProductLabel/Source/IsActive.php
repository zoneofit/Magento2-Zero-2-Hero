<?php
namespace Codazon\ProductLabel\Model\ProductLabel\Source;
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
	protected $productLabel;
	public function __construct(\Codazon\ProductLabel\Model\ProductLabel $productLabel)
    {
        $this->productLabel = $productLabel;
    }
	public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->productLabel->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key
            ];
        }
        return $options;
    }
}