<?php
#set errors reporting
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

date_default_timezone_set('America/New_York');

// ****************************************** //
// ************ ERROR MANAGEMENT ************ //


// ************ ERROR MANAGEMENT ************ //
// ****************************************** //


// Site URL (base for all redirections):
#define ('BASE_URL', 'https://web.njit.edu/~nl79/it302/it302register/public/');
#define ('BASE_URL', 'http://osl81.njit.edu/~nl79/it302/it302register/public/');
define ('BASE_URL', '/it302register/public/');


/*
 *require the autoloader class.
 *set the autoloader. 
 */ 
require_once('library/loader.class.php'); 
spl_autoload_register('library\\loader::load');

//require_once('mysqli_connect.php');

/*
 *create a router object.
 */ 
$router = new library\Router(); 

#build the page object. 
$page = 'controller\\' . $router->getNode(); 
$controller = new $page($router);   
