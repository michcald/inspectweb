<?php

class Mvc_Router
{
    private static $instance;
    
    private $rules = array();
    
    private function __construct() {}
    
    public static function getInstance()
    {
        if(self::$instance === null)
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        
        return self::$instance;
    }
    
    public function addRule(Mvc_Router_Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }
    
    public function route()
    {
        // Default rule has to be the last because it's always true
        $this->addRule(new Mvc_Router_Rule_Default());
        
        $pathInfo = pathinfo($_SERVER['PHP_SELF']);
        $uri = str_replace($pathInfo['dirname'], '', $_SERVER['REQUEST_URI']);
        
        foreach($this->rules as $r)
        {
            if($r->match($uri))
            {
                $module = $r->getModule();
                $controller = $r->getController();
                $action = $r->getAction();
                $params = $r->getParams();
                return new Mvc_Request($module, $controller, $action, $params);
            }
        }
    }
}