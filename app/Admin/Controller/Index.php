<?php

class App_Admin_Controller_Index extends Mvc_Controller
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
    
    public function status()
    {
        if(App_Instructor_Model_System::isRunning()) {
            echo "<font color=\"green\">Running</font>";
        } else {
            echo "<font color=\"red\">Not Running</font>";
        }
        
        die();
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}