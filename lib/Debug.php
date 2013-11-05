<?php

class Lib_Debug
{
    public static function dump($var, $echo = true)
    {
        $output = '<pre>' . print_r($var, true) . '</pre>';

        if($echo) {
            echo $output;
        }
        
        return $output;
    }

}
