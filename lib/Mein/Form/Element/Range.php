<?php

class Lib_Mein_Form_Element_Range extends Lib_Mein_Form_Element_Abstract
{
    private $min = null;
    
    private $max = null;
    
    private $step = 1;
    
    public function __construct($name, $min, $max, $step = 1)
    {
        parent::__construct($name);
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }
    
    protected function toString()
    {
        $str = "\t\t<select" . $this->getAttributesString() . ">\n";
        
        for($i=$this->min ; $i<=$this->max ; $i+=$this->step)
        {
            $str .= "\t\t\t<option value=\"$i\"";
            
            if($this->getValue() == $i) {
                $str .= " selected";
            }
            
            $str .= ">$i</option>\n";
        }
        
        $str .= "\t\t</select>\n";
        
        return $str;
    }
}
