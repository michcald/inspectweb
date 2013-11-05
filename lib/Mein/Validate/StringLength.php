<?php

class Lib_Mein_Validate_StringLength extends Lib_Mein_Validate_Abstract
{
    private $length = null;
    
    private $operator = '==';
    
    public function __construct($length, $operator = '==')
    {
        $this->length = (int)$length;
        
        if(!in_array($operator, array('==', '<=', '>=', '<', '>'))) {
            throw new Exception("Mein_Validate_StringLength::__construct() invalid operator $operator");
        }
        
        if($operator == '<') {
            $error = "The string has to have less than " . $length . " chars";
        } elseif($operator == '<=') {
            $error = "The string has to have maximum $length chars";
        } elseif($operator == '>') {
            $error = "The string has to have at least " . $length+1 . " chars";
        } elseif($operator == '>=') {
            $error = "The string has to have at least $length chars";
        } else {
            $error = "The string has to have $length chars";
        }
        
        $this->setErrorMessage($error);
        
        $this->operator = $operator;
    }
    
    public function validate($value)
    {
        return eval("return strlen(\$value) {$this->operator} {$this->length};");
    }
}
