<?php

class App_Instructor_Form_Libraries_AddQuestion extends Lib_Mein_Form
{
    public function __construct($idLibrary)
    {
        $this->setMethod('post');
        
        $position = new Lib_Mein_Form_Element_Select('position');
        $position->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_libraries_questions WHERE idlibrary=$idLibrary");
        for($i=1 ; $i<=100 ; $i++)
        {
            if(array_search($i, $takenIndexes) !== false) continue;
            $position->addOption($i, $i);
        }
        $this->addElement($position);
        
        $name = new Lib_Mein_Form_Element_Textarea('question');
        $name->setLabel('Question')->setRequired();
        $this->addElement($name);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
        
        $library = new Lib_Mein_Form_Element_Hidden('idlibrary');
        $library->setValue($idLibrary);
        $this->addElement($library);
    }
}