<?php

class App_Instructor_Form_Teams_EditMember extends Lib_Mein_Form
{
    public function __construct($idMember)
    {
        $this->setMethod('post');
        //$this->setAction(array('m'=>'instructor','c'=>'teams','a'=>'edit-member'));
        
        $db = Lib_Registry::get('db');
        
        $row = $db->fetchRow("SELECT role,idteam FROM teams_members WHERE id=$idMember");
        
        $role = new Lib_Mein_Form_Element_Select('role');
        $role->setLabel('Role')
                ->setDescription('There can only be one moderator and one scribe for each team')
                ->setRequired()
                ->addOption('evaluator', 'Evaluator');
        if(!$db->fetchOne("SELECT id FROM teams_members WHERE idteam={$row['idteam']} AND role='moderator'")) {
            $role->addOption('moderator', 'Moderator');
        }
        if(!$db->fetchOne("SELECT id FROM teams_members WHERE idteam={$row['idteam']} AND role='scribe'")) {
            $role->addOption('scribe', 'Scribe');
        }
        $this->addElement($role);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}