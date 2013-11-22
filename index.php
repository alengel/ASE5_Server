<?php
/*
	Entry point of all web request. Includes app constants, settingup path
	and finally dispatching the request.

	Define all application constants.
*/


// set error level, show all errors including notices.
ini_set('display_errors',true);
ini_set('error_reporting',E_ALL-E_NOTICE);

// this should not be empty,  inf installing in subdirectory append the directory name,
// WARNING :  If using IIS, make sure HTTP_HOST is properly set.

define("APP_NAME","ASET5");
define ('WWW_ROOT',$_SERVER['HTTP_HOST']."/eclipse/ASE5_Server"); 

// define zend framework in use, this is also the zend library directory name
// simply change this when upgrading zend framework
define("ZEND_FRAMEWORK","Zend-1.12"); 

// set path for ZEND FRAMEWORK, 
//	if Zend framework is one level above this file (index.php), do not change this, else change accordingly.
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/lib/'.ZEND_FRAMEWORK),
    realpath(dirname(__FILE__) . ''),     
    get_include_path(),
)));


// define application environment, check configs/application.ini, environments are set there
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

/*
*
*	YOU MAY NOT REQUIRE TO CHANGE ANYTHING BELOW.
*
*/

// define the error page, whether to show a friendly or description when an aplication occures
define ('DEBUG',true);

// define other application constants.
define ('DOC_ROOT',realpath(dirname(__FILE__))); // do not change this

// define HTTP or HTTPS protocol, Do not change these
define('HTTP','http://');
define('HTTPS','https://');


// define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/src'));


// set models path.
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/models',     
    APPLICATION_PATH . '/core',     
    get_include_path(),
)));


// turn off magic quote
if (get_magic_quotes_gpc() === 1){
    ini_set( 'magic_quotes_gpc', 0 );
}

//	Zend_Application 
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run

$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/../src/cfg/application.ini'
);


$application->bootstrap();

// add default module controller path and admin module, when you add new module, add it here as well
$application->getBootstrap()->frontcontroller->setControllerDirectory(APPLICATION_PATH . '/default/controllers');
$application->getBootstrap()->frontcontroller->addControllerDirectory(APPLICATION_PATH . '/projects/controllers','projects');
$application->getBootstrap()->frontcontroller->addControllerDirectory(APPLICATION_PATH . '/user/controllers','user');
$application->getBootstrap()->frontcontroller->addControllerDirectory(APPLICATION_PATH . '/t5/controllers','t5');

// check for cron
if ('HTTP/1.1'  == $_SERVER['SERVER_PROTOCOL']) {
	//echo 'web';
	$application->run();
}
else{
	//echo 'Cron started at '.date(time());
	$application->getBootstrap()->frontcontroller->setDefaultControllerName('cron');
	$application->getBootstrap()->frontcontroller->setDefaultAction('run');
	$application->run();
	//echo 'Cron end at '.date(time());
	
}




