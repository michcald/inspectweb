<?php

include 'Application/Autoloader.php';

class Mvc_Application
{
    private $devMode = null;
    
    public function __construct($devMode = false)
    {
        $this->devMode = $devMode;
        
        $this->initAutoloading();
        $this->initException();
    }
    
    private function initAutoloading()
    {
        spl_autoload_register(array('Mvc_Application_Autoloader', 'autoload'));
    }
    
    private function initException()
    {
        if($this->devMode) {
            set_exception_handler(array('Mvc_Exception_Handler', 'devMode'));
        } else {
            set_exception_handler(array('Mvc_Exception_Handler', 'prodMode'));
        }
    }
    
    public function run()
    {
        App_Bootstrap::boot();

        $request = Mvc_Router::getInstance()->route();
        
        $response = Mvc_Dispatcher::dispatch($request);

        echo $response;
    }
}