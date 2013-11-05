<?php

abstract class App_Student_Model_System
{
    private static $sdk = null;
    
    private static function getSdk()
    {
        if(self::$sdk === null) {
            self::$sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        }
        
        return self::$sdk;
    }
    
    public static function isRunning()
    {
        $sdk = self::getSdk();
        
        $res = $sdk->get("system-status");
        $res = json_decode($res, true);
        
        return $res['message'] == 'running';
    }
}