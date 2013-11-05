<?php

class App_Instructor_Controller_Environment extends Mvc_Controller
{
    /**
     *
     * @var Lib_Mein_Db_Pdo
     */
    private $db = null;
    
    public function preAction()
    {
        $session = new Lib_Mein_Session('auth');
        
        if(!isset($session->auth) || $session->auth->UserLevel != 100) {
            $this->redirect(array('m'=>'auth','a'=>'logout'));
        }
        
        ignore_user_abort();
        set_time_limit(0);
        
        $this->db = Lib_Registry::get('db');
    }
    
    public function index()
    {
        $prims = App_Instructor_Model_Prims::getAll();
        
        foreach($prims as &$p)
        {
            preg_match_all("#:\d*#", $p->Description, $matches);
            
            if(isset($matches[0][0]) && isset($matches[0][1]))
            {
                $idTeam = (int)str_replace(':', '', $matches[0][0]);
                $idStep = (int)str_replace(':', '', $matches[0][1]);

                $p->url =  "http://cc.ics.uci.edu/inspectweb/index.php?" . http_build_query(array(
                    'm' => 'inspectworld',
                    'step' => $idStep,
                    'team' => $idTeam
                ));
            }
        }
        
        $this->getView()->prims = $prims;
        
        $this->getView()->content = $this->getView()->render('/environment/index.phtml');
    }
    
    public function build()
    {
        $form = new App_Instructor_Form_Environment_Build();
        
        if($form->isSubmitted() && $form->isValid())
        {
            $idChallenge = (int)$form->getValue('challenge');
            $idRegion = $form->getValue('region');
            $idCreator = $form->getValue('creator');
            
            //$challenge = $this->db->fetchRow("SELECT * FROM challenges WHERE id=$idChallenge");
            
            $teams = $this->db->fetchAll("SELECT * FROM challenges_teams WHERE idchallenge=$idChallenge");
            
            $steps = $this->db->fetchAll("SELECT * FROM challenges_steps WHERE idchallenge=$idChallenge ORDER BY position ASC");
            
            // read all the steps
            
            foreach($teams as &$t)
            {
                foreach($steps as $s)
                {
                    $description = "{$t['name']}:{$t['id']} - {$s['position']} {$s['name']}:{$s['id']}";

                    $url = "http://cc.ics.uci.edu/inspectweb/index.php?" . http_build_query(array(
                        'm' => 'inspectworld',
                        'step' => $s['id'],
                        'team' => $t['id']
                    ));

                    $t['steps'][] = array(
                        'description' => $description,
                        'url' => str_replace('&', '&amp;', $url)
                    );
                }
            }
            
            $teamsNum = count($teams);
            
            // creating the scoreboard screen
            $scoreboardUrl = "http://cc.ics.uci.edu/inspectweb/index.php?" . http_build_query(array(
                'm' => 'inspectworld',
                'a' => 'scoreboard',
                'challenge' => $idChallenge
            ));
            $scoreboardUrl = str_replace('&', '&amp;', $scoreboardUrl);
            
            for($i=0 ; $i<$teamsNum ; $i++)
            {
                $stepsNum = count($teams[$i]['steps']);
                $northStepsNum = ceil($stepsNum / 3);
                $stepWidth = 12;
                $stepsDistance = 2;
                $arenaWidth = ($northStepsNum * ($stepWidth + $stepsDistance)) + $stepsNum * 2;
                
                $xStart = $i * $arenaWidth + ($stepsDistance * $northStepsNum + 20) * $i + 20;
                
                $yStart = 255/2 - 30;
                
                App_Instructor_Model_Prims::buildArena($idRegion, $idCreator, $teams[$i]['steps'], $xStart, $yStart, $stepsDistance, $scoreboardUrl);
            }
            
            // rebooting
            App_Instructor_Model_System::reboot();
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $prims = App_Instructor_Model_Prims::getAll();
        foreach($prims as $p)
        {
            if($p->Name == 'API Browser')
            {
                $this->getView()->destroy = true;
                break;
            }
        }
        
        $this->getView()->content = $this->getView()->render('/environment/build.phtml');
    }
    
    public function destroy()
    {
        $form = new App_Instructor_Form_Environment_Destroy();
        
        $prims = App_Instructor_Model_Prims::getAll();
        
        if($form->isSubmitted() && $form->isValid())
        {
            foreach($prims as $p)
            {
                if($p->Name == 'API Browser') { // delete only the prims created from the API
                    $res = App_Instructor_Model_Prims::delete($p->UUID);
                }
            }
            
            // rebooting
            App_Instructor_Model_System::reboot();
        }
        else
        {
            $this->getView()->form = $form;
        }
        
        $this->getView()->prims = $prims;
        
        $this->getView()->content = $this->getView()->render('/environment/destroy.phtml');
    }
    
    public function postAction()
    {
        $this->getResponse()->setContent($this->getView()->render('layout.phtml'));
    }
}