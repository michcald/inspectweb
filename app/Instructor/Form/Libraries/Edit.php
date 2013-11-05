<?php

class App_Instructor_Form_Libraries_Edit extends Lib_Mein_Form
{
    public function __construct($library)
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setRequired()
                ->setValue($library['name']);
        $this->addElement($name);
        
        $descr = new Lib_Mein_Form_Element_Textarea('description');
        $descr->setLabel('Description')
                ->setValue($library['description']);
        $this->addElement($descr);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}