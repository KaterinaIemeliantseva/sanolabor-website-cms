<?php
//DB
// define('DB_SERVER', "127.0.0.1");
// define('DB_USER', "sanolabor_usr");
// define('DB_PASS', "Y6Fdw28d");
// define('DB_DATABASE', "sanolabor_db");

try
{
	$db = new \PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
}
catch(PDOException $ex)
{
	print_r($ex);
    die('Error!');
}
