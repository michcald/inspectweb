<?php

class Lib_Mein_Form_Element_Url extends Lib_Mein_Form_Element_Text
{
    public function __construct($name)
    {
        parent::__construct($name);
        
        $this->addValidator(new Lib_Mein_Validate_Url());
        $this->addFilter(new Lib_Mein_Filter_StringTrim());
    }
}
