<?php
//header("Content-Type:   text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set("memory_limit", "32M");
set_time_limit(300);
date_default_timezone_set('Europe/Ljubljana');
define('DS', DIRECTORY_SEPARATOR);
//define('ROOT', dirname(__FILE__));
define('ROOT', dirname(dirname(__FILE__)));
 //print_r($_POST);
 if(isset($_POST['data']) && is_array($_POST['data']))
	$_POST = $_POST['data'];

if(isset($_GET['c']))
{
    $_POST = $_GET;
}

/*if(isset($_POST) && !empty($_POST['c']) && !empty($_POST['m']) && $_POST['m'] == 'toXls'){
	header('Content-Type: text/html; charset=Windows-1250');
}*/

if(isset($_POST) && !empty($_POST['c']) && !empty($_POST['m']))
{
	if(isset($_POST['jezik'])) define('JEZIK', $_POST['jezik']);
  	else  define('JEZIK', 'si');

    //require_once ROOT . DS . 'library' . DS . 'php-aws-ses-master' . DS . 'autoload.php';
    require_once ROOT . DS . '../lib' . DS . 'php-aws-ses-master' . DS . 'autoload.php';

    //baza
    require_once (ROOT . DS . 'config.php');
    include (ROOT . DS . 'library' . DS . 'Database.class.php');
    $db = Database::obtain();
    //print_R($db);
	//user
	include (ROOT . DS . 'library' . DS . 'Access.class.php');
	$user = new flexibleAccess($db);

	if(isset($_SESSION['userSessionValue'])) $uid = $_SESSION['userSessionValue'];
	else $uid = 0;


    define('CLASSNAME', $_POST['c']);
    define('METHODNAME', $_POST['m']);

    function __autoload($name)
    {
        include_once(ROOT . DS . 'library' . DS . 'SuperClass.class.php');
        if(CLASSNAME != 'Vsebina') include_once(ROOT . DS . 'application' . DS . 'bal' . DS . 'VsebinaBAL.php');
        include_once(ROOT . DS . 'application' . DS . 'bal' . DS . CLASSNAME.'BAL.php');
    }

    $classname = CLASSNAME.'BAL';   $methodname = METHODNAME;
    $request = new $classname;
    $request -> $methodname($_POST);

}
