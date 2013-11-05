<?php

class Lib_Mein_Form_Element_Select extends Lib_Mein_Form_Element_Abstract
{
    private $options = array();
    
    public function addOption($value, $name)
    {
        $this->options[$name] = $value;
        return $this;
    }
    
    protected function toString()
    {
        $str = "\t\t<select" . $this->getAttributesString() . ">\n";
        
        foreach($this->options as $name => $value)
        {
            $str .= "\t\t\t<option value=\"$value\"";
            
            if($this->getValue() == $value) {
                $str .= " selected";
            }
            
            $str .= ">$name</option>\n";
        }
        
        $str .= "\t\t</select>\n";
        
        return $str;
    }
}
