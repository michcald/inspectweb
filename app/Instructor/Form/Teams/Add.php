<?php

class App_Instructor_Form_Teams_Add extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')->setRequired();
        $this->addElement($name);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
    }
}