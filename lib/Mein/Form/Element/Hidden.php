<?php

class Lib_Mein_Form_Element_Hidden extends Lib_Mein_Form_Element_Abstract
{
    protected function toString()
    {
        return "\t\t<input type=\"hidden\" value=\"{$this->getValue()}\"" . $this->getAttributesString() . " />";
    }
}
