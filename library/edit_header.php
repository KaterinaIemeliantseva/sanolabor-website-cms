<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

if(session_id() == '')
{
    session_start();
}

if (empty($_SESSION['userSessionValue']))
{
    exit();
}

//include (dirname(dirname(__FILE__)).'/vendor/autoload.php');

require_once (dirname(dirname(__FILE__)).'/../lib/audit/Audit.php');
include (dirname(dirname(__FILE__)).'/config.php');
include (dirname(dirname(__FILE__)).'/library/Database.class.php');
include ('SuperClass.class.php'); //echo ROOT . DS . 'library' . DS . 'SuperClass.class.php'; die();
$handler = new SuperClass();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

spl_autoload_register(function ($class_name) {
    require_once(dirname(dirname(__FILE__)).'/application/bal/'.$class_name.'.php');
});


$bal = $_POST['data']['c'].'BAL';
$foo = new $bal;

$data = false;
if(!empty($_POST['data']['id']))
{
    $data = $foo->getSingle($_POST['data']['id']);
}

// if(DE)
// {

//     $audit_entries['Entries'] = [];

//     foreach ($data as $field_name => $value_item) 
//     {
//         $audit_entries['Entries'][] = ['FieldName' => $field_name, 'TrailType' => 2, 'OldValue' => $value_item, 'NewValue' => '', 'OldDescription' => '', 'NewDescription' => '', 'TableName' => 'test'];
//     }

//     print_r($audit_entries);
// }
