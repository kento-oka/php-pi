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
class Pin{

    /**
     * 
     * 
     * @var Gpio
     */
    private $gpio;
    
    /**
     * 
     * 
     * @var int
     */
    private $number;
    
    /**
     * Constructor
     * 
     * @param   int $number
     *      Pin number
     * @param   Gpio    $gpio
     *      wrapper instance
     */
    public function __construct(int $number, Gpio $gpio){
        $this->gpio     = $gpio;
        $this->number   = $number;
    }
    
    public function isExported(): bool{
        return file_exists($this->getGpioPath());
    }
    
    public function currentDirection(): string{
        if(!$this->isExported()){
            throw new \RuntimeException;
        }
        
        return (string)file_get_contents($this->getGpioPath("direction"));
    }
    
    /**
     * Export this pin
     * 
     * @param   string  $direction
     *      'in' or 'out'
     * 
     * @return  $this
     */
    public function export(string $direction = null){
        if($direction !== "in" && $direction !== "out" && $direction !== null){
            throw new \InvalidArgumentException;
        }
        
        $this->unexport();

        //export
        file_put_contents(Gpio::PATH_EXPORT, $this->number);

        if($direction !== null){
            file_put_contents($this->getGpioPath("direction"), $direction);
        }
        
        return $this;
    }
    
    public function exportIn(){
        return $this->export("in");
    }
    
    public function exportOut(){
        return $this->export("out");
    }
    
    /**
     * Unexport thins pin
     * 
     * @return  $this
     */
    public function unexport(){
        if($this->isExported()){
            file_put_contents(Gpio::PATH_UNEXPORT, $this->number);
        }
        
        return $this;
    }
    
    /**
     * Get input
     * 
     * @return  string
     */
    public function input(){
        if($this->currentDirection() !== "in"){
            throw new \LogicException;
        }
        
        $input  = file_get_contents($this->getGpioPath("value"));
        
        if($input === false){
            throw new \RuntimeException;
        }
        
        return trim($input) === "1" ? true : false;
    }
    
    /**
     * Send output
     *  
     * @param   bool    $value
     * 
     * @return  $this
     */
    public function output(bool $value){
        if($this->currentDirection() !== "out"){
            throw new \LogicException;
        }
        
        $result = file_put_contents($this->getGpioPath("value"), $value ? "1" : "0");
        
        if($result === false){
            throw new \RuntimeException;
        }
        
        return $this;
    }
    
    protected function getGpioPath(string $sub = null){
        return Gpio::PATH_GPIO . $this->number . ($sub !== null ? "/$sub" : "");
    }
    
}