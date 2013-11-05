<?php

class App_Admin_Controller_System extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function preAction()
    {
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth != 'admin') {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        
    }
    
    public function start()
    {
        $form = new App_Admin_Form_System_Start();
        
        if($form->isSubmitted())
        {
            $res = App_Admin_Model_System::start();;
            
            if($res == 'false') {
                $this->getView()->error = 'The system is already running!';
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/system/start.phtml');
    }
    
    public function stop()
    {
        $form = new App_Admin_Form_System_Stop();
        
        if($form->isSubmitted())
        {
            $res = App_Admin_Model_System::stop();;
            
            if($res == 'false') {
                $this->getView()->error = 'The system is not running!';
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/system/stop.phtml');
    }
    
    public function reboot()
    {
        $form = new App_Admin_Form_System_Reboot();
        
        if($form->isSubmitted()) {
            App_Admin_Model_System::reboot();
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/system/reboot.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}