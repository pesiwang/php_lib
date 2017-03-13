<?php

class Lib_Util_Ip {
    static public function getClientIp() {
        $ipKeyArr = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        $ipKeyArrLen = count($ipKeyArr);
        for ($i = 0; $i < $ipKeyArrLen; ++$i) {
            $key = $ipKeyArr[$i];
            if (isset($_SERVER[$key]) && self::isIp($_SERVER[$key])) {
                return $_SERVER[$key];
            }
        }

        return 'unknown';
    }

    static public function isIp($ip) {
        if (0 == preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$ip)) {
            return false;
        } else {
            return true;
        }
    }
}

