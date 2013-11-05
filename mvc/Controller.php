<?php

class Mvc_Controller
{
    private $request = null;
    
    private $response = null;
    
    private $view = null;
    
    public final function __construct($request)
    {
        $this->request = $request;
        $this->response = new Mvc_Response();
        $this->view = new Mvc_View($request->getModule());
    }
    
    public function init()
    {
        
    }
    
    public function preAction()
    {
        
    }
    
    public function postAction()
    {
        
    }
    
    protected final function redirect($data, $seconds = 0)
    {
        $pathInfo = pathinfo($_SERVER['PHP_SELF']);
        
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $pathInfo['dirname'] . '/index.php';
        
        $url = (is_array($data)) ? $url . '?' . http_build_query($data) : $url . $data;
        
        header("Refresh: $seconds; url=$url");

        if($seconds == 0) {
            die();
        }
    }
    
    /**
     *
     * @return Mvc_Request
     */
    public final function getRequest()
    {
        return $this->request;
    }
    
    /**
     *
     * @return Mvc_Response
     */
    public final function getResponse()
    {
        return $this->response;
    }
    
    /**
     *
     * @return Mvc_View
     */
    protected function getView()
    {
        return $this->view;
    }
}