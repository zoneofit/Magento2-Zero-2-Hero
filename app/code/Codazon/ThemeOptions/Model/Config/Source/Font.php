<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\ThemeOptions\Model\Config\Source;

class Font implements \Magento\Framework\Option\ArrayInterface
{    
    public function toOptionArray()
    {
    
        $googlefont_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyA8_y7yeY_Y2RumG3eL-GlNhERGwPQDozg';
              
            $ch = curl_init($googlefont_api_url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $fonts = curl_exec($ch);
            curl_close($ch);
           
            $fonts = json_decode($fonts,true);
            $options = array();
            if (!isset($fonts['items'])) return $options; 
            
            foreach ($fonts['items'] as $item) {
                $options[$item['family']] = array(
                    'value' => $item['family'],
                    'label' => $item['family'],
                );
            }
            $options = array_values($options);            
            return $options;        
    }

    
}
