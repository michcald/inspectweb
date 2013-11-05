<?php

class App_Instructor_Form_Themes_AddStep extends Lib_Mein_Form
{
    public function __construct($theme)
    {
        $this->setMethod('post');
        $this->setAction(array('m'=>'instructor','c'=>'themes','a'=>'add-step'));
        
        $index = new Lib_Mein_Form_Element_Select('position');
        $index->setLabel('Index')->setRequired();
        $takenIndexes = Lib_Registry::get('db')->fetchCol("SELECT DISTINCT(position) FROM themes_steps WHERE idtheme={$theme['id']}");
        for($i=1;$i<=100;$i++)
        {
            if(array_search($i, $takenIndexes) !== false) continue;
            $index->addOption($i, $i);
        }
        $index->setValue(Lib_Registry::get('db')->fetchOne("SELECT MAX(position) FROM themes_steps WHERE idtheme={$theme['id']}") + 1);
        $this->addElement($index);
        
        $name = new Lib_Mein_Form_Element_Text('name');
        $name->setLabel('Name')->setRequired();
        $this->addElement($name);
        
        $img = new Lib_Mein_Form_Element_File('img');
        $img->setLabel('Screenshot')
                ->setDescription('Images formats: jpg, png, gif')
                ->setIgnored()
                ->addValidator(new Lib_Mein_Validate_File_Extension(array('jpg','jpeg','png','gif')))
                ->setDestination('pub/tmp');
        $this->addElement($img);

        $submit = new Lib_Mein_Form_Element_Submit('submit');
        $submit->setValue('Add')->setIgnored();
        $this->addElement($submit);
        
        $idTheme = new Lib_Mein_Form_Element_Hidden('idtheme');
        $idTheme->setValue($theme['id']);
        $this->addElement($idTheme);
    }
}