<?php

class App_Inspectworld_Controller_Index extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function init()
    {
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $idTeam = (int)$this->getRequest()->getParam('team', false);
        $idStep = (int)$this->getRequest()->getParam('step', false);
        
        if(!$idStep || !$idTeam) {
            return false;
        }
        
        $step = $this->db->fetchRow("SELECT * FROM challenges_steps WHERE id=$idStep");
        
        $challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id={$step['idchallenge']}");
        
        $this->getView()->challenge = $challenge;

        $this->getView()->step = $step;
        
        $this->getView()->questions = $this->db->fetchAll(
                "SELECT challenges_steps_questions.*,answer,challenges_steps_questions_answers.id AS idanswer " .
                "FROM challenges_steps_questions,challenges_steps_questions_answers " .
                "WHERE " .
                    "idstep=$idStep AND " .
                    "challenges_steps_questions.id=idquestion AND " .
                    "idteam=$idTeam " .
                "ORDER BY challenges_steps_questions.position ASC");

        $this->getView()->team = $this->db->fetchRow("SELECT * FROM challenges_teams WHERE id=$idTeam");
        
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
    
    public function saveAnswer()
    {
        if(!$this->getRequest()->isAjax()) {
            die('No ajax request');
        }
        
        $idAnswer = (int)$this->getRequest()->getParam('id', false);
        $answer = $this->getRequest()->getParam('answer', false);
        
        if(!$idAnswer) {
            die("Id ($idAnswer) unreachable");
        }
        
        $this->db->update('challenges_steps_questions_answers', array(
            'answer' => trim($answer)
        ), "id=$idAnswer");
        
        die('ok');
    }
    
    public function getAnswers()
    {
        if(!$this->getRequest()->isAjax()) {
            die('No ajax request');
        }
        
        $idTeam = (int)$this->getRequest()->getParam('team', false);
        $idStep = (int)$this->getRequest()->getParam('step', false);
        
        $res = $this->db->fetchAll("SELECT challenges_steps_questions_answers.* " .
                "FROM challenges_steps_questions_answers,challenges_steps_questions " .
                "WHERE challenges_steps_questions_answers.idteam=$idTeam AND " .
                    "challenges_steps_questions.idstep=$idStep AND " .
                    "challenges_steps_questions_answers.idquestion=challenges_steps_questions.id");

        echo json_encode($res);
        
        die();
    }
    
    // who is online in the team
    public function online()
    {
        $team = (int)$this->getRequest()->getParam('team', false);
        
    }
    
    public function scoreboard()
    {
        $idChallenge = (int)$this->getRequest()->getParam('challenge', false);
        
        $challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
        $challenge['questions'] = $this->db->countRows(
                "SELECT * FROM challenges_steps,challenges_steps_questions " .
                "WHERE idchallenge=$idChallenge AND challenges_steps.id=challenges_steps_questions.idstep");
        $this->getView()->challenge = $challenge;
        
        $teams = $this->db->fetchAll(
                "SELECT * FROM challenges_teams WHERE idchallenge=$idChallenge");
        
        foreach($teams as &$t)
        {
            $t['answers'] = $this->db->countRows(
                    "SELECT * " .
                    "FROM challenges,challenges_steps," .
                        "challenges_steps_questions,challenges_steps_questions_answers " .
                    "WHERE challenges.id=$idChallenge AND challenges_steps.idchallenge=challenges.id AND " .
                        "challenges_steps_questions.idstep=challenges_steps.id AND " .
                        "challenges_steps_questions_answers.idquestion=challenges_steps_questions.id AND " .
                        "challenges_steps_questions_answers.idteam={$t['id']} AND " .
                        "challenges_steps_questions_answers.answer!=''"
                    );
                        
            $t['score'] = $this->db->fetchOne(
                    "SELECT SUM(score) " .
                    "FROM challenges_steps,challenges_steps_questions,challenges_steps_questions_answers " .
                    "WHERE challenges_steps.idchallenge=$idChallenge AND " .
                        "challenges_steps.id=challenges_steps_questions.idstep AND " .
                        "challenges_steps_questions.id=challenges_steps_questions_answers.idquestion AND " .
                        "challenges_steps_questions_answers.idteam={$t['id']}");
        }
        
        $this->getView()->teams = $teams;
        
        $this->getResponse()->setContent($this->getView()->render('/scoreboard.phtml'));
    }
}