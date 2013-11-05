<?php

class App_Default_Controller_Index extends Mvc_Controller
{
    public function preAction()
    {
        $this->redirect(array('m'=>'auth'));
    }
    
    public function index()
    {
        //$this->getResponse()->setContent($this->getView()->render('index.phtml'));
    }
}