<?php

class App_Instructor_Controller_Teams extends Mvc_Controller
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
        $teams = $this->db->fetchAll('SELECT * FROM teams ORDER BY name ASC');
        
        foreach($teams as &$t) {
            $t['members'] = $this->db->countRows("SELECT * FROM teams_members WHERE idteam={$t['id']}");
        }
        
        $this->getView()->teams = $teams;
        
        $this->getView()->content = $this->getView()->render('/teams/index.phtml');
    }
    
    public function add()
    {
        $form = new App_Instructor_Form_Teams_Add();
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->insert('teams', $form->getValues());
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->content = $this->getView()->render('/teams/add.phtml');
    }
    
    public function edit()
    {
        $idteam = (int)$this->getRequest()->getParam('id', false);
        
        $team = $this->db->fetchRow("SELECT * FROM teams WHERE id=$idteam");
        
        $form = new App_Instructor_Form_Teams_Edit($team);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->update('teams', $form->getValues(), "id=$idteam");
        } else {
            $this->getView()->form = $form;
        }
        
        $this->getView()->team = $this->db->fetchRow("SELECT * FROM teams WHERE id=$idteam");
        
        $this->getView()->content = $this->getView()->render('/teams/edit.phtml');
    }
    
    public function delete()
    {
        $idteam = (int)$this->getRequest()->getParam('id', false);
        
        $team = $this->db->fetchRow("SELECT * FROM teams WHERE id=$idteam");
        
        $form = new App_Instructor_Form_Teams_Delete();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->delete('teams', "id=$idteam");
            $this->db->delete('teams_members', "idteam=$idteam");
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->team = $team;
        
        $this->getView()->content = $this->getView()->render('/teams/delete.phtml');
    }
    
    public function addMember()
    {
        $idTeam = (int)$this->getRequest()->getParam('idteam', false);
        
        if(!$idTeam) {
            $this->redirect(array('m'=>'instructor'));
        }
        
        $form = new App_Instructor_Form_Teams_AddMember($idTeam);
        
        if($form->isSubmitted() && $form->isValid()) {
            $this->db->insert('teams_members', $form->getValues());
        }
        
        $members = $this->db->fetchAll("SELECT * FROM teams_members WHERE idteam=$idTeam");
        
        foreach($members as &$m)
        {
            $temp = App_Instructor_Model_Users::getOne($m['idauth']);
            $m['FirstName'] = $temp->FirstName;
            $m['LastName'] = $temp->LastName;
            $m['Email'] = $temp->Email;
        }
        
        $this->getView()->members = $members;
        
        $this->getView()->team = $this->db->fetchRow("SELECT * FROM teams WHERE id=$idTeam");
        
        $this->getView()->form = new App_Instructor_Form_Teams_AddMember($idTeam);
        
        $this->getView()->content = $this->getView()->render('/teams/add-member.phtml');
    }
    
    public function editMember()
    {
        $idMember = (int)$this->getRequest()->getParam('id', false);
        
        $form = new App_Instructor_Form_Teams_EditMember($idMember);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->db->update('teams_members', array(
                'role' => $form->getValue('role')
            ), "id=$idMember");
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $row = $this->db->fetchRow("SELECT * FROM teams_members WHERE id=$idMember");
        
        $user = App_Instructor_Model_Users::getOne($row['idauth']);
        $user->role = $row['role'];
        $this->getView()->member = $user;
        
        $this->getView()->team = $this->db->fetchRow("SELECT * FROM teams WHERE id={$row['idteam']}");
        
        $this->getView()->content = $this->getView()->render('/teams/edit-member.phtml');
    }
    
    public function deleteMember()
    {
        $idMember = (int)$this->getRequest()->getParam('id', false);
        
        $row = $this->db->fetchRow("SELECT * FROM teams_members WHERE id=$idMember");
        
        $this->db->delete('teams_members', "id=$idMember");
        
        $this->redirect(array('m'=>'instructor','c'=>'teams','a'=>'add-member','idteam'=>$row['idteam']));
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('/layout.phtml'));
    }
}