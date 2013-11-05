<?php

class App_Instructor_Form_Libraries_Add extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')->setRequired();
        $this->addElement($name);
        
        $descr = new Lib_Mein_Form_Element_Textarea('description');
        $descr->setLabel('Description');
        $this->addElement($descr);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
    }
}