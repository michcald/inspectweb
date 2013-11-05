<?php

class Lib_Mein_Validate_File_Extension extends Lib_Mein_Validate_Abstract
{
    private $extensions = array();
    
    public function __construct(array $extensions)
    {
        foreach($extensions as $ext) {
            $this->extensions[] = strtolower($ext);
        }
        
        $this->setErrorMessage('The file has to have one of these extensions: '.implode(', ', $extensions));
    }
    
    public function validate($filePath)
    {
        $pathInfo = pathinfo($filePath);
        
        return in_array(strtolower($pathInfo['extension']), $this->extensions);
    }
}