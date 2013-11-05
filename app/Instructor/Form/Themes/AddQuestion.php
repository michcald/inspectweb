<?php

class App_Instructor_Form_Themes_AddQuestion extends Lib_Mein_Form
{
    public function __construct($step)
    {
        $this->setMethod('post');
        $this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add-question'));
        
        $index = new Lib_Mein_Form_Element_Select('position');
        $index->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_steps_questions WHERE idstep={$step['id']}");
        for($i=1;$i<=100;$i++)
        {
            if(array_search($i, $takenIndexes) !== false) continue;
            $index->addOption($i, $i);
        }
        $index->setValue(Lib_Registry::get('db')->fetchOne("SELECT MAX(position) FROM themes_steps_questions WHERE idstep={$step['id']}") + 1);
        $this->addElement($index);
        
        $name = new Lib_Mein_Form_Element_Textarea('question');
        $name->setLabel('Question')->setRequired();
        $this->addElement($name);

        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
        
        $idTheme = new Lib_Mein_Form_Element_Hidden('idstep');
        $idTheme->setValue($step['id']);
        $this->addElement($idTheme);
    }
}