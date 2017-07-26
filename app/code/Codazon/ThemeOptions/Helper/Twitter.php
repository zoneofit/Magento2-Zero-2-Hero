<?php
namespace Codazon\ThemeOptions\Helper;

class Twitter extends \Magento\Framework\App\Helper\AbstractHelper
{
	const OAUTH_ACCESS_TOKEN        = '1892673373-WqpGyl3fF0U5oLheku7ampV14SNSo3kgHCuMfMI';
    const OAUTH_ACCESS_TOKEN_SECRET = 'L15SyB2g0tEnFQTxrtik9PiFiWAptD4Uee2vYZ6lDcl2q';
    const CONSUMER_KEY              = 'l1Bip3e9eD7Z32rHdficFePz9';
    const CONSUMER_SECRET           = 'cWjJexaNog0tLl2d7PyKIxvv3GX2EoNq0qUtMbsY9JT81DNag6';
    const SCREEN_NAME				= 'twitter';
    protected $_themeHelper;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
	  \Codazon\ThemeOptions\Helper\Data $themeHelper
    ) {
	  $this->_themeHelper = $themeHelper;
        parent::__construct($context);        
    }

    function parseJsonTweetToHtml($tweet, $getinfo)
    {
        
        $_tweet = trim($tweet['text']);

        if($getinfo)
        {            
            $entities = array();

            if(in_array("link",$getinfo) && is_array($tweet['entities']['urls']))
            {
                foreach($tweet['entities']['urls'] as $e)
                {
                    $temp["start"] = $e['indices'][0];
                    $temp["end"] = $e['indices'][1];
                    $temp["replacement"] = "<a href='".$e['expanded_url']."' target='_blank'>".$e['display_url']."</a>";
                    $entities[] = $temp;
                }
            }
            if(in_array( "mention", $getinfo) && is_array($tweet['entities']['user_mentions']))
            {
                foreach($tweet['entities']['user_mentions'] as $e)
                {
                    $temp["start"] = $e['indices'][0];
                    $temp["end"] = $e['indices'][1];
                    $temp["replacement"] = "<a href='https://twitter.com/".$e['screen_name']."' target='_blank'>@".$e['screen_name']." </a>";
                    $entities[] = $temp;
                }
            }
            if(in_array("hashtag",$getinfo) && is_array($tweet['entities']['hashtags']))
            {
                foreach($tweet['entities']['hashtags'] as $e)
                {
                    $temp["start"] = $e['indices'][0];
                    $temp["end"] = $e['indices'][1];
                    $temp["replacement"] = "<a href='https://twitter.com/hashtag/".$e['text']."?src=hash' target='_blank'>#".$e['text']."</a>";
                    $entities[] = $temp;
                }
            }

            if(array_key_exists("media",$tweet['entities']))
            {
                if(in_array("media",$getinfo) && $tweet['entities']['media'])
                {

                    foreach($tweet['entities']['media'] as $e)
                    {
                        $temp["start"] = $e['indices'][0];
                        $temp["end"] = $e['indices'][1];
                        $temp["replacement"] = "<a href='".$e["expanded_url"]."' target='_blank'>".$e['display_url']."</a>";
                        $entities[] = $temp;
                    }
                }
            }

            usort($entities, function($a,$b){ 
            	return($b["start"]-$a["start"]);
            	});
			

            foreach($entities as $item)
            {
                $_tweet = substr_replace($_tweet, $item["replacement"], $item["start"], $item["end"] - $item["start"]);
            }
        }
        return $_tweet;
    }

	public function getAccessToken()
	{	
		
		return $this->_themeHelper->getConfig('general_section/twitter/oauth_access_token') ? $this->_themeHelper->getConfig('general_section/twitter/oauth_access_token')
				: self::OAUTH_ACCESS_TOKEN;
	}
	
	public function getAccessTokenSecret()
	{
		return $this->_themeHelper->getConfig('general_section/twitter/oauth_access_token_secret') ? $this->_themeHelper->getConfig('general_section/twitter/oauth_access_token_secret')
				: self::OAUTH_ACCESS_TOKEN_SECRET;
	}
    
	public function getConsumerKey()
	{
		return $this->_themeHelper->getConfig('general_section/twitter/consumer_key') ? $this->_themeHelper->getConfig('general_section/twitter/consumer_key')
				: self::CONSUMER_KEY;
	}
	public function getConsumerSecret()
	{
		return $this->_themeHelper->getConfig('general_section/twitter/consumer_secret') ? $this->_themeHelper->getConfig('general_section/twitter/consumer_secret')
					: self::CONSUMER_SECRET;
	}
	
	public function getScreenName()
	{
		return $this->_themeHelper->getConfig('general_section/twitter/screen_name') ? $this->_themeHelper->getConfig('general_section/twitter/screen_name')
					: self::SCREEN_NAME;
		
	}
	
    public function formatDate($date,$format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }
}
