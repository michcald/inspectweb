<?php

class App_Student_Controller_Index extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function preAction()
    {
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth->UserLevel != 0) {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $session = new Lib_Mein_Session('auth');
        $idOpenSim = $session->auth->UUID;
        
        $challenges = $this->db->fetchAll(
                "SELECT DISTINCT challenges.* " . 
                "FROM challenges_teams_members,challenges_teams,challenges " .
                "WHERE idopensim=\"$idOpenSim\" AND " .
                    "challenges_teams_members.idteam=challenges_teams.id AND " .
                    "challenges_teams.idchallenge=challenges.id " .
                "ORDER BY start DESC");
        
        foreach($challenges as &$c)
        {
            $c['score'] = $this->db->fetchOne(
                    "SELECT SUM(bonus) AS score " .
                    "FROM challenges_teams,challenges_teams_members " .
                    "WHERE challenges_teams.id=challenges_teams_members.idteam");
            
            $c['score'] += $this->db->fetchOne(
                    "SELECT SUM(score) " .
                    "FROM challenges_teams_members,challenges_teams,challenges," .
                        "challenges_steps,challenges_steps_questions,challenges_steps_questions_answers " .
                    "WHERE " .
                        "idopensim=\"$idOpenSim\" AND " .
                        "challenges_teams_members.idteam=challenges_teams.id AND " .
                        "challenges_teams.idchallenge=challenges.id AND " .
                        "challenges.id=challenges_steps.idchallenge AND " .
                        "challenges_steps.id=challenges_steps_questions.idstep AND " .
                        "challenges_steps_questions.id=challenges_steps_questions_answers.idquestion");
        }
        
        $this->getView()->challenges = $challenges;
        
        $this->getView()->content = $this->getView()->render('/index/index.phtml');
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