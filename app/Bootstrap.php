<?php

class App_Bootstrap implements Mvc_Application_Bootstrap
{
    public static function boot()
    {
        self::initDb();
    }
    
    public static function initDb()
    {
        Lib_Registry::set('db', new Lib_Mein_Db_Pdo('mysql', 'localhost', 'opensimuser', 'password', 'inspectweb'));
    }
}
