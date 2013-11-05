<?php

class Lib_Mein_Validate_LessThan extends Lib_Mein_Validate_Abstract
{
    private $max = null;
    
    public function __construct($max)
    {
        $this->max = $max;
        
        $this->setErrorMessage("Insert a value less than $max");
    }
    
    public function validate($value)
    {
        return $value < $this->max;
    }
}
