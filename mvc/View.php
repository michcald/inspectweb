<?php

class Mvc_View
{
    private $module = null;
    
    private $data = array();
    
    public function __construct($module)
    {
        $this->module = $module;
    }
    
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function __get($key)
    {
        return (array_key_exists($key, $this->data)) ? $this->data[$key] : null;
    }
    
    public function __call($name, $arguments)
    {
        $class = str_replace(' ', '_', ucwords(str_replace('_', ' ', $name)));
        
        $helperClassName = 'App_' . ucfirst($this->module) . '_View_Helper_' . $class;
        
        $helper = new $helperClassName();
        
        return call_user_func_array(array($helper, $name), $arguments);
    }
    
    public function url($data = null)
    {
        $pathInfo = pathinfo($_SERVER['PHP_SELF']);
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $pathInfo['dirname'];
        
        if(is_array($data))
        {
            return $url . '?' . http_build_query($data);
        }
        
        return $url . $data;
    }
    
    public function render($file)
    {
        if($file[0] != '/') {
            $file = '/' . $file;
        }
        
        $file = "app/{$this->module}/view/html$file";

        if(!file_exists($file)) {
            throw new Exception("View file $file not found");
        }

        ob_start();
        include $file;
        $content = ob_get_contents();
        ob_end_clean();
        
        return $content;
    }
}