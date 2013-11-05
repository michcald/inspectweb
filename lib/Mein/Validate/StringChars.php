<?php

class Lib_Mein_Validate_StringChars extends Lib_Mein_Validate_Abstract
{
    private $avaliableChars = array();
    
    public function __construct($avaliableChars = "abcdefghijklmnopqrstuvwxyz0123456789_")
    {
        $this->setErrorMessage("Avaliable chars: $avaliableChars");
        
        for($i=0 ; $i<strlen($avaliableChars) ; $i++) {
            $this->avaliableChars[] = $avaliableChars[$i];
        }
    }
    
    public function validate($value)
    {
        for($i=0 ; $i<strlen($value) ; $i++)
        {
            if(!in_array($value[$i], $this->avaliableChars)) {
                return false;
            }
        }
        
        return true;
    }
}
