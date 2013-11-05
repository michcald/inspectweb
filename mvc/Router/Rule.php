<?php

abstract class Mvc_Router_Rule
{
    const MODULE_KEY = 'm';
    const CONTROLLER_KEY = 'c';
    const ACTION_KEY = 'a';
    
    const DEFAULT_MODULE = 'default';
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';
    
    protected $params = array();
    
    public function __construct() {}
    
    public final function getModule()
    {
        return (array_key_exists(self::MODULE_KEY, $this->params)) ?
                $this->params[self::MODULE_KEY] : self::DEFAULT_MODULE;
    }
    
    public final function getController()
    {
        return (array_key_exists(self::CONTROLLER_KEY, $this->params)) ?
                $this->params[self::CONTROLLER_KEY] : self::DEFAULT_CONTROLLER;
    }
    
    public final function getAction()
    {
        return (array_key_exists(self::ACTION_KEY, $this->params)) ?
                $this->params[self::ACTION_KEY] : self::DEFAULT_ACTION;
    }
    
    public final function getParams()
    {
        return array_merge($this->params, $_GET, $_POST);
    }
    
    abstract public function match($string);
}