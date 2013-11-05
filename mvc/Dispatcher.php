<?php

class Mvc_Dispatcher
{
    public static function dispatch(Mvc_Request $request)
    {
        self::bootstrap($request);
        
        $class = self::getControllerClass($request);
        
        $method = self::getMethod($request);
        
        /*if(!method_exists($class, $method)) {
            throw new Exception("Call to undefined method {$class}::{$method}()", 404);
        }*/
        
        $obj = new $class($request);
        
        if(!($obj instanceof Mvc_Controller)) {
            throw new Exception("Controller class {$class} has to extends Mvc_Controller class");
        }
        
        $obj->init();
        $obj->preAction();
        $obj->$method();
        $obj->postAction();
        
        return $obj->getResponse();
    }
    
    private static function bootstrap()
    {
        $bootstrapClass = 'App_Bootstrap';
        $bootstrapClass::boot();
    }
    
    private static function getModule($request)
    {
        $module = self::strToCamelCase($request->getModule());
        $module[0] = strtoupper($module[0]);
        
        return $module;
    }
    
    private static function getControllerClass($request)
    {
        $module = self::getModule($request);
        
        $controller = self::strToCamelCase($request->getController());
        $controller[0] = strtoupper($controller[0]);
        
        return "App_{$module}_Controller_{$controller}";
    }
    
    private static function getMethod($request)
    {
        $method = self::strToCamelCase($request->getAction());
        $method[0] = strtoupper($method[0]);
        return $method;
    }
    
    private static function strToCamelCase($string)
    {
        $string = strtolower($string);
        return str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', $string))));
    }
    
    private static function strFromCamelCase($string)
    {
        $string = preg_replace('~([A-Z])~', '-$1', $string);
        $string = strtolower($string);
        return trim($string, " -");
    }
}