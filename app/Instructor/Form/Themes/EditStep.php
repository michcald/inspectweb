<?php

class App_Instructor_Form_Themes_EditStep extends Lib_Mein_Form
{
    public function __construct($step)
    {
        $this->setMethod('post');
        //$this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add-step'));
        
        $index = new Lib_Mein_Form_Element_Select('position');
        $index->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_steps WHERE idtheme={$step['idtheme']} AND position!={$step['position']}");
        for($i=1;$i<=100;$i++)
        {
            if(array_search($i, $takenIndexes)) continue;
            $index->addOption($i, $i);
        }
        $index->setValue($step['position']);
        $this->addElement($index);
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')
                ->setRequired()
                ->setValue($step['name']);
        $this->addElement($name);
        
        $img = new Lib_Mein_Form_Element_File('img');
        $img->setLabel('Screenshot')
                ->setDescription('Images formats: jpg, png, gif')
                ->setIgnored()
                ->addValidator(new Lib_Mein_Validate_File_Extension(array('jpg','jpeg','png','gif')))
                ->setDestination('pub/tmp');
        $this->addElement($img);

        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Edit')->setIgnored();
        $this->addElement($submit);
    }
}