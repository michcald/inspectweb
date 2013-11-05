<?php

class Lib_Mein_Form_Element_File extends Lib_Mein_Form_Element_Abstract
{
    private $destinationDir = '';
    
    private $filename = '';
    
    public function __construct($name)
    {
        parent::__construct($name);
    }
    
    public function setDestination($destinationDir)
    {
        if(!is_dir($destinationDir)) {
            throw new Exception("$destinationDir is not a directory");
        }
        
        if($destinationDir[strlen($destinationDir)-1] == '/') {
            $destinationDir = substr($destinationDir, 0, strlen($destinationDir)-2);
        }
        
        $this->destinationDir = $destinationDir;
        
        return $this;
    }
    
    public function getValue()
    {
        if(isset($_FILES[$this->getName()]) && !$this->filename && is_file($_FILES[$this->getName()]["tmp_name"]))
        {
            if($this->destinationDir) {
                $fileName = $this->destinationDir . '/' . $_FILES[$this->getName()]["name"];
            } else {
                $fileName = $_FILES[$this->getName()]["name"];
            }
            
            move_uploaded_file($_FILES[$this->getName()]["tmp_name"], $fileName);
            
            $this->filename = $fileName;
        }
        
        return $this->filename;
    }
    
    public function isValid()
    {
        if(!parent::isValid())
        {
            if(is_file($this->getValue())) {
                @unlink($this->getValue());
            }
            return false;
        }
        
        return true;
    }
    
    protected function toString()
    {
        return "\t\t<input type=\"file\" " . $this->getAttributesString() . " />";
    }
}
