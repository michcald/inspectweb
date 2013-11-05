<?php

class Lib_Mein_Validate_Ip extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert a valid IP');
    }
    
    public function validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP);
    }
}
