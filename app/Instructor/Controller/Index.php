<?php

class App_Instructor_Controller_Index extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function preAction()
    {
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth->UserLevel != 100) {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $this->getView()->nextchallenges = $this->db->fetchAll(
                "SELECT * FROM challenges WHERE start>=\"" . date('Y-m-d H:i:s') . "\" ORDER BY start DESC");
        
        $this->getView()->lastchallenges = $this->db->fetchAll(
                "SELECT * FROM challenges WHERE end<\"" . date('Y-m-d H:i:s') . "\" ORDER BY start DESC LIMIT 5");
        
        $this->getView()->content = $this->getView()->render('/index/index.phtml');
        
        $this->getResponse()->setContent($this->getView()->render('layout.phtml'));
    }
    
    public function online()
    {
        $sdk = new Lib_OpenSimSdk('http://cc.ics.uci.edu/inspectworld/rest/api');
        
        $res = $sdk->get('users', array(
            'online' => 'true',
            'fields' => 'FirstName,LastName,Email,Created,UserTitle,UserLevel,grid',
            'order' => 'FirstName ASC'
        ));
        $res = json_decode($res);
        
        $this->getView()->online = $res;
        
        $this->getView()->content = $this->getView()->render('/index/online.phtml');
        
        $this->getResponse()->setContent($this->getView()->content);
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
        
    }
}