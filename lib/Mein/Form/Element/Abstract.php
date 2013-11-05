<?php

abstract class Lib_Mein_Form_Element_Abstract
{
    protected $label = null;
    
    protected $attributes = array();
    
    protected $description = null;
    
    private $ignored = false;
    
    private $required = false;
    
    private $validators = array();
    
    private $errorValidatorIndex = null;
    
    private $filters = array();
    
    private $value = null;
    
    public function __construct($name)
    {
        $this->setAttribute('id', $name);
        $this->setAttribute('name', $name);
    }
    
    public function getName()
    {
        return $this->attributes['name'];
    }
    
    /**
     *
     * @param type $label
     * @return Mein_Form_Element_Abstract 
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    
    /**
     *
     * @param type $attribute
     * @param type $value
     * @return Mein_Form_Element_Abstract 
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }
    
    /**
     *
     * @param type $description
     * @return Mein_Form_Element_Abstract 
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     *
     * @return Mein_Form_Element_Abstract 
     */
    public function setRequired()
    {
        $this->required = true;
        array_unshift($this->validators, new Lib_Mein_Validate_NotEmpty());
        return $this;
    }
    
    /**
     *
     * @return Mein_Form_Element_Abstract 
     */
    public function setIgnored()
    {
        $this->ignored = true;
        return $this;
    }
    
    /**
     *
     * @return type 
     */
    public function isIgnored()
    {
        return $this->ignored;
    }
    
    /**
     *
     * @param Mein_Validate_Abstract $validator
     * @return Mein_Form_Element_Abstract 
     */
    public function addValidator(Lib_Mein_Validate_Abstract $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }
    
    public function isValid()
    {
        if(!is_numeric($this->getValue()) && $this->getValue() == '')
        {
            if(!$this->required) {
                return true;
            }
            else
            {
                $this->errorValidatorIndex = 0;
                return false;
            }
        }
        
        for($i=0;$i<count($this->validators);$i++)
        {
            $isValid = $this->validators[$i]->validate($this->getValue());
            
            if(!$isValid)
            {
                $this->errorValidatorIndex = $i;
                return false;
            }
        }
        
        return true;
    }
    
    /**
     *
     * @param Mein_Filter_Abstract $filter
     * @return Mein_Form_Element_Abstract 
     */
    public function addFilter(Lib_Mein_Filter_Abstract $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }
    
    /**
     *
     * @param type $value
     * @return Mein_Form_Element_Abstract 
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function getValue()
    {
        $value = $this->value;
        
        foreach($this->filters as $filter) {
            $value = $filter->filter($value);
        }
        
        return $value;
    }
    
    protected function getAttributesString()
    {
        $str = '';
        foreach($this->attributes as $attribute => $value) {
            $str .= " $attribute=\"$value\"";
        }
        return $str;
    }
    
    abstract protected function toString();
    
    public final function __toString()
    {
        if($this instanceof Lib_Mein_Form_Element_Hidden) {
            return $this->toString();
        }
        
        $str = array();
        
        $str[] = "<div class=\"mein-form-element\">";
        
        if($this->label !== null) {
            $str[] = "\t<div class=\"mein-form-element-label" . (($this->required) ? " mein-form-element-required" : "") . "\">{$this->label}</div>";
        }
        
        $str[] = "\t<div class=\"mein-form-element-input\">";
        
        $str[] = $this->toString();
        
        if($this->description !== null) {
            $str[] = "\t\t<div class=\"mein-form-element-description\">$this->description</div>";
        }
        
        if($this->errorValidatorIndex !== null) {
            $str[] = "\t\t<div class=\"mein-form-element-error\">" . $this->validators[$this->errorValidatorIndex]->getErrorMessage() . "</div>";
        }
        
        $str[] = "\t</div>";
        
        $str[] = "</div>";
        
        $str[] = "<div class=\"mein-form-element-separator\"></div>";
        
        return implode("\n", $str);
    }
}