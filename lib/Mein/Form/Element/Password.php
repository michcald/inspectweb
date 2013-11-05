<?php

class Lib_Mein_Form_Element_Password extends Lib_Mein_Form_Element_Abstract
{
    protected function toString()
    {
        return "\t\t<input type=\"password\" value=\"{$this->getValue()}\"" . $this->getAttributesString() . " />";
    }
}
