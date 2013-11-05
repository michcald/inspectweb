<?php

class App_Admin_Form_Accounts_Delete extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Delete');
        $this->addElement($submit);
    }
}