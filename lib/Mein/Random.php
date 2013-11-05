<?php

class Lib_Mein_Random
{
    public static function string($length = 5, $caps = true, $num = true, $special = true)
    {
        $ncaps = "abcdefghilmnopqrstuvzxwkjy";
        $dcaps = "ABCDEFGHILMNOPQRSTUVZXWKJY";
        $dnum = "1234567890";
        $dspecial = "!£$%&/()=?^;:_,.-@#][{}";

        $dict = $ncaps;
        if($caps) {
            $dict .= $dcaps;
        }
        if($num) {
            $dict .= $dnum;
        }
        if($special) {
            $dict .= $dspecial;
        }

        $pgen = str_shuffle($dict);

        return substr($pgen, 0, $length);
    }
    
    public static function integer($min, $max)
    {
        return rand($min, $max);
    }
}