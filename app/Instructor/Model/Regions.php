<?php

abstract class App_Instructor_Model_Regions
{
    private static $sdk = null;
    
    private static function getSdk()
    {
        if(self::$sdk === null) {
            self::$sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        }
        
        return self::$sdk;
    }
    
    public static function getAll($fields = null)
    {
        $sdk = self::getSdk();
        
        $res = $sdk->get('regions', array(
            'fields' => ($fields) ? $fields : 'regionName'
        ));
        
        return ($res) ? json_decode($res) : array();
    }
}