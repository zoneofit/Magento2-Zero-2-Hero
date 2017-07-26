<?php
namespace Codazon\MegaMenu\Model\Megamenu\Source;
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
	protected $megamenu;
	public function __construct(\Codazon\MegaMenu\Model\Megamenu $megamenu)
    {
        $this->megamenu = $megamenu;
    }
	public function toOptionArray()
	{
		$options[] = ['label' => '', 'value' => ''];
		$availableOptions = $this->megamenu->getAvailableStatuses();
		foreach ($availableOptions as $key => $value) {
			$options[] = [
				'label' => $value,
				'value' => $key,
			];
		}
		return $options;
	}
}