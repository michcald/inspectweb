<?php

class Lib_Mein_Validate_Alpha extends Lib_Mein_Validate_Abstract
{
    private $allowWhiteSpace = null;
    
    public function __construct($allowWhiteSpace = true)
    {
        $this->allowWhiteSpace = $allowWhiteSpace;
        
        if($this->allowWhiteSpace) {
            $this->setErrorMessage('Insert a string with only letters or blank spaces');
        } else {
            $this->setErrorMessage('Insert a string with only letters (no blank spaces)');
        }
    }
    
    public function validate($value)
    {
        if($this->allowWhiteSpace) {
            return preg_match("/^[a-zA-Z\s]*$/" , $value);
        }
        
        return preg_match("/^[a-zA-Z]*$/" , $value);
    }
}
