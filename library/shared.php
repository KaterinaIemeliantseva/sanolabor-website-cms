<?php
/** Check if environment is development and display errors **/
function setReporting()
{
  if (DEVELOPMENT_ENVIRONMENT == true)
  {
  	error_reporting(E_ALL);
  	ini_set('display_errors','On');
  } else
  {
  	error_reporting(E_ALL);
  	ini_set('display_errors','Off');
  	ini_set('log_errors', 'On');
  	//ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
  }
}


//token
if (empty($_SESSION['token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$token = $_SESSION['token'];
preg_match("/[^\.\/]+\.[^\.\/]+$/", $_SERVER['HTTP_HOST'], $matches);
setcookie('token', $token, time() + (86400 * 30), ".".$matches[0]);

//auth
$_SESSION['auth_key']= hash_hmac('sha256', date('dmYH', time()), API_SEC_KEY);



/** Main Call Function **/
function callHook()
{
  global $url;

  $urlArray = array();
  $urlArray = explode("/",$url);

  //get fix
  foreach($_GET as $name => $value)
  {
	$_GET[$name] = str_replace('/', '', $value);
  }

  //nastavimo jezik
  define('JEZIK', 'si');

  //nastavimo nicename
  $i = 0;
  foreach($urlArray as $nivo)
  {
    //  echo 'NIVO'.$i.'_NICENAME - ' . $nivo.'<br />';
    if(!empty($nivo)) define('NIVO'.$i.'_NICENAME', $nivo);

    $i++;
  }

  if(!defined('NIVO0_NICENAME'))  define('NIVO0_NICENAME', 'home');

   //baza
  //if($_SERVER['REMOTE_ADDR'] == '84.255.239.57') include (ROOT . DS . 'library' . DS . 'Database2.class.php');
  include (ROOT . DS . 'library' . DS . 'Database.class.php');
  $db = Database::obtain();

  //login
  include (ROOT . DS . 'library' . DS . 'Access.class.php');
  $user = new flexibleAccess($db);

  // if((!$user->is_loaded() && NIVO0_NICENAME != 'login') || ($user->is_loaded() && NIVO0_NICENAME == 'logout'))
  // {
  //   $user->logout('http://'.$_SERVER['HTTP_HOST'].'/login');
  //   exit();
  // }

  if(!$user->is_loaded() && NIVO0_NICENAME != 'login')
  {
    header('location: http://'.$_SERVER['HTTP_HOST'].'/login');
    die();
  }

  if($user->is_loaded() && NIVO0_NICENAME == 'logout')
  {
    $user->logout('http://'.$_SERVER['HTTP_HOST'].'/login');
    die();
  }


  if(isset($_SESSION['userSessionValue'])) $uid = $_SESSION['userSessionValue'];
  else $uid = 0;

  //references
  include (ROOT . DS . 'library' . DS . 'references.php');

  //template
  include (ROOT . DS . 'library' . DS . 'template.class.php');
  $template = new Template($user);
  $template->Render();


}


setReporting();
//removeMagicQuotes();
callHook();

