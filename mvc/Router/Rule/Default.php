<?php

class Mvc_Router_Rule_Default extends Mvc_Router_Rule
{
    public function __construct()
    {
        $this->params = array_merge($_GET, $_POST);
    }
    
    public final function match($string)
    {
        return true;
    }
}