<?php
if(session_id() == '')
{
	session_start();
}

header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
// header('Access-Control-Allow-Headers');
Header("Cache-Control: max-age=3000, must-revalidate");
ini_set("sendmail_from", "matej.slana@hakl.it");
ini_set("memory_limit","32M");
date_default_timezone_set('Europe/Ljubljana');
define('DS', DIRECTORY_SEPARATOR);

define('ROOT2', dirname(dirname(__FILE__)));
define('ROOT', dirname(__FILE__));

if(isset($_GET['url'])) $url =  urldecode($_GET['url']);
else $url = '';

require_once (ROOT . DS . 'library' . DS . 'bootstrap.php');
