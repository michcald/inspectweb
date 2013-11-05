<?php

class Mvc_Response
{
    private static $instance = null;
    
    private $content = '';
    
    public function __construct()
    {
        
    }
    
    /**
     *
     * @return Mvc_Response
     */
    public final static function getInstance()
    {
        if(self::$instance === null)
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        
        return self::$instance;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function setContentType($type)
    {
        header("Content-Type: $type;");
    }
    
    public function setLocation($location)
    {
        header("Location: $location");
    }
    
    public function setDownload($filename)
    {
        // Headers for an download:
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"$filename\""); 
        header('Content-Transfer-Encoding: binary');
    }
    
    public function setNoCache()
    {
        // Disable caching of the current document:
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1 
        header("Cache-Control: post-check=0, pre-check=0", false); 
        header('Pragma: no-cache');
    }
    
    public function __toString()
    {
        return ($this->content) ? $this->compress($this->content) : '';
    }
    
    private function compress($buffer)
    {
        $replace = array(
            "#<!--.*?-->#s" => "",      // strip comments
            "#>\s+<#"       => "><",  // strip excess whitespace
            "#\n\s+<#"      => "<"    // strip excess whitespace
        );
        
        $search = array_keys($replace);
        $html = preg_replace($search, $replace, $buffer);
        return trim($html);
    }
}