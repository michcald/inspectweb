<?php

date_default_timezone_set('America/Los_Angeles');

include 'mvc/application.php';

$application = new Mvc_Application(true);
$application->run();