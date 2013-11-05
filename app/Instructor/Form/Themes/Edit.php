<?php

class App_Instructor_Form_Themes_Edit extends Lib_Mein_Form
{
    public function __construct($theme)
    {
        $this->setMethod('post');
        //$this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add'));
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setRequired()
                ->setValue($theme['name']);
        $this->addElement($name);
        
        $descr = new Lib_Mein_Form_Element_Textarea('description');
        $descr->setLabel('Description')
                ->setValue($theme['description']);
        $this->addElement($descr);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}