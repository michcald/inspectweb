<?php

class Lib_Mein_Validate_Email extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert a valid email address');
    }
    
    public function validate($value)
    {
        return preg_match("/^[a-z0-9][_.a-z0-9-]+@([a-z0-9][0-9a-z-]+.)+([a-z]{2,4})/" , $value);
    }
}
