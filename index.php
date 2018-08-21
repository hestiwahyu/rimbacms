<?php

if(!file_exists('install.php')){
	// Kickstart the framework
	$f3 = require('lib/base.php');

	$f3->set('DEBUG',1);
	if ((float)PCRE_VERSION<7.9)
		trigger_error('PCRE version is out of date');

	$f3->config('app/config/config.ini');
	$f3->config('app/config/routes.ini');

	new Session();

	$f3->run();
}else{
	include 'install.php';
}
