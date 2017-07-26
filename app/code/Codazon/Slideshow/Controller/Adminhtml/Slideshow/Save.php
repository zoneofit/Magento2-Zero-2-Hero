<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Controller\Adminhtml\Slideshow;

use Magento\Framework\App\Filesystem\DirectoryList; 

class Save extends \Magento\Backend\App\Action
{

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('slideshow_id');
            $model = $this->_objectManager->create('Codazon\Slideshow\Model\Slideshow')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This slideshow no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $data['parameters'] = json_encode($data['parameters']);
            
            $data['slider_list']['images'] = $this->handleImageRemoveError($data);            
            if(array_key_exists('slider_list',$data))
                $data['content'] = json_encode($data['slider_list']['images']);
            else
                $data['content'] = '';

            
            $model->setData($data);
            
            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                $this->messageManager->addSuccess(__('You saved the slideshow.'));
                // clear previously saved data from session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slideshow_id' => $model->getId()]);
                }
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return $resultRedirect->setPath('*/*/edit', ['slideshow_id' => $this->getRequest()->getParam('slideshow_id')]);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    private function handleImageRemoveError($postData)
    {

        if (isset($postData['slider_list']['images'])) {
            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(DirectoryList::MEDIA);
            $config = $this->_objectManager->get('Codazon\Slideshow\Model\Slideshow\Media\Config');            
            foreach ($postData['slider_list']['images'] as $key => $image) {
                if (!empty($image['removed']) && $image['removed'] ==1 ) {
                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */                            
                    if(file_exists($mediaDirectory->getAbsolutePath($config->getMediaPath($image['file']))))
                        unlink($mediaDirectory->getAbsolutePath($config->getMediaPath($image['file'])));
                    unset($postData['slider_list']['images'][$key]);
                }
            }
            return $postData['slider_list']['images'];
        }
    }
}
