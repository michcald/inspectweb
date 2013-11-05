<?php

class Lib_Mein_Filter_StringTrim extends Lib_Mein_Filter_Abstract
{
    public function filter($value)
    {
        return trim($value);
    }
}