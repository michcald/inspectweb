<?php

class App_Instructor_Form_Teams_AddMember extends Lib_Mein_Form
{
    public function __construct($idTeam)
    {
        $this->setMethod('post');
        $this->setAction(array('m'=>'instructor','c'=>'teams','a'=>'add-member'));
        
        $users = App_Instructor_Model_Users::getStudents('LastName ASC,FirstName ASC');
        
        $temp = 0;
        
        $member = new Lib_Mein_Form_Element_Select('idauth');
        $member->setLabel('Add member')->setRequired();
        foreach($users as $u)
        {
            if(!Lib_Registry::get('db')->fetchOne("SELECT id FROM teams_members WHERE idteam=$idTeam AND idauth='{$u->UUID}'"))
            {
                $member->addOption($u->UUID, $u->LastName.' '.$u->FirstName);
                $temp++;
            }
        }
        if($temp == 0) {
            return; // hide the form
        }
        $this->addElement($member);
        
        $role = new Lib_Mein_Form_Element_Select('role');
        $role->setLabel('Role')
                ->setDescription('There can only be one moderator and one scribe for each team')
                ->setRequired()
                ->addOption('evaluator', 'Evaluator');
        if(!Lib_Registry::get('db')->fetchOne("SELECT id FROM teams_members WHERE idteam=$idTeam AND role='moderator'")) {
            $role->addOption('moderator', 'Moderator');
        }
        if(!Lib_Registry::get('db')->fetchOne("SELECT id FROM teams_members WHERE idteam=$idTeam AND role='scribe'")) {
            $role->addOption('scribe', 'Scribe');
        }
        $this->addElement($role);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
        
        $team = new Lib_Mein_Form_Element_Hidden('idteam');
        $team->setValue($idTeam);
        $this->addElement($team);
    }
}