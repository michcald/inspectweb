<?php

class Mvc_Exception_Handler
{
    public static function devMode($e)
    {
        if($e->getCode() == 404)
        {
            header('HTTP/1.0 404 Not Found');
            echo "<h1>404 Not Found</h1>";
            echo "<p>The page that you have requested could not be found.</p>";
        }
        
        echo $e->getMessage() . '<br /><br />' . nl2br($e->getTraceAsString());
    }
    
    public static function prodMode($e)
    {
        if($e->getCode() == 404)
        {
            header("Location: " . $_SERVER['PHP_SELF']);
        }
        
        // write something into a log file?
    }
}