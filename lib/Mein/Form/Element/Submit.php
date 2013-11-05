<?php

class Lib_Mein_Form_Element_Submit extends Lib_Mein_Form_Element_Abstract
{
    protected function toString()
    {
        return "\t\t<input type=\"submit\" value=\"{$this->getValue()}\"" . $this->getAttributesString() . " />";
    }
}