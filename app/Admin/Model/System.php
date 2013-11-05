<?php

abstract class App_Admin_Model_System
{
    private static $sdk = null;
    
    private static function getSdk()
    {
        if(self::$sdk === null) {
            self::$sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        }
        
        return self::$sdk;
    }

    public static function start()
    {
        $sdk = self::getSdk();
        
        return $sdk->get("system", array('cmd' => 'start'));
    }
    
    public static function stop()
    {
        $sdk = self::getSdk();
        
        return $sdk->get("system", array('cmd' => 'stop'));
    }
    
    public static function reboot()
    {
        $sdk = self::getSdk();
        
        return $sdk->get("system", array('cmd' => 'reboot'));
    }
    
    public static function isRunning()
    {
        $sdk = self::getSdk();
        
        $res = $sdk->get("system-status");
        $res = json_decode($res, true);
        
        return $res['message'] == 'running';
    }
}