<?php

class Lib_Mein_Validate_File_Size extends Lib_Mein_Validate_Abstract
{
    private $size = 0;
    
    public function __construct($size)
    {
        $this->size = $size; // in byte
        
        $this->setErrorMessage("The file size has to be less than {$this->size} byte");
    }
    
    public function validate($filename)
    {
        return filesize($filename) <= $this->size;
    }
}