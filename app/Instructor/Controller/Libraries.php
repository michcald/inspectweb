<?php

class App_Instructor_Controller_Libraries extends Mvc_Controller
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
        $this->getView()->libraries = $this->db->fetchAll("SELECT * FROM themes_libraries ORDER BY name");
        
        $this->getView()->content = $this->getView()->render('/libraries/index.phtml');
    }
    
    public function add()
    {
        $form = new App_Instructor_Form_Libraries_Add();
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->insert('themes_libraries', $form->getValues());
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/libraries/add.phtml');
    }
    
    public function edit()
    {
        $id = (int)$this->getRequest()->getParam('id', false);
        
        if(!$id) {
            return false;
        }
        
        $library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id=$id");
        
        $form = new App_Instructor_Form_Libraries_Edit($library);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->update('themes_libraries', $form->getValues(), "id=$id");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id=$id");;
        
        $this->getView()->content = $this->getView()->render('/libraries/edit.phtml');
    }
    
    public function delete()
    {
        $id = (int)$this->getRequest()->getParam('id', false);
        
        if(!$id) {
            return false;
        }
        
        $library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id=$id");;
        
        $form = new App_Instructor_Form_Libraries_Delete();
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->delete('themes_libraries', "id=$id");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->library = $library;
        
        $this->getView()->content = $this->getView()->render('/libraries/delete.phtml');
    }
    
    public function questions()
    {
        $id = (int)$this->getRequest()->getParam('id', false);
        
        if(!$id) {
            return false;
        }
        
        $form = new App_Instructor_Form_Libraries_AddQuestion($id);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->insert('themes_libraries_questions', $form->getValues());
            $this->redirect(array('m'=>'instructor','c'=>'libraries','a'=>'questions','id'=>$id));
        }
        
        $this->getView()->form = $form;
        
        $this->getView()->library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id=$id");
        
        $this->getView()->questions = $this->db->fetchAll(
                "SELECT * FROM themes_libraries_questions WHERE idlibrary=$id ORDER BY position ASC");
        
        $this->getView()->content = $this->getView()->render('/libraries/questions.phtml');
    }
    
    public function editQuestion()
    {
        $id = (int)$this->getRequest()->getParam('id', false);
        
        if(!$id) {
            return false;
        }
        
        $question = $this->db->fetchRow("SELECT * FROM themes_libraries_questions WHERE id=$id");
        
        $form = new App_Instructor_Form_Libraries_EditQuestion($question);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->update('themes_libraries_questions', $form->getValues(), "id=$id");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id={$question['idlibrary']}");
        
        $this->getView()->question = $this->db->fetchRow("SELECT * FROM themes_libraries_questions WHERE id=$id");;
        
        $this->getView()->content = $this->getView()->render('/libraries/edit-question.phtml');
    }
    
    public function deleteQuestion()
    {
        $id = (int)$this->getRequest()->getParam('id', false);
        
        if(!$id) {
            return false;
        }
        
        $question = $this->db->fetchRow("SELECT * FROM themes_libraries_questions WHERE id=$id");;
        
        $form = new App_Instructor_Form_Libraries_DeleteQuestion();
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->delete('themes_libraries_questions', "id=$id");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->library = $this->db->fetchRow("SELECT * FROM themes_libraries WHERE id={$question['idlibrary']}");
        
        $this->getView()->question = $question;
        
        $this->getView()->content = $this->getView()->render('/libraries/delete-question.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}