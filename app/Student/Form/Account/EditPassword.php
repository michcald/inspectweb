<?php

class App_Student_Form_Account_EditPassword extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $pass1 = new Lib_Mein_Form_Element_Password('pass1');
        $pass1->setLabel('Password')
                ->setRequired()
                ->addValidator(new Lib_Mein_Validate_StringLength(5, ">="));
        $this->addElement($pass1);
        
        $pass2 = new Lib_Mein_Form_Element_Password('pass2');
        $pass2->setLabel('Retype Password')->setRequired();
        $this->addElement($pass2);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
    
    public function isValid()
    {
        $res = parent::isValid();
        
        if(!$res) {
            return false;
        }
        
        $pass1 = $this->getValue('pass1');
        $pass2 = $this->getValue('pass2');
        
        if($pass1 != $pass2)
        {
            $val = new Lib_Mein_Validate_Never();
            $val->setErrorMessage('The password does not match');
            $this->getElement('pass2')->addValidator($val);
            $this->getElement('pass2')->isValid();
            return false;
        }
        
        return true;
    }
}