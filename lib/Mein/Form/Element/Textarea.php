<?php

class Lib_Mein_Form_Element_Textarea extends Lib_Mein_Form_Element_Abstract
{
    protected function toString()
    {
        return "\t\t<textarea" . $this->getAttributesString() . ">" . $this->getValue() . "</textarea>";
    }
}