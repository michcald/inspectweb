<?php

class App_Auth_Controller_Index extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Session
     */
    private $session = null;
    
    public function init()
    {
        $this->session = new Lib_Mein_Session('auth');
    }
    
    public function index()
    {
        if(isset($this->session->auth))
        {
            if($this->session->auth == 'admin')
            {
                $this->redirect(array('m'=>'admin'));
                return;
            }
            
            switch($this->session->auth->UserLevel)
            {
                case 0: $this->redirect(array('m'=>'student'), 2); break; // student
                case 100: $this->redirect(array('m'=>'instructor'), 2); break; // instructor
                default: $this->redirect(array('m'=>'auth','a'=>'logout'));
            }
        }            
        
        if($this->getRequest()->isPost())
        {
            $email = $this->getRequest()->getParam('email', false);
            $password = $this->getRequest()->getParam('password', false);
            
            if($email && $password)
            {
                if($email == 'admin' && $password == 'qwerty')
                {
                    $this->session->auth = 'admin';
                    $this->redirect(array('m'=>'admin'));
                    return;
                }
                
                $sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        
                $res = $sdk->get('auth', array(
                    'email' => $email,
                    'password' => $password
                ));
                $res = json_decode($res);
                
                if(isset($res->uuid))
                {
                    $user = $sdk->get("users/{$res->uuid}", array(
                        'fields' => 'FirstName,LastName,UserLevel,Email'
                    ));
            
                    $user = json_decode($user);
                    
                    $this->getView()->user = $user;
                    
                    $this->session->auth = $user;
                    
                    switch($user->UserLevel)
                    {
                        case 0: $this->redirect(array('m'=>'student'), 2); break; // student
                        case 100: $this->redirect(array('m'=>'instructor'), 2); break; // instructor
                        default: $this->redirect(array('m'=>'auth','a'=>'logout'));
                    }
                }
            }
        }
        
        $this->getResponse()->setContent($this->getView()->render('index.phtml'));
    }
    
    public function logout()
    {
        $this->session->unsetAll();
        $this->redirect(array('m'=>'auth'));
    }
}