<?php

abstract class Lib_Mein_Validate_Abstract
{
    private $errorMessage = '';
    
    abstract public function validate($value);
    
    public function setErrorMessage($error)
    {
        $this->errorMessage = $error;
    }
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}