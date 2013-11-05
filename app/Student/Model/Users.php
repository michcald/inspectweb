<?php

abstract class App_Student_Model_Users
{
    private static $sdk = null;
    
    private static $identityMap = array();
    
    private static function getSdk()
    {
        if(self::$sdk === null) {
            self::$sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        }
        
        return self::$sdk;
    }
    
    private static function saveToIdentityMap($user)
    {
        self::$identityMap[$user->UUID] = $user;
    }
    
    private static function getFromIdentityMap($uuid)
    {
        if(array_key_exists($uuid, self::$identityMap)) {
            return self::$identityMap[$uuid];
        }
        
        return false;
    }
    
    public static function getAll($order = null, $limit = null)
    {
        $sdk = self::getSdk();
        
        $data = array(
            'fields' => 'FirstName,LastName,Email'
        );
        
        if($order) {
            $data['order'] = $order;
        }
        if($limit) {
            $data['limit'] = $limit;
        }
        
        $temp = $sdk->get("users", $data);
        $temp = json_decode($temp);
        
        foreach($temp as $t) {
            self::saveToIdentityMap($t);
        }
        
        return $temp;
    }
    
    public static function getOne($uuid)
    {
        if(self::getFromIdentityMap($uuid)) {
            return self::getFromIdentityMap($uuid);
        }
        
        $sdk = self::getSdk();
        
        $temp = $sdk->get("users/$uuid", array(
            'fields' => 'FirstName,LastName,Email'
        ));
        $temp = json_decode($temp);
        
        self::saveToIdentityMap($temp);
        
        return $temp;
    }
    
    public static function getOnline()
    {
        $sdk = self::getSdk();
        
        $res = $sdk->get('users', array(
            'online' => 'true',
            'fields' => 'FirstName,LastName,Email,Created,UserTitle,UserLevel,grid',
            'order' => 'FirstName ASC'
        ));
        $res = json_decode($res);
        
        foreach($res as $r) {
            self::saveToIdentityMap($r);
        }
        
        return $res;
    }
    
    public static function add($firstName, $lastName, $email, $password)
    {
        $sdk = self::getSdk();
        
        $sdk->post('users', array(
            'first-name' => $firstName,
            'last-name' => $lastName,
            'email' => $email,
            'password' => $password
        ));
    }
    
    public static function edit($uuid, $firstName, $lastName, $email)
    {
        $sdk = self::getSdk();
        
        $res = $sdk->put("users/$uuid", array(
            'first-name' => $firstName,
            'last-name' => $lastName,
            'email' => $email
        ));
        
        // remove the element from the identity
        if(array_key_exists($uuid, self::$identityMap))
        {
            $new = array();
            foreach(self::$identityMap as $key => $value)
            {
                if($key == $uuid) continue;
                $new[$key] = $value;
            }
            self::$identityMap = $new;
        }
        
        return $res;
    }
    
    public static function editPassword($uuid, $password)
    {
        $sdk = self::getSdk();
        
        $res = $sdk->put("auth/$uuid", array(
            'password' => $password
        ));
        
        return $res;
    }
}