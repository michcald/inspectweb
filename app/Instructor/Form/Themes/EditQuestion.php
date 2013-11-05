<?php

class App_Instructor_Form_Themes_EditQuestion extends Lib_Mein_Form
{
    public function __construct($question)
    {
        $this->setMethod('post');
        //$this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add-question'));
        
        $index = new Lib_Mein_Form_Element_Select('position');
        $index->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_steps_questions WHERE idstep={$question['idstep']} AND position!={$question['position']}");
        for($i=1;$i<=100;$i++)
        {
            if(array_search($i, $takenIndexes) !== false) continue;
            $index->addOption($i, $i);
        }
        $index->setValue($question['position']);
        $this->addElement($index);
        
        $name = new Lib_Mein_Form_Element_Textarea('question');
        $name->setLabel('Question')
                ->setRequired()
                ->setValue($question['question']);
        $this->addElement($name);

        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}