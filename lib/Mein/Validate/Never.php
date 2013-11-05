<?php

/**
 * Serve per creare un errore generico, in pratica è un validator che ritorna sempre false
 */
class Lib_Mein_Validate_Never extends Lib_Mein_Validate_Abstract
{
    public function validate($value)
    {
        return false;
    }
}