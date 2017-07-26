<?php
namespace Codazon\ThemeOptions\Framework\View\Asset\Source;
use Magento\Framework\App\Config\ScopeConfigInterface;
class Plugin
{
	public function __construct(
        \Codazon\ThemeOptions\Framework\App\Config\Initial $initialConfig,
        \Codazon\ThemeOptions\Model\Config\Reader\Store $storeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Codazon\ThemeOptions\Helper\Data $helper
    ) {
        $this->_initialConfig = $initialConfig;
        
        //$scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
        //$this->config = $this->_initialConfig->getData($scope);
        $storeId = $storeManager->getStore()->getId();
        $this->config = $storeConfig->read($storeId);
        $this->data = array();
        $this->helper = $helper;
    }
    public function getPath($data, $path = null)
    {
    	if(!is_array($data)){
    		$this->data[$path] = $data;
    	}else{
    		foreach($data as $key => $value){
    			$this->getPath($value, $key);
    			/*if($path){
    				$this->getPath($value, $path.'/'.$key);
    			}else{
    				$this->getPath($value, $key);
    			}*/
    		}
    	}
    }
    public function aroundGetContent($subject, $procede, $asset)
    {
    	$data = $this->config;
    	if(isset($this->config['variables'])){
    		$data = $this->config['variables'];
    	}
    	$this->getPath($data);
    	
    	$path = $asset->getPath();
    	$result = $procede($asset);    	
    	//echo "<pre>";print_r($this->data);die;
    	if (strpos($path,'source/_variables.less') !== false) {
    		//unset($this->data[0]);
    		foreach($this->data as $key => $value){
    			//$var = str_replace('/','-',$key);
    			//$result .= '@'.$key.":~'".$value."'; ";    			
    			if (strpos($value, '#') !== false) {
					$result .= '@'.$key.':'.$value.'; ';		
				} elseif(strpos($value, 'rgba(') !== false){
					$result .= '@'.$key.':'.$value.'; ';
				}else{
					
					if(preg_match("/background_file/",$key))
		            {		            			    	
		            	if($value)            	        	 	           	                                            
                    		$background_url = $this->helper->getBaseUrl() .'pub/media/codazon/themeoptions/background/'. $value;
                    	else
                    		$background_url = "";                    		                     
                   		$result .= '@'.$key.":~'".$background_url."'; ";                   
    	            }
    	            else
						$result .= '@'.$key.":~'".$value."'; ";
    			}
    		}
    		
		}
		
    	return $result;
    }
}
