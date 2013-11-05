<?php

class Lib_Mein_Validate_Float extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert a real number');
    }
    
    public function validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
}
