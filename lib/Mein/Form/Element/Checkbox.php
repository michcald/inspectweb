<?php

class Lib_Mein_Form_Element_Checkbox extends Lib_Mein_Form_Element_Abstract
{
    private $checkedValue = null;
    
    public function setChecked()
    {
        $this->setAttribute('checked', 'checked');
    }
    
    /**
     *
     * @param type $value
     * @return Mein_Form_Element_Checkbox 
     */
    public function setCheckedValue($value)
    {
        $this->checkedValue = $value;
        return $this;
    }
    
    public function getValue()
    {
        return ($this->isChecked()) ? $this->checkedValue : parent::getValue();
    }
    
    public function isChecked()
    {
        $data = (isset($_POST)) ? $_POST : $_GET;
        return isset($data[$this->getName()]);
    }
    
    protected function toString()
    {
        return "\t\t<input type=\"checkbox\" value=\"" . $this->getValue() . "\"" . $this->getAttributesString() . " />";
    }
}