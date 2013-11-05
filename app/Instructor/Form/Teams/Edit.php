<?php

class App_Instructor_Form_Teams_Edit extends Lib_Mein_Form
{
    public function __construct($team)
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setRequired()
                ->setValue($team['name']);
        $this->addElement($name);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}