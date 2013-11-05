<?php

class App_Admin_Form_Accounts_Add extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        
        $level = new Lib_Mein_Form_Element_Select('user-level');
        $level->setLabel('User level')
                ->setRequired()
                ->addOption(0, 'Student')
                ->addOption(100, 'Instructor');
        $this->addElement($level);
        
        $name = new Lib_Mein_Form_Element_Text('first-name');
        $name->setLabel('First name')->setRequired();
        $this->addElement($name);
        
        $surname = new Lib_Mein_Form_Element_Text('last-name');
        $surname->setLabel('Last name')->setRequired();
        $this->addElement($surname);
        
        $email = new Lib_Mein_Form_Element_Email('email');
        $email->setLabel('Email')->setRequired();
        $this->addElement($email);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
    }
    
    public function isValid()
    {
        $res = parent::isValid();
        
        if(!$res) {
            return false;
        }
        
        $users = App_Instructor_Model_Users::getAll();
        
        $exit = false;
        
        foreach($users as $u)
        {
            // verify if the first|last name is already used
            if($u->FirstName == $this->getValue('first-name') && $u->LastName == $this->getValue('last-name'))
            {
                $elem = $this->getElement('first-name');
                $val = new Lib_Mein_Validate_Never();
                $val->setErrorMessage('First and last name already used!');
                $elem->addValidator($val);
                $elem->isValid();
                
                $exit = true;
            }
            
            // verify if the email is already used
            if($u->Email == $this->getValue('email'))
            {
                $elem = $this->getElement('email');
                $val = new Lib_Mein_Validate_Never();
                $val->setErrorMessage('Email already used!');
                $elem->addValidator($val);
                $elem->isValid();
                
                $exit = true;
            }
            
            if($exit) {
                return false;
            }
        }
        
        return true;
    }
}