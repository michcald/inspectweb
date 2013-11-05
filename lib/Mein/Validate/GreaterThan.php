<?php

class Lib_Mein_Validate_GreaterThan extends Lib_Mein_Validate_Abstract
{
    private $min = null;
    
    public function __construct($min)
    {
        $this->min = $min;
        
        $this->setErrorMessage("Insert a value bigger than $min");
    }
    
    public function validate($value)
    {
        return $value > $this->min;
    }
}