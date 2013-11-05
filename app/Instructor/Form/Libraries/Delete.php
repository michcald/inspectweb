<?php

class App_Instructor_Form_Libraries_Delete extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Delete')->setIgnored();
        $this->addElement($submit);
    }
}