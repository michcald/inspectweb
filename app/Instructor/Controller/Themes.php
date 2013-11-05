<?php

class App_Instructor_Controller_Themes extends Mvc_Controller
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
        $this->getView()->themes = $this->db->fetchAll("SELECT * FROM themes ORDER BY name ASC");
        
        $this->getView()->content = $this->getView()->render('/themes/index.phtml');
    }
    
    public function overview()
    {
        $themes = $this->db->fetchAll("SELECT * FROM themes ORDER BY name ASC");
        
        foreach($themes as &$t)
        {
            $steps = $this->db->fetchAll("SELECT * FROM themes_steps WHERE idtheme={$t['id']} ORDER BY position ASC");
            
            foreach($steps as &$s) {
                $s['questions'] = $this->db->fetchAll("SELECT * FROM themes_steps_questions WHERE idstep={$s['id']} ORDER BY position ASC");
            }
            
            $t['steps'] = $steps;
        }
        
        $this->getView()->themes = $themes;
        
        $this->getView()->content = $this->getView()->render('/themes/overview.phtml');
    }
    
    public function add()
    {
        $form = new App_Instructor_Form_Themes_Add();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $idTheme = $this->db->insert('themes', $form->getValues());
            
            // read & create the steps
            $steps = $form->getValue('steps'); // steps number
            for($i=1 ; $i <=$steps ; $i++)
            {
                $this->db->insert('themes_steps', array(
                    'idtheme' => $idTheme,
                    'name' => "Step $i",
                    'position' => $i
                ));
            }
            
            // read the selected library
            $idLibrary = $form->getValue('library'); // library id
            
            if($idLibrary)
            {
                $questions = $this->db->fetchAll(
                    "SELECT * FROM themes_libraries_questions WHERE idlibrary=$idLibrary");
                
                $steps = $this->db->fetchCol(
                        "SELECT id FROM themes_steps WHERE idtheme=$idTheme");
                
                foreach($steps as $s)
                {
                    foreach($questions as $q)
                    {
                        $this->db->insert('themes_steps_questions', array(
                            'idstep' => $s,
                            'question' => $q['question'],
                            'position' => $q['position']
                        ));
                    }
                }
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/themes/add.phtml');
    }
    
    public function edit()
    {
        $idTheme = (int)$this->getRequest()->getParam('id', false);
        
        $theme = $this->db->fetchRow("SELECT * FROM themes WHERE id=$idTheme");
        
        $form = new App_Instructor_Form_Themes_Edit($theme);
        
        if($form->isSubmitted() && $form->isValid()) {
           $this->db->update('themes', $form->getValues(), "id=$idTheme");
        } else {
            $this->getView()->form = $form;
        }
        
         $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id=$idTheme");
        
        $this->getView()->content = $this->getView()->render('/themes/edit.phtml');
    }
    
    public function delete()
    {
        $idTheme = (int)$this->getRequest()->getParam('id', false);
        
        $theme = $this->db->fetchRow("SELECT * FROM themes WHERE id=$idTheme");
        
        $form = new App_Instructor_Form_Themes_Delete();
        
        if($form->isSubmitted() && $form->isValid())
        {
           $this->db->delete('themes', "id=$idTheme");
           
           $steps = $this->db->fetchCol("SELECT id FROM themes_steps WHERE idtheme=$idTheme");
           foreach($steps as $s)
           {
               $this->db->delete('themes_steps', "id=$s");
               
               @unlink("pub/img/themes/steps/$s.jpg");
               
               $this->db->delete('themes_steps_questions', "idstep=$s");
           }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
         $this->getView()->theme = $theme;
        
        $this->getView()->content = $this->getView()->render('/themes/delete.phtml');
    }
    
    public function steps()
    {
        $idTheme = (int)$this->getRequest()->getParam('id', false);
        
        $theme = $this->db->fetchRow("SELECT * FROM themes WHERE id=$idTheme");
        
        if(!$theme) {
            return false;
        }
        
        $this->getView()->theme = $theme;
        
        $steps = $this->db->fetchAll(
                "SELECT * FROM themes_steps WHERE idtheme=$idTheme ORDER BY position");
        
        foreach($steps as &$s) {
            $s['questions'] = $this->db->countRows("SELECT * FROM themes_steps_questions WHERE idstep={$s['id']}");
        }
        
        $this->getView()->steps = $steps;
        
        $this->getView()->content = $this->getView()->render('/themes/steps.phtml');
    }
    
    public function addStep()
    {
        $idTheme = (int)$this->getRequest()->getParam('idtheme', false);
        
        $theme = $this->db->fetchRow("SELECT * FROM themes WHERE id=$idTheme");
        
        if(!$theme) {
            return false;
        }
        
        $form = new App_Instructor_Form_Themes_AddStep($theme['id']);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $idStep = $this->db->insert('themes_steps', $form->getValues());
            
            $src = $form->getValue('img');
            $img = new Lib_Mein_Image($src);
            if($img->getWidth() != 400) {
                $img->resizeToWidth(400);
            }
            $img->save("pub/img/themes/steps/$idStep.jpg");
            unlink($src);
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->theme = $theme;
        
        $this->getView()->content = $this->getView()->render('/themes/add-step.phtml');
    }
    
    public function editStep()
    {
        $idStep = (int)$this->getRequest()->getParam('idstep', false);
        
        $step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id=$idStep");
        
        $form = new App_Instructor_Form_Themes_EditStep($step);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->update('themes_steps', $form->getValues(), "id=$idStep");
            
            if($src = $form->getValue('img'))
            {
                $img = new Lib_Mein_Image($src);
                if($img->getWidth() != 400) {
                    $img->resizeToWidth(400);
                }
                $img->save("pub/img/themes/steps/$idStep.jpg");
                unlink($src);
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id=$idStep");
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$step['idtheme']}");
        
        $this->getView()->content = $this->getView()->render('/themes/edit-step.phtml');
    }
    
    public function deleteStep()
    {
        $idStep = (int)$this->getRequest()->getParam('idstep', false);
        
        $step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id=$idStep");
        
        $form = new App_Instructor_Form_Themes_DeleteStep();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->delete('themes_steps', "id=$idStep");
            
            @unlink("pub/img/themes/steps/$idStep.jpg");
            
            $this->db->delete('themes_steps_questions', "idstep=$idStep");
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->step = $step;
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$step['idtheme']}");
        
        $this->getView()->content = $this->getView()->render('/themes/delete-step.phtml');
    }
    
    public function questions()
    {
        $idStep = (int)$this->getRequest()->getParam('idstep', false);
        
        $step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id=$idStep");
        
        if(!$step) {
            return false;
        }
        
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$step['idtheme']}");
        $this->getView()->step = $step;
        
        $this->getView()->questions = $this->db->fetchAll(
                "SELECT * FROM themes_steps_questions WHERE idstep=$idStep ORDER BY position ASC");
        
        $this->getView()->content = $this->getView()->render('/themes/questions.phtml');
    }
    
    public function addQuestion()
    {
        $idStep = (int)$this->getRequest()->getParam('idstep', false);
        
        $step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id=$idStep");
        
        if(!$step) {
            return false;
        }
        
        $form = new App_Instructor_Form_Themes_AddQuestion($step);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->insert('themes_steps_questions', $form->getValues());
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->step = $step;
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$step['idtheme']}");
        
        $this->getView()->content = $this->getView()->render('/themes/add-question.phtml');
    }
    
    public function editQuestion()
    {
        $idQuestion = (int)$this->getRequest()->getParam('idquestion', false);
        
        $question = $this->db->fetchRow("SELECT * FROM themes_steps_questions WHERE id=$idQuestion");
        
        $form = new App_Instructor_Form_Themes_EditQuestion($question);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->update('themes_steps_questions', $form->getValues(), "id=$idQuestion");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->question = $this->db->fetchRow("SELECT * FROM themes_steps_questions WHERE id=$idQuestion");
        $this->getView()->step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id={$question['idstep']}");
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$this->getView()->step['idtheme']}");
        
        $this->getView()->content = $this->getView()->render('/themes/edit-question.phtml');
    }
    
    public function deleteQuestion()
    {
        $idQuestion = (int)$this->getRequest()->getParam('idquestion', false);
        
        $question = $this->db->fetchRow("SELECT * FROM themes_steps_questions WHERE id=$idQuestion");
        
        $form = new App_Instructor_Form_Themes_DeleteQuestion();
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->delete('themes_steps_questions', "id=$idQuestion");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->question = $question;
        $this->getView()->step = $this->db->fetchRow("SELECT * FROM themes_steps WHERE id={$question['idstep']}");
        $this->getView()->theme = $this->db->fetchRow("SELECT * FROM themes WHERE id={$this->getView()->step['idtheme']}");
        
        $this->getView()->content = $this->getView()->render('/themes/delete-question.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}