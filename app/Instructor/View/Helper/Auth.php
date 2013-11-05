<?php

class App_Instructor_View_Helper_Auth
{
    public function auth()
    {
        $session = new Lib_Mein_Session('auth');
        
        return $session->auth;
    }
}