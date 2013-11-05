<?php

class Lib_Mein_Validate_Integer extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert an integer number');
    }
    
    public function validate($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}
