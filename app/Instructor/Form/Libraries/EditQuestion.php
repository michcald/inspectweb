<?php

class App_Instructor_Form_Libraries_EditQuestion extends Lib_Mein_Form
{
    public function __construct($question)
    {
        $this->setMethod('post');
        
        $position = new Lib_Mein_Form_Element_Select('position');
        $position->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_libraries_questions WHERE idlibrary={$question['idlibrary']} AND position!={$question['position']}");
        for($i=1 ; $i<=100 ; $i++)
        {
            if(array_search($i, $takenIndexes) !== false) continue;
            $position->addOption($i, $i);
        }
        $position->setValue($question['position']);
        $this->addElement($position);
        
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