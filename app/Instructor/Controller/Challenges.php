<?php

class App_Instructor_Controller_Challenges extends Mvc_Controller
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
        
        ignore_user_abort();
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $this->getView()->challenges = $this->db->fetchAll("SELECT * FROM challenges ORDER BY start DESC");
        
        $this->getView()->content = $this->getView()->render('/challenges/index.phtml');
    }
    
    public function add()
    {
        $form = new App_Instructor_Form_Challenges_Add();
        
        if($form->isSubmitted() && $form->isValid())
        {
            // create the challenge
            $idChallenge = $this->db->insert('challenges', array(
                'name' => $form->getValue('name'),
                'start' => date('Y:m:d H:i:s', strtotime($form->getValue('start'))),
                'end' => date('Y:m:d H:i:s', strtotime($form->getValue('end'))),
                'theme' => $this->db->fetchOne("SELECT name FROM themes WHERE id={$form->getValue('idtheme')}")
            ));
            
            // copy the two teams and the members
            $idTeam1 = $this->db->insert('challenges_teams', array(
                'idchallenge' => $idChallenge,
                'name' => $this->db->fetchOne("SELECT name FROM teams WHERE id={$form->getValue('team1')}")
            ));
            $members = $this->db->fetchAll("SELECT * FROM teams_members WHERE idteam={$form->getValue('team1')}");
            foreach($members as $m)
            {
                $this->db->insert('challenges_teams_members', array(
                    'idteam' => $idTeam1,
                    'idopensim' => $m['idauth'],
                    'role' => $m['role']
                ));
            }
            
            $idTeam2 = $this->db->insert('challenges_teams', array(
                'idchallenge' => $idChallenge,
                'name' => $this->db->fetchOne("SELECT name FROM teams WHERE id={$form->getValue('team2')}")
            ));
            $members = $this->db->fetchAll("SELECT * FROM teams_members WHERE idteam={$form->getValue('team2')}");
            foreach($members as $m)
            {
                $this->db->insert('challenges_teams_members', array(
                    'idteam' => $idTeam2,
                    'idopensim' => $m['idauth'],
                    'role' => $m['role']
                ));
            }
            
            // copy the steps and the questions
            $steps = $this->db->fetchAll("SELECT * FROM themes_steps WHERE idtheme={$form->getValue('idtheme')}");
            foreach($steps as $s)
            {
                $idStep = $this->db->insert('challenges_steps', array(
                    'idchallenge' => $idChallenge,
                    'name' => $s['name'],
                    'position' => $s['position']
                ));
                
                // copy the image
                if(file_exists("pub/img/themes/steps/{$s['id']}.jpg"))
                {
                    $img = new Lib_Mein_Image("pub/img/themes/steps/{$s['id']}.jpg");
                    $img->save("pub/img/challenges/steps/$idStep.jpg");
                }
                
                $questions = $this->db->fetchAll("SELECT * FROM themes_steps_questions WHERE idstep={$s['id']}");
                foreach($questions as $q)
                {
                    $idQuestion = $this->db->insert('challenges_steps_questions', array(
                        'idstep' => $idStep,
                        'question' => $q['question'],
                        'position' => $q['position']
                    ));
                    
                    // foreach team creates empty answers
                    $teams = $this->db->fetchAll("SELECT * FROM challenges_teams WHERE idchallenge=$idChallenge");
                    foreach($teams as $t)
                    {
                        $this->db->insert('challenges_steps_questions_answers', array(
                            'idquestion' => $idQuestion,
                            'idteam' => $t['id'],
                            'answer' => ''
                        ));
                    }
                }
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/challenges/add.phtml');
    }
    
    public function edit()
    {
        $idChallenge = (int)$this->getRequest()->getParam('id', false);
        
        if(!$idChallenge) {
            return false;
        }
        
        $challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
        
        $form = new App_Instructor_Form_Challenges_Edit($challenge);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->update('challenges', $form->getValues(), "id=$idChallenge");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
        
        $this->getView()->content = $this->getView()->render('/challenges/edit.phtml');
    }
    
    public function delete()
    {
        $form = new App_Instructor_Form_Challenges_Delete();
        
        $idChallenge = (int)$this->getRequest()->getParam('id', false);
        
        $this->getView()->challenge = $this->db->fetchRow("SElECT * FROM challenges WHERE id=$idChallenge");
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->delete("challenges", "id=$idChallenge");
            
            $steps = $this->db->fetchCol("SELECT id FROM challenges_steps WHERE idchallenge=$idChallenge");
            
            foreach($steps as $s)
            {
                $this->db->delete("challenges_steps", "id=$s");
                
                $questions = $this->db->fetchCol("SELECT id FROM challenges_steps_questions WHERE idstep=$s");
                
                foreach($questions as $q)
                {
                    $this->db->delete("challenges_steps_questions", "id=$q");
                    $this->db->delete("challenges_steps_questions_answers", "idquestion=$q");
                }
            }
            
            $teams = $this->db->fetchCol("SELECT id FROM challenges_teams WHERE idchallenge=$idChallenge");
            
            foreach($teams as $t)
            {
                $this->db->delete("challenges_teams", "id=$t");
                $this->db->delete("challenges_teams_members", "idteam=$t");
            }
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/challenges/delete.phtml');
    }
    
    public function teams()
    {
        $idChallenge = (int)$this->getRequest()->getParam('id', false);
        
        $challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
        
        if(!$challenge) {
            return false;
        }
        
        $teams = $this->db->fetchAll("SELECT * FROM challenges_teams WHERE idchallenge=$idChallenge");
        
        foreach($teams as &$t)
        {
            $t['members'] = $this->db->countRows("SELECT * FROM challenges_teams_members WHERE idteam={$t['id']}");
            $t['score'] = $this->db->fetchOne(
                    "SELECT SUM(score) AS score " .
                    "FROM challenges_steps_questions_answers,challenges_steps_questions,challenges_steps " .
                    "WHERE idchallenge=$idChallenge AND idteam={$t['id']} AND ".
                            "challenges_steps_questions_answers.idquestion=challenges_steps_questions.id AND " .
                            "challenges_steps_questions.idstep=challenges_steps.id");
        }
        
        $this->getView()->teams = $teams;
        
        $this->getView()->challenge = $challenge;
        
        $this->getView()->content = $this->getView()->render('/challenges/teams.phtml');
    }
    
    public function members()
    {
        $idTeam = (int)$this->getRequest()->getParam('id', false);
        
        if(!$idTeam) {
            $this->redirect(array('m'=>'instructor'));
        }
        
        $members = $this->db->fetchAll("SELECT * FROM challenges_teams_members WHERE idteam=$idTeam");
        
        foreach($members as &$m)
        {
            $temp = App_Instructor_Model_Users::getOne($m['idopensim']);
            $m['FirstName'] = $temp->FirstName;
            $m['LastName'] = $temp->LastName;
            $m['Email'] = $temp->Email;
        }
        
        $this->getView()->members = $members;
        
        $this->getView()->team = $team = $this->db->fetchRow("SELECT * FROM challenges_teams WHERE id=$idTeam");
        
        $this->getView()->challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id={$team['idchallenge']}");
        
        $this->getView()->content = $this->getView()->render('/challenges/members.phtml');
    }
    
    public function steps()
    {
        $idChallenge = (int)$this->getRequest()->getParam('id', false);
        
        $challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
        
        if(!$challenge) {
            return false;
        }
        
        $this->getView()->challenge = $challenge;
        
        $steps = $this->db->fetchAll(
                "SELECT * FROM challenges_steps WHERE idchallenge=$idChallenge ORDER BY position");
        
        foreach($steps as &$s) {
            $s['questions'] = $this->db->countRows("SELECT * FROM challenges_steps_questions WHERE idstep={$s['id']}");
        }
        
        $this->getView()->steps = $steps;
        
        $this->getView()->content = $this->getView()->render('/challenges/steps.phtml');
    }
    
    public function questions()
    {
        $idStep = (int)$this->getRequest()->getParam('idstep', false);
        
        $step = $this->db->fetchRow("SELECT * FROM challenges_steps WHERE id=$idStep");
        
        if(!$step) {
            return false;
        }
        
        $this->getView()->challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id={$step['idchallenge']}");
        $this->getView()->step = $step;
        
        $this->getView()->questions = $this->db->fetchAll(
                "SELECT * FROM challenges_steps_questions WHERE idstep=$idStep ORDER BY position ASC");
        
        $this->getView()->content = $this->getView()->render('/challenges/questions.phtml');
    }
    
    public function answers()
    {
        $idQuestion = (int)$this->getRequest()->getParam('id', false);
        
        $answers = $this->db->fetchAll(
                "SELECT challenges_steps_questions_answers.*,challenges_teams.name AS team " .
                "FROM challenges_steps_questions_answers,challenges_teams " .
                "WHERE idquestion=$idQuestion AND challenges_teams.id=idteam");
        
        $this->getView()->answers = $answers;
        
        $this->getView()->question = $question = $this->db->fetchRow(
                "SELECT * FROM challenges_steps_questions WHERE id={$answers[0]['idquestion']}");
        
        $this->getView()->step = $step = $this->db->fetchRow(
                "SELECT * FROM challenges_steps WHERE id={$question['idstep']}");
        
        $this->getView()->challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id={$step['idchallenge']}");
        
        $this->getView()->content = $this->getView()->render('/challenges/answers.phtml');
    }
    
    public function setScore()
    {
        if(!$this->getRequest()->isAjax()) {
            die();
        }
        
        $idAnswer = (int)$this->getRequest()->getParam('id', false);
        $score = (int)$this->getRequest()->getParam('score', false);
        
        $this->db->update('challenges_steps_questions_answers', array(
            'score' => $score
        ), "id=$idAnswer");
        
        die();
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}