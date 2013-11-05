<?php

class Lib_Mein_Filter_StringToUpper extends Lib_Mein_Filter_Abstract
{
    public function filter($value)
    {
        return strtoupper($value);
    }
}