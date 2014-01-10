<?php
require dirname(__FILE__).'/../../client/Logger.php';

$levels = array('ERROR','WARNING','NORMAL','DEBUG');
Logger::setLandingPage('http://mysite.com/'.uniqid());
Logger::setPageType('A'.uniqid());
Logger::setVar1('some variable'.uniqid());
Logger::log('testing : '.uniqid().print_r($_SERVER,true), $levels[array_rand($levels)]);

