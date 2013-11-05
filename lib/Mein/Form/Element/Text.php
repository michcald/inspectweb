<?php

class Lib_Mein_Form_Element_Text extends Lib_Mein_Form_Element_Abstract
{
    protected function toString()
    {
        return "\t\t<input type=\"text\" value=\"{$this->getValue()}\"" . $this->getAttributesString() . " />";
    }
}
