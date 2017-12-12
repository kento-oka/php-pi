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
namespace Pi;

/**
 * Interface used to define regex shortcut implementation.
 */
class Pi{
    
    const CPU_TEMP      = "/sys/class/thermal/thermal_zone0/temp";
    const CPU_FREQUENCY = "/sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq";
    
    /**
     * 
     * @var Gpio
     */
    private $gpio;
    
    /**
     * Constructor
     */
    public function __construct(Gpio $gpio){
        $this->gpio = $gpio;    //  一時的なコンストラクタインジェクションです。
    }
    
    //  Piの状態の確認
    //  ファイル操作なども行えればベスト
    
    public function getCpuLoad(){
        return sys_getloadavg();
    }
	
    public function getCpuTemp(bool $fahrenheit = false){
        $temp   = floatval(file_get_contents(self::CPU_TEMP)) / 1000;
        $temp   = $fahrenheit ? 1.8 * $temp + 32 : $temp;
     
        return $temp;
    }
    
    public function getCpuFrequency(){
        return floatval(file_get_contents(self::CPU_FREQUENCY)) / 1000;
    }
}