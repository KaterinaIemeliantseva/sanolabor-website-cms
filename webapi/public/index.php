<?php
if(session_id() == '')
{
	session_start();
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

ini_set('display_errors', 'On');
ini_set('memory_limit','128M');

define('JEZIK', 'si');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));
//echo ROOT;

require '../vendor/autoload.php';
require '../../config.php';
require 'db.php';
require 'library/Helpers.php';
require 'library/Json.php';
require 'model/Order.php';

require '../../library/file_upload/server/php/UploadHandler.php';
require '../../library/Database.class.php';
require '../../library/SuperClass.class.php';

require '../../library/ssp.class.php';

require_once (ROOT . '/../lib/audit/Audit.php');


//if (file_exists('E:\www\sanolabor_cms/application/bal/'.$class_name.'.php')) {
    spl_autoload_register(function ($class_name) {
        if (file_exists(ROOT . '/application/bal/'.$class_name.'.php')) {
            require_once(ROOT . '/application/bal/'.$class_name.'.php');
        }
    });
//}

// echo $_SERVER['REQUEST_URI'];
// print_r($_GET);

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

//$app>config('debug', true);

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slimskeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());


// Define routes
require 'routes.php';


// Run app
$app->run();
