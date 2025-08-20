<?php
namespace wtsd\common;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Log
{
    static public function write($logfile, array $info)
    {
        $logDir = ROOT . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        $logPath = $logDir . $logfile . '.log';                
        $writeMode = 'w';
        $log = implode('|', $info);
        $logString = sprintf('%s: %s', date("Y-m-d H:i:s"), $log);                          

        if (!file_exists($logDir) || !is_dir($logDir) || !is_writable($logDir)) {
            return false;
        }

        if (file_exists($logPath) && is_file($logPath) && is_writable($logPath) ) {
            $writeMode = 'a';
        }
                                    
        if (!$handler = fopen($logPath, $writeMode)) {
            return false;
        }
                          
        if (fwrite($handler, $logString. "\n") == FALSE ) {
            return false;
        }
                                                                
        @fclose($handler);
    }
}
