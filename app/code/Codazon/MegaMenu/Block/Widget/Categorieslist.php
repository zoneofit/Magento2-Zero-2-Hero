<?php
namespace Codazon\MegaMenu\Block\Widget;

class Categorieslist extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	protected function _toHtml(){		
		$parentId = (int)str_replace('category/','',$this->getData('parent_id'));
		$categoriesTree = $this->getLayout()->createBlock('Codazon\MegaMenu\Block\Widget\CategoriesTree');
		$categoriesTree->setData('parent_id',$parentId);
		if($this->getData('item_count')){
			$categoriesTree->setData('item_count',$this->getData('item_count'));
		}
		return '<ul>'.$categoriesTree->getHtml('', 'submenu', 0).'</ul>';	
	}
}