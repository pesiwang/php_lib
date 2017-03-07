<?php

class Lib_Util_Logger {
    const RUNTIME_LEVEL_FATAL     = 0x01;
    const RUNTIME_LEVEL_ERROR     = 0x02;
    const RUNTIME_LEVEL_WARNING   = 0x03;
    const RUNTIME_LEVEL_INFO      = 0x04;
    const RUNTIME_LEVEL_DEBUG     = 0x05;

    static private $_runtimeDir     = '/tmp/runtime';
    static private $_actionDir      = '/tmp/action';
    static private $_payDir         = '/tmp/pay';

    static private $_runtimeLevel   = self::RUNTIME_LEVEL_DEBUG;

    static public function init($runtimeDir, $actionDir, $payDir, $runtimeLevel = self::RUNTIME_LEVEL_DEBUG) {
        self::$_runtimeDir      = $runtimeDir;
        self::$_actionDir       = $actionDir;
        self::$_payDir          = $payDir;

        self::$_runtimeLevel    = $runtimeLevel;
    }

    static public function runtime($uid, $msg, $level = self::RUNTIME_LEVEL_DEBUG) {
        $data = '[' . date('Y-m-d H:i:s') . '][';
        switch($level){
            case self::RUNTIME_LEVEL_FATAL:
                $data .= 'FATAL';
                break;
            case self::RUNTIME_LEVEL_ERROR:
                $data .= 'ERROR';
                break;
            case self::RUNTIME_LEVEL_WARNING:
                $data .= 'WARNING';
                break;
            case self::RUNTIME_LEVEL_INFO:
                $data .= 'INFO';
                break;
            default:
                $data .= 'DEBUG';
                break;
        }

        self::_formatMsg($msg);
        $data .= '][' . $uid . ']' . $msg . "\n";
        self::_write(self::$_runtimeDir, 'rt', $data);
    }

    static public function action($uid, $type, $extra = null) {
        $data = time() . '|' . $uid . '|' . $type;
        if (isset($extra)) {
            $data .= '|' . $extra;
        }
        $data .= "\n";
        self::_write(self::$_actionDir, 'action', $data);
    }

    static public function pay($uid, $msg) {
        $data = '[' . date('Y-m-d H:i:s') . '][' . $uid . ']';
        self::_formatMsg($msg);
        $data .= $msg . "\n";
        self::_write(self::$_runtimeDir, 'pay', $data, true);
    }

    static private function _formatMsg(&$msg){
        $debugInfo = '';
        foreach(array_reverse(debug_backtrace()) as $debug){
            if(strlen($debugInfo) > 0){
                $debugInfo .= '==>';
            }
            if(isset($debug['class']) && isset($debug['function'])){
                $debugInfo .= $debug['class'] . '::' . $debug['function'];
            }
        }
        $debugInfo = '[' . $debugInfo . ']';
        $msg .= $debugInfo;
    }

    static private function _write($dir, $prefix, $msg, $needFlush = false){
        $filename = $prefix . '_' . date('Ymd') . '.log';
        $fp = fopen($dir . '/' . $filename, 'a');
        if($fp){
            fwrite($fp, $msg);
            if ($needFlush) {
                fflush($fp);
            }
            fclose($fp);
        }
    }
}
