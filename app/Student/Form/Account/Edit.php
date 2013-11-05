<?php

class App_Student_Form_Account_Edit extends Lib_Mein_Form
{
    private $user = null;
    
    public function __construct($user)
    {
        $this->user = $user;
        
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('first-name');
        $name->setLabel('First name')
                ->setRequired()
                ->setValue($user->FirstName);
        $this->addElement($name);
        
        $surname = new Lib_Mein_Form_Element_Text('last-name');
        $surname->setLabel('Last name')
                ->setRequired()
                ->setValue($user->LastName);
        $this->addElement($surname);
        
        $email = new Lib_Mein_Form_Element_Email('email');
        $email->setLabel('Email')
                ->setRequired()
                ->setValue($user->Email);
        $this->addElement($email);
        
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
        
        $users = App_Instructor_Model_Users::getAll();
        
        $exit = false;
        
        foreach($users as $u)
        {
            if($u->UUID == $this->user->UUID) {
                continue;
            }
            
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