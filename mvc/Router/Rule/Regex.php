<?php

class Mvc_Router_Rule_Regex extends Mvc_Router_Rule
{
    private $pattern = null;
    
    public function __construct($pattern, array $params = array())
    {
        $this->pattern = $pattern;
        
        $this->params = $params;
    }
    
    public function match($string)
    {
        $string = preg_replace("#\s#", '', $string); // elimino eventuali spazi bianchi
        $string = preg_replace('#(^\/)|(\/$)#', '', $string); // elimino il primo / e l'ultimo
        
        // sostituisco le parti variabili con "qualsiasi cosa"
        $regex = preg_replace("#:[a-zA-Z0-9\-]*#", '.*', $this->pattern);
        
        if(preg_match("#$regex#", $string))
        {
            $values = explode('/', $string);
            $keys = explode('/', $this->pattern);
            
            for($i=0 ; $i<count($values) ; $i++)
            {
                $k = $keys[$i];
                if($k[0] != ':') continue;
                $this->params[str_replace(':', '', $k)] = $values[$i];
            }
            
            return true;
        }
        
        return false;
    }
}