<?php

class Lib_Mein_Filter_StripTags extends Lib_Mein_Filter_Abstract
{
    public function filter($value)
    {
        return strip_tags($value);
    }
}