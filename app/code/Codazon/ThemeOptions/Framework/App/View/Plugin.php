<?php
namespace Codazon\ThemeOptions\Framework\App\View;
class Plugin extends \Codazon\ThemeOptions\Framework\App\Action\Action\Plugin
{
	
    public function afterGenerateLayoutBlocks($subject, $result)
    {
		$layout = $subject->getLayout();
		$this->progress($layout);
    	return $result;
    }
}
