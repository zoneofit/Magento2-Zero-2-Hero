<?php
namespace Codazon\ProductLabel\Setup;

use Codazon\ProductLabel\Model\LabelFactory;
use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
class LabelSetup  extends EavSetup{
	private $labelFactory;
	public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory,
		\Codazon\ProductLabel\Model\ProductLabelFactory $labelFactory
    ) {
		$this->labelFactory = $labelFactory;
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }
	public function createLabel($data = [])
    {
        return $this->labelFactory->create($data);
    }
	public function getDefaultEntities()
    {
		return [
            'codazon_product_label_entity' => [
				'entity_model' => 'Codazon\ProductLabel\Model\ResourceModel\ProductLabelEntity',
				'attribute_model' => 'Codazon\ProductLabel\Model\ResourceModel\Eav\Attribute',
                'table' => 'codazon_product_label_entity',
                //'additional_attribute_table' => 'codazon_product_label_eav_attribute',
                'entity_attribute_collection' => 'Codazon\ProductLabel\Model\ResourceModel\ProductLabel\Attribute\Collection',
				 'attributes' => [
					'content' => [
                        'type' => 'text',
						'label' => 'Label Content',
                        'required' => true,
                        'sort_order' => 3,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Label Information',
                    ],
					'custom_class' => [
                        'type' => 'varchar',
                        'label' => 'Custom Class',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 4,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Label Information',
                    ],
					'custom_css' => [
                        'type' => 'text',
						'label' => 'Custom CSS',
                        'sort_order' => 5,
						'input' => 'textarea',
                        'required' => false,
						'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Label Information',
                    ],
				 	'label_image' => [
                        'type' => 'varchar',
                        'label' => 'Label Image',
                        'input' => 'hidden',
                        'required' => false,
                        'sort_order' => 5,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Label Information',
                    ],
					'label_background' => [
                        'type' => 'varchar',
                        'label' => 'Image',
                        'input' => 'hidden',
                        'required' => false,
                        'sort_order' => 5,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'Label Information',
                    ]
				 ]
			]
		];	
		
	}
}

