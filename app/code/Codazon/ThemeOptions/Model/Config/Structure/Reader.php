<?php
/**
 * Backend System Configuration reader.
 * Retrieves system configuration form layout from system.xml files. Merges configuration and caches it.
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Model\Config\Structure;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\TemplateEngine\Xhtml\CompilerInterface;

/**
 * Class Reader
 */
class Reader extends \Magento\Config\Model\Config\Structure\Reader
{
	public function __construct(
        \Codazon\ThemeOptions\Framework\App\Config\FileResolver $fileResolver,
        \Magento\Config\Model\Config\Structure\Converter $converter,
        \Magento\Config\Model\Config\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Registry $registry,
        CompilerInterface $compiler,
        $fileName = 'codazon_options.xml',
        $idAttributes = [],
        $domDocumentClass = 'Magento\Framework\Config\Dom',
        $defaultScope = 'global'
    ) {
        $this->compiler = $compiler;
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $compiler,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
    
    protected function _readFiles($fileList)
    {
        /** @var \Magento\Framework\Config\Dom $configMerger */
        $configMerger = null;
        /*$code = 'Codazon_settings';
        $uri = $_SERVER['REQUEST_URI'];
        $params = explode('/',$uri);
        for($i=0; $i < count($params); $i++){
        	if($params[$i] == 'code'){
        		$code = ucwords($params[$i+1]);
        		break;
        	}
        }*/
        //echo $tmp->getRequest()->getParam('code');die;
        foreach ($fileList as $key => $content) {
        	//if (strpos($key,$code) !== false) {
		        try {
		            $content = $this->processingDocument($content);
		            if (!$configMerger) {
		                $configMerger = $this->_createConfigMerger($this->_domDocumentClass, $content);
		            } else {
		                $configMerger->merge($content);
		            }
		        } catch (\Magento\Framework\Config\Dom\ValidationException $e) {
		            throw new LocalizedException(
		                new \Magento\Framework\Phrase("Invalid XML in file %1:\n%2", [$key, $e->getMessage()])
		            );
		        }
            //}
        }

        if ($this->validationState->isValidationRequired()) {
            $errors = [];
            if ($configMerger && !$configMerger->validate($this->_schemaFile, $errors)) {
                $message = "Invalid Document \n";
                throw new LocalizedException(
                    new \Magento\Framework\Phrase($message . implode("\n", $errors))
                );
            }
        }

        $output = [];
        if ($configMerger) {
            $output = $this->_converter->convert($configMerger->getDom());
        }

        return $output;
    }
}
