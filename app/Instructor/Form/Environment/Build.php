<?php

class App_Instructor_Form_Environment_Build extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $challenges = Lib_Registry::get('db')->fetchAll(
                "SELECT * FROM challenges WHERE start>\"".date('Y-m-d H:i:s')."\" ORDER BY start ASC");
        
        if(count($challenges) > 0)
        {
            $challenge = new Lib_Mein_Form_Element_Select('challenge');
            $challenge->setLabel('Challenge')->setRequired();
            foreach($challenges as $c) {
                $challenge->addOption($c['id'], date('m/d/Y, h:i:sa', strtotime($c['start'])) . ' - ' . $c['name']);
            }
            $this->addElement($challenge);

            $region = new Lib_Mein_Form_Element_Select('region');
            $region->setLabel('Region')->setRequired();
            foreach(App_Instructor_Model_Regions::getAll() as $r) {
                $region->addOption($r->UUID, $r->regionName);
            }
            $this->addElement($region);
            
            $creator = new Lib_Mein_Form_Element_Select('creator');
            $creator->setLabel('Creator')->setRequired()
                    ->setDescription('Only an instructor can be a creator!');
            foreach(App_Instructor_Model_Users::getInstructors() as $u) {
                $creator->addOption($u->UUID, "{$u->FirstName} {$u->LastName}");
            }
            $this->addElement($creator);
            
            
            $submit = new Lib_Mein_Form_Element_Submit('submit');
            $submit->setValue('Build');
            $this->addElement($submit);
        }
    }
}