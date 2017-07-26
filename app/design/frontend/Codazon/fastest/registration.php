<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
use \Magento\Framework\Component\ComponentRegistrar;
$dirs = array_filter(glob(__DIR__.'/*'), 'is_dir');
$parentHome = 'fashion';
$parentHomeDir = __DIR__.'/'.$parentHome;
if(($key = array_search($parentHomeDir, $dirs)) !== false) {
    unset($dirs[$key]);
}
array_unshift($dirs , $parentHomeDir);

$tmp = explode('/',__DIR__);
if(count($tmp) == 1){
	$tmp = explode("\\",__DIR__);
}
$package = end($tmp);
foreach($dirs as $dir){
	$tmp2 = explode('/',$dir);
	$home = end($tmp2);
	ComponentRegistrar::register(
		\Magento\Framework\Component\ComponentRegistrar::THEME,
		'frontend/Codazon/'.$package.'_'.$home,
		$dir
	);
}

