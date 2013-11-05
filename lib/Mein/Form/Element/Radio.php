<?php

class Lib_Mein_Form_Element_Radio extends Lib_Mein_Form_Element_Abstract
{
    private $options = array();
    
    public function addOption($value, $label)
    {
        $this->options[$label] = $value;
        return $this;
    }
    
    protected function toString()
    {
        $str = '';
        foreach($this->options as $label => $value)
        {
            $str .= "<input type=\"radio\" value=\"$value\" {$this->getAttributesString()}";
            
            if($this->getValue() == $value) {
                $str .= " checked";
            }
            
            $str .= "/> $label<br />\n";
        }
        
        return $str;
    }
}
