<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Block;

class Latesttweets extends \Magento\Framework\View\Element\Template
{
    const CACHE_LIFETIME = 3600;
	const TWITTER_API_URL = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    protected $_objectManager;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {                    
        $this->_objectManager = $objectManager;
        parent::__construct($context);
    }

    public function getTweetsByUsername ($username, $limit = 2)
    {                        
        $username     = trim($username);
        $limit        = intval($limit);
        $cacheKey     = 'twitter_' . md5($username);
        $cacheTags    = array('twitter', $cacheKey);
        $tweets       = array();

        if (!$username || !$limit || $limit <= 0) {
            return array();
        }
        
        $responseJson = $this->_loadCache($cacheKey . '_' . $limit);

        if (empty($responseJson)) {
            
            $twitter = $this->_objectManager->create('Codazon\ThemeOptions\Model\TwitterApi');
            // set our parameters for accessing user_timeline.json
            // see more: https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline            
            $getParameters = array(
                'screen_name=' . urlencode($username),
                'count=' . urlencode($limit)
            );

            // this is where the request is being made
            $responseJson = $twitter->setGetfield('?' . implode('&', $getParameters))            
                ->buildOauth(self::TWITTER_API_URL, 'GET')
                ->performRequest();

            // save tweets to cache
            $this->_saveCache($responseJson,
                $cacheKey . '_' . $limit,
                $cacheTags,
                self::CACHE_LIFETIME);

        }
               
        $response = json_decode($responseJson,true);       
        if (isset($response['statuses'])) {
            $response = $response['statuses'];
        }
        if (empty($response)) {
            return array();
        }

        return $response;
    }


}
