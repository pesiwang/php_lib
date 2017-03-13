<?php

class Lib_Util_Http {
    static public function post($url, $body = "", $header = array()) {
        $result = array();

        $ch = curl_init($url);
        if (!$ch) {
           $result['succ'] = false;
           $result['error'] = "curl_init fail,url=".$url;
           return $result;
        }

        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        
        $response = curl_exec($ch);
        if (false === $response) {
            $result['succ'] = false;
            $result['error'] = curl_error($ch);
        } else {
            $result['succ'] = true;
            $result['response'] = $response;
        }

        curl_close($ch);
        return $result;
    }

    static public function get($url, $data = array(), $header = array()) {
        $result = array();

        $url = $url . '?' . http_build_query($data);
        $ch = curl_init($url);
        if (!$ch) {
           $result['succ'] = false;
           $result['error'] = "curl_init fail,url=".$url;
           return $result;
        }

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        
        $response = curl_exec($ch);
        if (false === $response) {
            $result['succ'] = false;
            $result['errno'] = curl_errno($ch);
            $result['error'] = curl_error($ch);
        } else {
            $result['succ'] = true;
            $result['response'] = $response;
        }

        curl_close($ch);
        return $result;
    }
}

