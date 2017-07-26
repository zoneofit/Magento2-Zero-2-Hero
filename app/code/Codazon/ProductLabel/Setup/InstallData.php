<?php
namespace Codazon\ProductLabel\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    private $labelSetupFactory;
	public function __construct(LabelSetupFactory $categorySetupFactory)
    {
        $this->labelSetupFactory = $categorySetupFactory;
    }
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
		$labelSetup = $this->labelSetupFactory->create(['setup' => $setup]);
        $labelSetup->installEntities();	
		
	}
}