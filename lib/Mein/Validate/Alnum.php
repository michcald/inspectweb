<?php

class Lib_Mein_Validate_Alnum extends Lib_Mein_Validate_Abstract
{
    private $allowWhiteSpace = null;
    
    public function __construct($allowWhiteSpace = true)
    {
        $this->allowWhiteSpace = $allowWhiteSpace;
        
        if($this->allowWhiteSpace) {
            $this->setErrorMessage('Insert a string with only letters, numbers or white spaces');
        } else {
            $this->setErrorMessage('Insert a string with only letters or numbers');
        }
    }
    
    public function validate($value)
    {
        if($this->allowWhiteSpace) {
            return preg_match("/^[a-zA-Z0-9\s]*$/" , $value);
        }
        
        return preg_match("/^[a-zA-Z0-9]*$/" , $value);
    }
}