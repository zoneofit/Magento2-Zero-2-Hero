<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Controller\Adminhtml\Slideshow;

use Magento\Framework\Message\Error;

class Validate extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(       
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory) 
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
        
        
        
    }
    /**
     * Customer validation
     *
     * @param \Magento\Framework\DataObject $response
     * @return CustomerInterface|null
     */
    protected function _validateSlideshow($response)
    {
        $slideshow = null;
        $errors = [];

        try {
                                               
            $data = $this->getRequest()->getPostValue();
            if ($data) {
                $id = $this->getRequest()->getParam('slideshow_id');
                $model = $this->_objectManager->create('Codazon\Slideshow\Model\Slideshow');                
                    $checkModel = $model->checkIdentifier($data['identifier']);
                    //var_dump($checkModel);die;
                    if($checkModel && $id != $checkModel)
                        $errors = 'This identifier is exists';
                
            }
        } catch (\Magento\Framework\Validator\Exception $exception) {
            /* @var $error Error */
            foreach ($exception->getMessages(\Magento\Framework\Message\MessageInterface::TYPE_ERROR) as $error) {
                $errors[] = $error->getText();
            }
        }

        if ($errors) {
            $messages = $response->hasMessages() ? $response->getMessages() : [];            
            $messages = $errors;            
            $response->setMessages($messages);
            $response->setError(true);            
        }

        return $response;
    }

    

    /**
     * AJAX customer validation action
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        
        $response = new \Magento\Framework\DataObject();
        $response->setError(false);
        $this->_validateSlideshow($response);             
        if ($response->getError() === true) {
            $this->messageManager->addError($response->getMessages());
            $this->_view->getLayout()->initMessages();            
            $response->setHtmlMessage($this->_view->getLayout()->getMessagesBlock()->getGroupedHtml());
        }
        $response = $response->toJson();
        //$this->_translateInline->processResponseBody($response);
        $this->_response->representJson($response);
    }
}
