<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Magefan\Blog\Controller\Adminhtml\Post;

/**
 * Blog post save controller
 */
class Save extends \Magefan\Blog\Controller\Adminhtml\Post
{
	/**
	 * Before model save
	 * @param  \Magefan\Blog\Model\Post $model
	 * @param  \Magento\Framework\App\Request\Http $request
	 * @return void
	 */
	protected function _beforeSave($model, $request)
	{		
		$identifier = $model->getData('identifier') ? $model->getData('identifier') : $this->generateIdentifier($model->getData('title'),$model->getData('stores'));
		if($identifier)         
			$model->setData('identifier',$identifier);
		if ($links = $request->getParam('links')) {

			foreach (array('post', 'product') as $key) {
				$param = 'related'.$key.'s';
				if (!empty($links[$param])) {
					$ids = array_unique(
						array_map('intval',
							explode('&', $links[$param])
						)
					);
					if (count($ids)) {
						$model->setData('related_'.$key.'_ids', $ids);
					}
				}
			}
		}
	}
	
	protected function generateIdentifier($label,$stores)
	{
		$postModel = $this->_objectManager->create('Magefan\Blog\Model\Post');
		$code = substr(
		    preg_replace(
		        '/[^a-z_0-9]/',
		        '_',
		        $postModel->formatUrlKey($label)
		    ),
		    0,
		    30
		);
		$validatorAttrCode = new \Zend_Validate_Regex(['pattern' => '/^[a-z][a-z_0-9]{0,29}[a-z0-9]$/']);
		if (!$validatorAttrCode->isValid($code)) {
		    $code = 'blog_' . ($code ?: substr(md5(time()), 0, 8));
		}		
		$checkIdenfier = $postModel->checkIdentifier($code,$stores);
		if($checkIdenfier)
			$code = $code.'_'.substr(md5(time()), 0, 2);			
		return $code;
	}
	
}
