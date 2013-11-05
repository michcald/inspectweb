<?php

class App_Instructor_Form_Themes_Add extends Lib_Mein_Form
{
    public function __construct()
    {
        $this->setMethod('post');
        $this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add'));
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')->setRequired();
        $this->addElement($name);
        
        $descr = new Lib_Mein_Form_Element_Textarea('description');
        $descr->setLabel('Description');
        $this->addElement($descr);
        
        $steps = new Lib_Mein_Form_Element_Select('steps');
        $steps->setLabel('Number of steps')
                ->setIgnored()
                ->setRequired();
        for($i=1 ; $i<=20 ; $i++) {
            $steps->addOption($i, $i);
        }
        $this->addElement($steps);
        
        $library = new Lib_Mein_Form_Element_Select('library');
        $library->setLabel('Library')
                ->setIgnored()
                ->setRequired();
        $library->addOption(0, 'Default');
        foreach(Lib_Registry::get('db')->fetchAll('SELECT * FROM themes_libraries') as $l) {
            $library->addOption($l['id'], $l['name']);
        }
        $this->addElement($library);
        
        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
    }
}