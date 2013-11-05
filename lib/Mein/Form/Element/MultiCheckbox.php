<?php

class Lib_Mein_Form_Element_MultiCheckbox extends Lib_Mein_Form_Element_Abstract
{
    private $options = array();
    
    public function __construct($name)
    {
        parent::__construct($name.'[]');
    }
    
    public function addOption($label, $value)
    {
        $this->options[] = array(
            'label' => $label,
            'value' => $value
        );
    }
    
    protected function toString()
    {
        $insertedValues = ($this->getValue()) ? $this->getValue() : array();
        
        $str = '';
        for($i=0 ; $i<count($this->options) ; $i++)
        {
            $value = $this->options[$i]['value'];
            $label = $this->options[$i]['label'];
            
            $str .= "<input type=\"checkbox\" value=\"$value\"" . $this->getAttributesString();
            
            foreach($insertedValues as $v)
            {
                if($value == $v) {
                    $str .= " checked=\"checked\"";
                }
            }
            $str .= " /> $label<br />";
        }
        
        return $str;
    }
}