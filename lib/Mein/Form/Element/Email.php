<?php

class Lib_Mein_Form_Element_Email extends Lib_Mein_Form_Element_Text
{
    public function __construct($name)
    {
        parent::__construct($name);
        
        $this->addValidator(new Lib_Mein_Validate_Email());
        $this->addFilter(new Lib_Mein_Filter_StringTrim());
    }
}
