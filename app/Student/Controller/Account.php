<?php

class App_Student_Controller_Account extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function preAction()
    {
        ignore_user_abort();
        
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth->UserLevel != 0) {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $session = new Lib_Mein_Session('auth');
        $uuid = $session->auth->UUID;
        
        $this->getView()->account = App_Student_Model_Users::getOne($uuid);
        
        $this->getView()->content = $this->getView()->render('/account/index.phtml');
    }
    
    public function edit()
    {
        $session = new Lib_Mein_Session('auth');
        $uuid = $session->auth->UUID;
        $account = App_Student_Model_Users::getOne($uuid);
        
        $form = new App_Instructor_Form_Account_Edit($account);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $firstName = $form->getValue('first-name');
            $lastName = $form->getValue('last-name');
            $email = $form->getValue('email');
            
            App_Student_Model_Users::edit($uuid, $firstName, $lastName, $email);

        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = $account = App_Student_Model_Users::getOne($uuid);
        
        if($form->isSubmitted() && $form->isValid()) {
            $session->auth = $account;
        }
        
        $this->getView()->content = $this->getView()->render('/account/edit.phtml');
    }
    
    public function editPassword()
    {
        $session = new Lib_Mein_Session('auth');
        $uuid = $session->auth->UUID;
        $account = App_Student_Model_Users::getOne($uuid);
        
        $form = new App_Instructor_Form_Account_EditPassword();
        
        if($form->isSubmitted() && $form->isValid()) {
            App_Student_Model_Users::editPassword($uuid, $form->getValue('pass1'));
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = $account = App_Student_Model_Users::getOne($uuid);
        
        if($form->isSubmitted() && $form->isValid()) {
            $session->auth = $account;
        }
        
        $this->getView()->content = $this->getView()->render('/account/edit-password.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}