<?php

class Lib_Mein_Filter_Integer extends Lib_Mein_Filter_Abstract
{
    public function filter($value)
    {
        return (int)$value;
    }
}