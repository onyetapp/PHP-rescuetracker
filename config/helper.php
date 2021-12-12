<?php
namespace OnyetApp;

class Helper {

    public static function is_ajax() {
        
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    }

    public static function base_url() {
        
        $basepath = rtrim(BASEPATH, '/');
        $basepath = ($basepath == '') ? '/' : '/' . $basepath . '/';
        return BASEURL . $basepath;
    
    }

}