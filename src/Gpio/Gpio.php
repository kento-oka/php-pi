<?php
/**
 * 
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.
 * Redistributions of files must retain the above copyright notice.
 *
 * @author      Kento Oka <oka.kento0311@gmail.com>
 * @copyright   (c) Kento Oka
 * @license     MIT
 * @since       1.0.0
 */
namespace Pi\Gpio;

/**
 * 
 */
class Gpio{
    
    const PINS_REV1 = [
        0, 1, 4, 7, 8, 9,
        10, 11, 14, 15, 17, 18,
        21, 22, 23, 24, 25
    ];
    
    const PINS_REV2 = [
        2, 3, 4, 7, 8, 9,
        10, 11, 14, 15, 17, 18,
        22, 23, 24, 25, 27
    ];
    
    const PINS_NEW  = [
        2, 3, 4, 5, 6, 7, 8, 9,
        10, 11, 12, 13, 14, 15, 16, 17, 18, 19,
        20, 21, 22, 23, 24, 25, 26, 27
    ];
    
    const PATH_GPIO     = "/sys/class/gpio/gpio";
    const PATH_EXPORT   = "/sys/class/gpio/gpio/export";
    const PATH_UNEXPORT = "/sys/class/gpio/gpio/unexport";
    
    /**
     * 
     * 
     * @var Pin[]
     */
    private $pins   = [];
    
    /**
     * Constructor
     * 
     * @param   int[]   $pins
     */
    public function __construct(array $pins){
        foreach($pins as $number){
            if(is_int($number) && 0 <= $number){
                $this->pins[$number]    = new Pin($number, $this);
            }
        }
    }
    
    /**
     * Get pin instance
     * 
     * @param   int $number
     *      Pin number
     * 
     * @return  Pin
     */
    public function pin(int $number){
        if(!isset($this->pins[$number])){
            throw new \LogicException;
        }
        
        return $this->pins[$number];
    }
    
    /**
     * Unexport all pin
     * 
     * @return  $this
     */
    public function unexportAll(){
        foreach($this->pins as $pin){
            $pin->unexport();
        }
        
        return $this;
    }
}