<?php
/**
 * PHP Class to user access (login, register, logout, etc)
 *
 * <code><?php
 * include('access.class.php');
 * $user = new flexibleAccess();
 * ? ></code>
 *
 * For support issues please refer to the webdigity forums :
 *				http://www.webdigity.com/index.php/board,91.0.html
 * or the official web site:
 *				http://phpUserClass.com/
 * ==============================================================================
 *
 * @version $Id: access.class.php,v 0.93 2008/05/02 10:54:32 $
 * @copyright Copyright (c) 2007 Nick Papanotas (http://www.webdigity.com)
 * @author Nick Papanotas <nikolas@webdigity.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 *
 * ==============================================================================

 */

/**
 * Flexible Access - The main class
 *
 * @param string $dbName
 * @param string $dbHost
 * @param string $dbUser
 * @param string $dbPass
 * @param string $dbTable
 */

class flexibleAccess{
  /*Settings*/
  /**
   * The database that we will use
   * var string
   */
  var $dbName = DB_DATABASE;
  /**
   * The database host
   * var string
   */
  var $dbHost = 'localhost';
  /**
   * The database port
   * var int
   */
  var $dbPort = 3307;
  /**
   * The database user
   * var string
   */
  var $dbUser = DB_USER;
  /**
   * The database password
   * var string
   */
  var $dbPass = DB_PASS;
  /**
   * The database table that holds all the information
   * var string
   */
  var $dbTable  = 'cms_user';
  /**
   * The session variable ($_SESSION[$sessionVariable]) which will hold the data while the user is logged on
   * var string
   */
  var $sessionVariable = 'userSessionValue';
  /**
   * Those are the fields that our table uses in order to fetch the needed data. The structure is 'fieldType' => 'fieldName'
   * var array
   */
  var $tbFields = array(
  	'id'=> 'id',
  	'username' => 'username',
  	'password'  => 'password',
  	'email' => 'email',
  	'active'=> 'active',
  	'nivo'=> 'nivo',
  	'ime_priimek'=> 'ime_priimek'
  );
	/**
   * When user wants the system to remember him/her, how much time to keep the cookie? (seconds)
   * var int
   */
  var $remTime = 2592000;//One month
  /**
   * The name of the cookie which we will use if user wants to be remembered by the system
   * var string
   */
  var $remCookieName = 'ckSavePass';
  /**
   * The cookie domain
   * var string
   */
  var $remCookieDomain = '';
  /**
   * The method used to encrypt the password. It can be sha1, md5 or nothing (no encryption)
   * var string
   */
  var $passMethod = 'sha1';
  /**
   * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
   * var bool
   */
  var $displayErrors = true;
  /*Do not edit after this line*/
  var $userID;
  var $dbConn;
  var $userData=array();
  /**
   * Class Constructure
   *
   * @param string $dbConn
   * @param array $settings
   * @return void
   */
  function __construct($dbConn, $settings = '')
  {
      $this->dbConn = $dbConn;

	    if ( is_array($settings) ){
		    foreach ( $settings as $k => $v ){
				    if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
				    $this->{$k} = $v;
			}
	    }
	    $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;

	    if (!empty($_SESSION[$this->sessionVariable]) )
	    {
		    $this->loadUser( $_SESSION[$this->sessionVariable] );
	    }

	    //Maybe there is a cookie?
	    if (isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
	      //echo 'I know you<br />';
	      $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	      $this->login($u['uname'], $u['password']);
	    }
  }

  /**
  	* Login function
  	* @param string $uname
  	* @param string $password
  	* @param bool $loadUser
  	* @return bool
  */
  function login($uname, $password, $remember = false, $loadUser = true)
  {
      $originalPassword =  $password;

        $loadUser = $this->dbConn->login($uname, $password);

        if($loadUser)
        {
          $this->userData = $loadUser;
          $this->userID = $this->userData[$this->tbFields['id']];
          $_SESSION[$this->sessionVariable] = $this->userID;
          if ( $remember ) {
            $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
            $a = setcookie($this->remCookieName,
            $cookie,time()+$this->remTime, '/', $this->remCookieDomain);
          }

          return true;
        }

		return false;
  }

  /**
  	* Logout function
  	* param string $redirectTo
  	* @return bool
  */
  function logout($redirectTo = '')
  {
      session_destroy();
      setcookie($this->remCookieName, null,-1, '/', $this->remCookieDomain);
      //echo $this->remCookieName;
      //die();

    unset($_COOKIE[$this->remCookieName]);

    setcookie($this->remCookieName, '', time()-3600);
    $_SESSION[$this->sessionVariable] = '';
    $this->userData = '';
    if ( $redirectTo != '' && !headers_sent()){
	   header('Location: '.$redirectTo );
	   exit;//To ensure security
	}
  }
  /**
  	* Function to determine if a property is true or false
  	* param string $prop
  	* @return bool
  */
  function is($prop){
  	return $this->get_property($prop)==1?true:false;
  }

    /**
  	* Get a property of a user. You should give here the name of the field that you seek from the user table
  	* @param string $property
  	* @return string
  */
  function get_property($property)
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if (!isset($this->userData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
    return $this->userData[$property];
  }
  /**
  	* Is the user an active user?
  	* @return bool
  */
  function is_active()
  {
    return $this->userData[$this->tbFields['active']];
  }

  /**
   * Is the user loaded?
   * @ return bool
   */
  function is_loaded()
  {
    return empty($this->userID) ? false : true;
  }
  /**
  	* Activates the user account
  	* @return bool
  */
  function activate()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ( $this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
    if (@mysql_affected_rows() == 1)
	{
		$this->userData[$this->tbFields['active']] = true;
		return true;
	}
	return false;
  }
  /*
   * Creates a user account. The array should have the form 'database field' => 'value'
   * @param array $data
   * return int
   */
  function insertUser($data){
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
    switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	$password = "SHA1('".$data[$this->tbFields['pass']]."')"; break;
	  case 'md5' :
	  	$password = "MD5('".$data[$this->tbFields['pass']]."')";break;
	  case 'nothing':
	  	$password = $data[$this->tbFields['pass']];
	}
    foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
    $data[$this->tbFields['pass']] = $password;
    $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
    return (int)mysql_insert_id($this->dbConn);
  }
  /*
   * Creates a random password. You can use it to create a password or a hash for user activation
   * param int $length
   * param string $chrs
   * return string
   */
  function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
    for($i = 0; $i < $length; $i++) {
      $pwd .= $chrs[mt_rand(0, strlen($chrs)-1)];
    }
    return $pwd;
  }
  ////////////////////////////////////////////
  // PRIVATE FUNCTIONS
  ////////////////////////////////////////////

  /**
  	* SQL query function
  	* @access private
  	* @param string $sql
  	* @return string
  */
  function query($sql, $line = 'Uknown')
  {
    //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
		$res = mysql_query($sql, $this->dbConn);
	if ( !$res )
		$this->error(mysql_error($this->dbConn), $line);
	return $res;
  }

  /**
  	* A function that is used to load one user's data
  	* @access private
  	* @param string $userID
  	* @return bool
  */
  function loadUser($userID)
  {

    $loadUser = $this->dbConn->loadUser($userID);
    if(!$loadUser)
    {
        return false;
    }

    $this->userData = $loadUser;
    $this->userID = $userID;
    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }

  /**
  	* Error holder for the class
  	* @access private
  	* @param string $error
  	* @param int $line
  	* @param bool $die
  	* @return bool
  */
  function error($error, $line = '', $die = false) {
    if ( $this->displayErrors )
    	echo '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
    if ($die) exit;
    return false;
  }
}
?>
