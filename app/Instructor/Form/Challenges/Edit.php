<?php

class App_Instructor_Form_Challenges_Edit extends Lib_Mein_Form
{
    public function __construct($challenge)
    {
        $this->setMethod('post');
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setRequired()
                ->setValue($challenge['name']);
        $this->addElement($name);
        
        $start = new Lib_Mein_Form_Element_DateTime('start');
        $start->setLabel('Start time')
                ->setAttribute('style', 'width: auto')
                ->setRequired()
                ->setDay(date('d', strtotime($challenge['start'])))
                ->setMonth(date('m', strtotime($challenge['start'])))
                ->setYear(date('Y', strtotime($challenge['start'])))
                ->setHour(date('H', strtotime($challenge['start'])))
                ->setMinute(date('i', strtotime($challenge['start'])))
                ->setSecond(date('s', strtotime($challenge['start'])));
        $this->addElement($start);
        
        $end = new Lib_Mein_Form_Element_DateTime('end');
        $end->setLabel('End time')
                ->setAttribute('style', 'width: auto')
                ->setRequired()
                ->setDay(date('d', strtotime($challenge['end'])))
                ->setMonth(date('m', strtotime($challenge['end'])))
                ->setYear(date('Y', strtotime($challenge['end'])))
                ->setHour(date('H', strtotime($challenge['end'])))
                ->setMinute(date('i', strtotime($challenge['end'])))
                ->setSecond(date('s', strtotime($challenge['end'])));
        $this->addElement($end);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}