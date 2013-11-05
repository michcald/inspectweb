<?php

abstract class Mvc_Application_Autoloader
{
    public static function autoload($className)
    {
        $pathInfo = pathinfo($_SERVER['PHP_SELF']);
        $appDir = realpath($_SERVER['DOCUMENT_ROOT'] . $pathInfo['dirname']);
        
        $chunks = explode('_', $className);
        if(count($chunks) > 0) {
            $chunks[0][0] = strtolower($chunks[0][0]);
        }
        $filePath = implode('/', $chunks);
        
        $filePath = "$appDir/$filePath.php";
    
        if(is_file($filePath))
        {
            include $filePath;
            return;
        }
        
        throw new Exception("File $filePath not found for class $className", 404);
    }
}