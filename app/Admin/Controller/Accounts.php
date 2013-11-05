<?php

class App_Admin_Controller_Accounts extends Mvc_Controller
{
    public function preAction()
    {
        ignore_user_abort();
        
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth != 'admin') {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $this->getView()->accounts = App_Admin_Model_Users::getAll('LastName ASC');
        
        $this->getView()->content = $this->getView()->render('/accounts/index.phtml');
    }
    
    public function add()
    {
        $form = new App_Admin_Form_Accounts_Add();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $userLevel = $form->getValue('user-level');
            $firstName = $this->getView()->firstName = $form->getValue('first-name');
            $lastName = $this->getView()->lastName = $form->getValue('last-name');
            $email = $this->getView()->email = $form->getValue('email');
            $password = $this->getView()->password = Lib_Mein_Random::string(6, true, true);
            
            App_Admin_Model_Users::add($userLevel, $firstName, $lastName, $email, $password);
            
            // send email to the user
            $subject = 'INspect-World new account';
            $body = $this->getView()->render('/accounts/add-email.phtml');
            App_Admin_Model_Mail::send($email, $subject, $body);
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/accounts/add.phtml');
    }
    
    public function edit()
    {
        $uuid = $this->getRequest()->getParam('id', false);
        
        if(!$uuid) {
            $this->redirect(array('m'=>'admin'));
        }
        
        $user = App_Admin_Model_Users::getOne($uuid);
        
        $form = new App_Admin_Form_Accounts_Edit($user);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $userLevel = $form->getValue('user-level');
            $firstName = $form->getValue('first-name');
            $lastName = $form->getValue('last-name');
            $email = $form->getValue('email');
            
            App_Admin_Model_Users::edit($uuid, $userLevel, $firstName, $lastName, $email);
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = App_Admin_Model_Users::getOne($uuid);
        
        $this->getView()->content = $this->getView()->render('/accounts/edit.phtml');
    }
    
    public function delete()
    {
        $uuid = $this->getRequest()->getParam('id', false);
        
        if(!$uuid) {
            $this->redirect(array('m'=>'admin'));
        }
        
        $user = App_Admin_Model_Users::getOne($uuid);
        
        $form = new App_Admin_Form_Accounts_Delete();
        
        if($form->isSubmitted() && $form->isValid()) {
            App_Admin_Model_Users::delete($uuid);
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = $user;
        
        $this->getView()->content = $this->getView()->render('/accounts/delete.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}