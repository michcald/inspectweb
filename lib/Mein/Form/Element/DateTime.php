<?php

class Lib_Mein_Form_Element_DateTime extends Lib_Mein_Form_Element_Abstract
{
    public function __construct($name)
    {
        parent::__construct($name);
    }
    
    public function setValue($value)
    {
        $this->setYear($value[2]);
        $this->setMonth($value[1]);
        $this->setDay($value[0]);
        
        $this->setHour($value[3]);
        $this->setMinute($value[4]);
        $this->setSecond($value[5]);
    }
    
    public function setDay($day)
    {
        $this->value[2] = $day;
        return $this;
    }
    
    public function getDay()
    {
        return $this->value[2];
    }
    
    public function setMonth($month)
    {
        $this->value[1] = $month;
        return $this;
    }
    
    public function getMonth()
    {
        return $this->value[1];
    }
    
    public function setYear($year)
    {
        $this->value[0] = $year;
        return $this;
    }
    
    public function getYear()
    {
        return $this->value[0];
    }
    
    public function setHour($hour)
    {
        $this->value[3] = $hour;
        return $this;
    }
    
    public function getHour()
    {
        return $this->value[3];
    }
    
    public function setMinute($minute)
    {
        $this->value[4] = $minute;
        return $this;
    }
    
    public function getMinute()
    {
        return $this->value[4];
    }
    
    public function setSecond($second)
    {
        $this->value[5] = $second;
        return $this;
    }
    
    public function getSecond()
    {
        return $this->value[5];
    }
    
    public function getValue()
    {
        return date('Y-m-d, H:i:s', strtotime($this->getDay().'-'.$this->getMonth().'-'.$this->getYear().' '.$this->getHour().':'.$this->getMinute().':'.$this->getSecond()));
    }
    
    public function toString()
    {
        for($i=1970 ; $i<=2050 ; $i++) {
            $yearParams[$i] = $i;
        }

        for($i=1 ; $i<=12 ; $i++)
        {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            $monthParams[$key] = $key;
        }

        for($i=1 ; $i<=31 ; $i++)
        {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            $dayParams[$key] = $key;
        }
        
        for($i=0 ; $i<24 ; $i++)
        {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            $hourParams[$key] = $key;
        }
        
        for($i=0 ; $i<=59 ; $i++)
        {
            $key = str_pad($i, 2, "0", STR_PAD_LEFT);
            $minuteParams[$key] = $key;
        }
        
        $str = '';
        
        $str .= $this->toStringSelect($dayParams, $this->getDay());
        $str .= '/';
        $str .= $this->toStringSelect($monthParams, $this->getMonth());
        $str .= '/';
        $str .= $this->toStringSelect($yearParams, $this->getYear());
        $str .= ', ';
        $str .= $this->toStringSelect($hourParams, $this->getHour());
        $str .= ':';
        $str .= $this->toStringSelect($minuteParams, $this->getMinute());
        $str .= ':';
        $str .= $this->toStringSelect($minuteParams, $this->getSecond());
        
        return $str;
    }
    
    private function toStringSelect(array $options, $selected)
    {
        $attributes = $this->attributes;
        
        $attributes['name'] = "{$this->getName()}[]";
        //$attributes['id'] = $attributes['id'];
        
        $str = "\t\t<select";
        foreach($attributes as $attribute => $value) {
            $str .= " $attribute=\"$value\"";
        }
        $str .= ">\n";
        
        foreach($options as $name => $value)
        {
            $str .= "\t\t\t<option value=\"$value\"";
            
            if($selected == (int)$value) {
                $str .= " selected";
            }
            
            $str .= ">$name</option>\n";
        }
        
        return $str .= "\t\t</select>\n";
    }
}
