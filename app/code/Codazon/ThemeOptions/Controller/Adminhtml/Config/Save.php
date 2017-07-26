<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Controller\Adminhtml\Config;

class Save extends \Magento\Backend\App\Action
{

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Codazon\ThemeOptions\Model\Config\Structure $configStructure,
        \Codazon\ThemeOptions\Model\ConfigFactory $configFactory,
        \Magento\Framework\Config\CacheInterface $cache,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Codazon\ThemeOptions\Framework\App\Config $themeConfig
    ) {
        parent::__construct($context);
        $this->themeConfig = $themeConfig;
        $this->_objectManager = $context->getObjectManager();
        $this->_cache = $cache;
        $this->string = $string;   
        $this->_configFactory = $configFactory;     
        
    }

    protected function _processNestedGroups($group)
    {
        $data = [];

        if (isset($group['fields']) && is_array($group['fields'])) {
            foreach ($group['fields'] as $fieldName => $field) {
                if (!empty($field['value'])) {
                    $data['fields'][$fieldName] = ['value' => $field['value']];
                }
            }
        }

        if (isset($group['groups']) && is_array($group['groups'])) {
            foreach ($group['groups'] as $groupName => $groupData) {
                $nestedGroup = $this->_processNestedGroups($groupData);
                if (!empty($nestedGroup)) {
                    $data['groups'][$groupName] = $nestedGroup;
                }
            }
        }

        return $data;
    }
    
    protected function _getGroupsForSave()
    {
        $groups = $this->getRequest()->getPost('groups');
        $files = $this->getRequest()->getFiles('groups');

        if ($files && is_array($files)) {
            /**
             * Carefully merge $_FILES and $_POST information
             * None of '+=' or 'array_merge_recursive' can do this correct
             */
            foreach ($files as $groupName => $group) {
                $data = $this->_processNestedGroups($group);
                if (!empty($data)) {
                    if (!empty($groups[$groupName])) {
                        $groups[$groupName] = array_merge_recursive((array)$groups[$groupName], $data);
                    } else {
                        $groups[$groupName] = $data;
                    }
                }
            }
        }
        return $groups;
    }

    /**
     * Save configuration
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $section = $this->getRequest()->getParam('section');
            $website = $this->getRequest()->getParam('website');
            $theme = $this->getRequest()->getParam('theme_id');
            $store = $this->getRequest()->getParam('store');

            $configData = [
                'section' => $section,
                'store'	=> $store,
                'website' => $website,
                'theme' => $theme,
                'groups' => $this->_getGroupsForSave(),
            ];                        
            /** @var \Magento\Config\Model\Config $configModel  */
            $configModel = $this->_configFactory->create(['data' => $configData]);
            $configModel->save();
            $typographyList = array('css','fonts','page','header','menu','body','footer','buttons');

            //clear pub static file
            if($section == 'variables'){
		        $this->_objectManager->get('Magento\Framework\App\State\CleanupFiles')->clearMaterializedViewFiles();
				$this->_eventManager->dispatch('clean_static_files_cache_after');
				$this->messageManager->addSuccess(__('The static files cache has been cleaned.'));
		    }

			$this->_cache->clean();
            $this->messageManager->addSuccess(__('You saved the configuration.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $messages = explode("\n", $e->getMessage());
            foreach ($messages as $message) {
                $this->messageManager->addError($message);
            }
        } catch (\Exception $e) {
            $this->messageManager->addException(
                $e,
                __('Something went wrong while saving this configuration:') . ' ' . $e->getMessage()
            );
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath(
            'themeoptions/config/edit',
            [
            	'theme_id' => $theme,
                '_current' => ['section', 'website', 'store', 'code'],
                '_nosid' => true
            ]
        );
    }
}
