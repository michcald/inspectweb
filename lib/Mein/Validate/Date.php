<?php

class Lib_Mein_Validate_Date extends Lib_Mein_Validate_Abstract
{
    public function __construct()
    {
        $this->setErrorMessage('Insert a valid date');
    }
    
    public function validate($value)
    {
        $time = strtotime($value);
        
        $day = (int)date('d', $time);
        $month = (int)date('m', $time);
        $year = (int)date('Y', $time);
        
        return checkdate($month, $day, $year);
    }
}
