<?php

class Lib_Mein_Filter_StringToLower extends Lib_Mein_Filter_Abstract
{
    public function filter($value)
    {
        return strtolower($value);
    }
}