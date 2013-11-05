<?php

class Lib_Mein_Form
{
    private $action = '';
    
    private $method = 'post';
    
    private $attributes = array('class' => 'mein-form');
    
    private $elements = array();
    
    /**
     *
     * @param type $data
     * @return Mein_Form 
     */
    public function setAction($data = array())
    {
        $this->action = $_SERVER['PHP_SELF'];
        
        if(count($data) > 0) {
            $this->action .= '?' . http_build_query($data);
        }
        
        return $this;
    }
    
    /**
     *
     * @param type $method
     * @return Mein_Form 
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
    
    /**
     *
     * @param type $attribute
     * @param type $value
     * @return Mein_Form 
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }
    
    /**
     *
     * @param Mein_Form_Element_Abstract $element
     * @return Mein_Form 
     */
    public function addElement(Lib_Mein_Form_Element_Abstract $element)
    {
        if($element instanceof Lib_Mein_Form_Element_File) {
            $this->setAttribute('enctype', 'multipart/form-data');
        }
        
        $data = (strtolower($this->method) == 'post') ? $_POST : $_GET;
        
        $name = $element->getName();
        
        $name = str_replace('[]', '', $name);
        
        if(array_key_exists($name, $data)) {
            $element->setValue($data[$name]);
        }
        
        $this->elements[$element->getName()] = $element;
        
        return $this;
    }
    
    /**
     *
     * @param type $name
     * @return Lib_Mein_Form_Element_Abstract
     */
    public function getElement($name)
    {
        if(!array_key_exists($name, $this->elements))
        {
            throw new Exception("Mein_Form::getElement() Element $name not found");
            return;
        }
        
        return $this->elements[$name];
    }
    
    public function isSubmitted()
    {
        if(strtolower($this->method) == 'post') {
            return strtolower($_SERVER['REQUEST_METHOD']) == 'post';;
        }
        
        return true;
    }
    
    public function isValid()
    {
        $res = true;
        
        foreach($this->elements as $element)
        {
            if(!$element->isValid()) {
                $res = false;
            }
        }
        
        if(!$res) // elimino i file che sono stati caricati ma per colpa di un element successivo il form fallisce
        {
            foreach($this->elements as $element)
            {
                if($element instanceof Lib_Mein_Form_Element_File && $element->isValid() && is_file($element->getValue())) {
                    unlink($element->getValue());
                }
            }
        }
        
        return $res;
    }
    
    public function getValue($name)
    {
        foreach($this->elements as $element)
        {
            if($element->getName() == $name) {
                return $element->getValue();
            }
        }
        
        return null;
    }
    
    public function getValues()
    {
        $values = array();
        
        foreach($this->elements as $element)
        {
            if(!$element->isIgnored()) {
                $values[$element->getName()] = $element->getValue();
            }
        }
        
        return $values;
    }
    
    public final function __toString()
    {
//        if(!$this->action)
//        {
//            $this->action = $_SERVER['REQUEST_URI'];
//            if($_SERVER['QUERY_STRING']) {
//                $this->action .= '?' . $_SERVER['QUERY_STRING'];
//            }
//        }
        
        if(count($this->elements) == 0) {
            return '';
        }
        
        $str = "<form action=\"{$this->action}\" method=\"{$this->method}\"";
        
        foreach($this->attributes as $attribute => $value) {
            $str .= " $attribute=\"$value\"";
        }
        
        $str .= ">\n";
        
        foreach($this->elements as $element) {
            $str .= $element . "\n";
        }
        
        $str .= "</form>\n";
        
        return $str;
    }
}