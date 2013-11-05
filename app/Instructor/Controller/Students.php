<?php

class App_Instructor_Controller_Students extends Mvc_Controller
{
    public function preAction()
    {
        ignore_user_abort();
        
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth->UserLevel != 100) {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $this->getView()->students = App_Instructor_Model_Users::getStudents('LastName ASC');
        
        $this->getView()->content = $this->getView()->render('/students/index.phtml');
    }
    
    public function add()
    {
        $form = new App_Instructor_Form_Students_Add();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $firstName = $this->getView()->firstName = $form->getValue('first-name');
            $lastName = $this->getView()->lastName = $form->getValue('last-name');
            $email = $this->getView()->email = $form->getValue('email');
            $password = $this->getView()->password = Lib_Mein_Random::string(6, true, true, false);
            
            App_Instructor_Model_Users::add($firstName, $lastName, $email, $password);
            
            // send email to the user
            $subject = 'INspect-World new student account';
            $body = $this->getView()->render('/students/add-email.phtml');
            App_Instructor_Model_Mail::send($email, $subject, $body);
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/students/add.phtml');
    }
    
    public function edit()
    {
        $uuid = $this->getRequest()->getParam('id', false);
        
        if(!$uuid) {
            $this->redirect(array('m'=>'instructor'));
        }
        
        $user = App_Instructor_Model_Users::getOne($uuid);
        
        $form = new App_Instructor_Form_Students_Edit($user);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $firstName = $form->getValue('first-name');
            $lastName = $form->getValue('last-name');
            $email = $form->getValue('email');
            
            App_Instructor_Model_Users::edit($uuid, $firstName, $lastName, $email, 0);
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = App_Instructor_Model_Users::getOne($uuid);
        
        $this->getView()->content = $this->getView()->render('/students/edit.phtml');
    }
    
    public function delete()
    {
        $uuid = $this->getRequest()->getParam('id', false);
        
        if(!$uuid) {
            $this->redirect(array('m'=>'instructor'));
        }
        
        $user = App_Instructor_Model_Users::getOne($uuid);
        
        $form = new App_Instructor_Form_Students_delete();
        
        if($form->isSubmitted() && $form->isValid()) {
            App_Instructor_Model_Users::delete($uuid);
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->account = $user;
        
        $this->getView()->content = $this->getView()->render('/students/delete.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}