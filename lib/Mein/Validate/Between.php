<?php

class Lib_Mein_Validate_Between extends Lib_Mein_Validate_Abstract
{
    private $min = null;
    
    private $max = null;
    
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
        
        $this->setErrorMessage("Insert a value between $min and $max");
    }
    
    public function validate($value)
    {
        return ($value > $this->min && $value < $this->max);
    }
}
