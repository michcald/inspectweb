<?php

class App_Instructor_Form_Challenges_Add extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')->setRequired();
        $this->addElement($name);
        
        $start = new Lib_Mein_Form_Element_DateTime('start');
        $start->setLabel('Start time')
                ->setAttribute('style', 'width: auto')
                ->setRequired()
                ->setDay(date('d'))
                ->setMonth(date('m'))
                ->setYear(date('Y'))
                ->setHour(date('H'))
                ->setMinute(date('i'))
                ->setSecond(0);
        $this->addElement($start);
        
        $end = new Lib_Mein_Form_Element_DateTime('end');
        $end->setLabel('End time')
                ->setAttribute('style', 'width: auto')
                ->setRequired()
                ->setDay(date('d'))
                ->setMonth(date('m'))
                ->setYear(date('Y'))
                ->setHour(date('H')+1)
                ->setMinute(date('i'))
                ->setSecond(0);
        $this->addElement($end);
        
        $theme = new Lib_Mein_Form_Element_Select('idtheme');
        $theme->setLabel('Theme')->setRequired()->setIgnored();
        foreach(Lib_Registry::get('db')->fetchAll("SELECT * FROM themes") as $t) {
            $theme->addOption($t['id'], $t['name']);
        }
        $this->addElement($theme);
        
        $team1 = new Lib_Mein_Form_Element_Select('team1');
        $team1->setLabel('Team 1')->setRequired();
        foreach(Lib_Registry::get('db')->fetchAll("SELECT * FROM teams ORDER BY name ASC") as $t) {
            $team1->addOption($t['id'], $t['name']);
        }
        $this->addElement($team1);
        
        $team2 = new Lib_Mein_Form_Element_Select('team2');
        $team2->setLabel('Team 2')->setRequired();
        foreach(Lib_Registry::get('db')->fetchAll("SELECT * FROM teams ORDER BY name ASC") as $t) {
            $team2->addOption($t['id'], $t['name']);
        }
        $this->addElement($team2);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
    }
    
    public function isValid()
    {
        if(!parent::isValid()) {
            return false;
        }
        
        // verify that the end time is after the start time
        $start = strtotime($this->getValue('start'));
        $end = strtotime($this->getValue('end'));
        if($end <= $start)
        {
            $val = new Lib_Mein_Validate_Never();
            $val->setErrorMessage('The end date has to be after the start date');
            $this->getElement('end')->addValidator($val);
            $this->getElement('end')->isValid();
            return false;
        }
        
        // verify that the instructor has selected 2 different teams
        $team1 = $this->getValue('team1');
        $team2 = $this->getValue('team2');
        if($team1 == $team2)
        {
            $val = new Lib_Mein_Validate_Never();
            $val->setErrorMessage('You can\'t select the same teams');
            $this->getElement('team2')->addValidator($val);
            $this->getElement('team2')->isValid();
            return false;
        }
        
        // verify if a student is shared between the two teams
//        $db = Lib_Registry::get('db');
//        $members1 = $db->fetchCol("SELECT idauth FROM teams_members WHERE idteam=$team1");
//        $members2 = $db->fetchCol("SELECT idauth FROM teams_members WHERE idteam=$team2");
//        $shared = array_intersect($members1, $members2);
//        Lib_Debug::dump($members1);
//        Lib_Debug::dump($members2);
//        die();
//        if($shared > 0)
//        {
//            $val = new Lib_Mein_Validate_Never();
//            $val->setErrorMessage('The two teams are sharing a student');
//            $this->getElement('team2')->addValidator($val);
//            $this->getElement('team2')->isValid();
//            return false;
//        }
        
        return true;
    }
}