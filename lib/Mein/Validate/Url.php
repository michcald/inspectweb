<?php

class Lib_Mein_Validate_Url extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert a valid URL');
    }
    
    public function validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL);
    }
}
