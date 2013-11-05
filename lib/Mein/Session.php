<?php

class Lib_Mein_Session
{
    private static $sessionStarted = false;
    
    private $namespace = null;
	
    public function __construct($namespace = 'default')
    {
        if(!$namespace) {
            throw new Exception("Invalid namespace");
        }
        
        $this->namespace = $namespace;
        
	self::sessionStart();
	
	if(!array_key_exists($namespace, $_SESSION)) {
            $_SESSION[$namespace] = array();
	}
    }
    
    private static function sessionStart()
    {
        if(!self::$sessionStarted)
        {
            session_start();
            self::$sessionStarted = true;
        }
    }

    public function __get($key)
    {
        return (!array_key_exists($key, $_SESSION[$this->namespace])) ? false : $_SESSION[$this->namespace][$key];
    }

    public function __set($key, $value)
    {
	return $_SESSION[$this->namespace][$key] = $value;
    }

    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION[$this->namespace]);
    }

    public function __unset($key)
    {
	unset($_SESSION[$this->namespace][$key]);
    }

    public function unsetAll()
    {
        session_unset();
        session_destroy();
    }
    
    public function __toString()
    {
        return "<pre>" . print_r($_SESSION[$this->namespace], true) . "</pre>";
    }
}