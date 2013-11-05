<?php

class Lib_Mein_Validate_NotEmpty extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Field required');
    }
    
    public function validate($value)
    {
        return $value != '';
    }
}
