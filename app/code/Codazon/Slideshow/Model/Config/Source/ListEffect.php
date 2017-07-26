<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Codazon\Slideshow\Model\Config\Source;

class ListEffect implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
         return [
          
                    ['value' => 'bounce', 'label'=>__('bounce')],
                    ['value' => 'flash', 'label'=>__('flash')],
                    ['value' => 'pulse', 'label'=>__('pulse')],
                    ['value' => 'rubberBand', 'label'=>__('rubberBand')],            
                    ['value' => 'shake', 'label'=>__('shake')],
                    ['value' => 'swing', 'label'=>__('swing')],
                    ['value' => 'tada', 'label'=>__('tada')],
                    ['value' => 'wobble', 'label'=>__('wobble')],
                    ['value' => 'bounceIn', 'label'=>__('bounceIn')],
                    ['value' => 'bounceInDown', 'label'=>__('bounceInDown')],
                    ['value' => 'bounceInLeft', 'label'=>__('bounceInLeft')],
                    ['value' => 'bounceInRight', 'label'=>__('bounceInRight')],            
                    ['value' => 'bounceInUp', 'label'=>__('bounceInUp')],
                    ['value' => 'bounceOut', 'label'=>__('bounceOut')],
                    ['value' => 'bounceOutDown', 'label'=>__('bounceOutDown')],
                    ['value' => 'bounceOutLeft', 'label'=>__('bounceOutLeft')],
                    ['value' => 'bounceOutRight', 'label'=>__('bounceOutRight')],            
                    ['value' => 'bounceOutUp', 'label'=>__('bounceOutUp')],
                    ['value' => 'fadeIn', 'label'=>__('fadeIn')],
                    ['value' => 'fadeInDown', 'label'=>__('fadeInDown')],
                    ['value' => 'fadeInDownBig', 'label'=>__('fadeInDownBig')],
                    ['value' => 'fadeInLeft', 'label'=>__('fadeInLeft')],            
                    ['value' => 'fadeInLeftBig', 'label'=>__('fadeInLeftBig')],
                    ['value' => 'fadeInRight', 'label'=>__('fadeInRight')],
                    ['value' => 'fadeInRightBig', 'label'=>__('fadeInRightBig')],
                    ['value' => 'fadeInUp', 'label'=>__('fadeInUp')],
                    ['value' => 'fadeInUpBig', 'label'=>__('fadeInUpBig')],
                    ['value' => 'fadeOut', 'label'=>__('fadeOut')],
                    ['value' => 'fadeOutDown', 'label'=>__('fadeOutDown')],
                    ['value' => 'fadeOutDownBig', 'label'=>__('fadeOutDownBig')],
                    ['value' => 'fadeOutLeft', 'label'=>__('fadeOutLeft')],            
                    ['value' => 'fadeOutLeftBig', 'label'=>__('fadeOutLeftBig')],
                    ['value' => 'fadeOutRight', 'label'=>__('fadeOutRight')],
                    ['value' => 'fadeOutRightBig', 'label'=>__('fadeOutRightBig')],
                    ['value' => 'fadeOutUp', 'label'=>__('fadeOutUp')],
                    ['value' => 'fadeOutUpBig', 'label'=>__('fadeOutUpBig')],
                    ['value' => 'flip', 'label'=>__('flip')],
                    ['value' => 'flipInX', 'label'=>__('flipInX')],
                    ['value' => 'flipInY', 'label'=>__('flipInY')],
                    ['value' => 'flipOutX', 'label'=>__('flipOutX')],            
                    ['value' => 'flipOutY', 'label'=>__('flipOutY')],
                    ['value' => 'lightSpeedIn', 'label'=>__('lightSpeedIn')],
                    ['value' => 'lightSpeedOut', 'label'=>__('lightSpeedOut')],
                    ['value' => 'rotateIn', 'label'=>__('rotateIn')],
                    ['value' => 'rotateInDownLeft', 'label'=>__('rotateInDownLeft')],
                    ['value' => 'rotateInDownRight', 'label'=>__('rotateInDownRight')],
                    ['value' => 'rotateInUpLeft', 'label'=>__('rotateInUpLeft')],            
                    ['value' => 'rotateInUpRight', 'label'=>__('rotateInUpRight')],
                    ['value' => 'rotateOut', 'label'=>__('rotateOut')],
                    ['value' => 'rotateOutDownLeft', 'label'=>__('rotateOutDownLeft')],
                    ['value' => 'rotateOutDownRight', 'label'=>__('rotateOutDownRight')],
                    ['value' => 'rotateOutUpLeft', 'label'=>__('rotateOutUpLeft')],            
                    ['value' => 'rotateOutUpRight', 'label'=>__('rotateOutUpRight')],
                    ['value' => 'slideInDown', 'label'=>__('slideInDown')],
                    ['value' => 'slideInLeft', 'label'=>__('slideInLeft')],
                    ['value' => 'slideInRight', 'label'=>__('slideInRight')],
                    ['value' => 'slideOutLeft', 'label'=>__('slideOutLeft')],            
                    ['value' => 'slideOutRight', 'label'=>__('slideOutRight')],
                    ['value' => 'slideOutUp', 'label'=>__('slideOutUp')],
                    ['value' => 'hinge', 'label'=>__('hinge')],
                    ['value' => 'rollIn', 'label'=>__('rollIn')],
                    ['value' => 'rollOut', 'label'=>__('rollOut')],
                        
        ];
    }
}

