<?php

class Mvc_Request
{
    private $module = null;
    
    private $controller = null;
    
    private $action = null;
    
    private $params = array();
    
    public function __construct($module, $controller, $action, array $params)
    {
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
    
    public function getModule()
    {
        return $this->module;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function getParam($key, $default = null)
    {
        if(array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        
        if($default !== null) {
            return $default;
        }
        
        throw new Exception("Key $key doesn't exists");
    }
    
    public function isPost()
    {
	return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    public function __toString()
    {
        return $_SERVER['PHP_SELF'] . '?' . http_build_query($this->params);
    }
}