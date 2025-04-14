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

class flexibleAccess
{
  /*Settings*/

  /**
   * The database that we will use
   */
  public $dbName = DB_DATABASE;

  /**
   * The database host
   */
  public $dbHost = 'localhost';

  /**
   * The database port
   */
  public $dbPort = 3307;

  /**
   * The database user
   */
  public $dbUser = DB_USER;

  /**
   * The database password
   */
  public $dbPass = DB_PASS;

  /**
   * The database table that holds all the information
   */
  public $dbTable  = 'cms_user';

  /**
   * The session variable ($_SESSION[$sessionVariable]) which will hold the data while the user is logged on
   */
  public $sessionVariable = 'userSessionValue';

  /**
   * Those are the fields that our table uses in order to fetch the needed data. The structure is 'fieldType' => 'fieldName'
   */
  public $tbFields = array(
    'id' => 'id',
    'username' => 'username',
    'password'  => 'password',
    'email' => 'email',
    'active' => 'active',
    'nivo' => 'nivo',
    'ime_priimek' => 'ime_priimek'
  );

  /**
   * When user wants the system to remember him/her, how much time to keep the cookie? (seconds)
   */
  public $remTime = 2592000; //One month

  /**
   * The name of the cookie which we will use if user wants to be remembered by the system
   */
  public $remCookieName = 'ckSavePass';

  /**
   * The cookie domain
   */
  public $remCookieDomain = '';

  /**
   * The method used to encrypt the password. It can be sha1, md5 or nothing (no encryption)
   */
  public $passMethod = 'sha1';

  /**
   * Display errors? Set this to true if you are going to seek for help, or have troubles with the script
   */
  public $displayErrors = true;

  /* Runtime variables */
  public $userID;
  public $dbConn;
  public $userData = array();
  /**
   * Class Constructure
   *
   * @param string $dbConn
   * @param array $settings
   * @return void
   */
  public function __construct($dbConn, $settings = '')
  {
    $this->dbConn = $dbConn;

    // We use user settings
    if (is_array($settings)) {
      foreach ($settings as $k => $v) {
        if (!isset($this->{$k})) die('Property ' . $k . ' does not exist. Check your settings.');
        $this->{$k} = $v;
      }
    }

    // Install the domain for cookies
    $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;

    // If there is already a session - load the user
    if (!empty($_SESSION[$this->sessionVariable])) {
      $this->loadUser($_SESSION[$this->sessionVariable]);
    }
    // Otherwise we are trying to authorize the token through cookies
    elseif (!$this->is_loaded()) {
      $this->validateRememberToken();
    }
  }

  /**
   * Login function
   * @param string $uname
   * @param string $password
   * @param bool $loadUser
   * @return bool
   */
  public function login($uname, $password, $remember = false)
  {
    // Get user data by name
    $user = $this->dbConn->getUserByUsername($uname);
    // If the user is not found, refusal
    if (!$user) return false;

    $dbPassword = $user['password'];
    $passwordValid = false;

    // New password format (bcrypt / password_hash)
    if (password_get_info($dbPassword)['algo']) {
      $passwordValid = password_verify($password, $dbPassword);
    }
    // Old Sha1
    elseif (sha1($password) === $dbPassword) {
      $passwordValid = true;

      // We update the password in the database for a new format
      $newHash = password_hash($password, PASSWORD_DEFAULT);
      $this->dbConn->updateUserPassword($user['id'], $newHash);
    }

    if (!$passwordValid) return false;

    // We keep the session and "remember me"
    $this->userData = $user;
    $this->userID = $user[$this->tbFields['id']];
    $_SESSION[$this->sessionVariable] = $this->userID;

    if ($remember) {
      $this->createRememberToken($user['id']);
    }

    return true;
  }

  /**
   * Logout function
   * param string $redirectTo
   * @return bool
   */
  public function logout($redirectTo = '')
  {
    // We remove the token from the base if the user is authorized
    if ($this->is_loaded()) {
      $this->dbConn->deleteRememberToken($this->userID);
    }

    // We finish the session
    session_destroy();

    // Remove the token cookies
    setcookie($this->remCookieName, '', time() - 3600, '/', $this->remCookieDomain, true, true);
    unset($_COOKIE[$this->remCookieName]);

    // We clean the variables
    $this->userData = [];
    $this->userID = null;

    // If the address is set - redirect
    if ($redirectTo !== '' && !headers_sent()) {
      header("Location: $redirectTo");
      exit;
    }
  }

  /**
   * Creates a token and saves it in the base + sets cookies
   * @param int $userID - User ID
   */
  private function createRememberToken($userID)
  {
    // Generous Token (256 bit = 64 hex symbols)
    $token = bin2hex(random_bytes(32));
    $hashedToken = hash('sha256', $token); // We keep in the database only a token hash

    // We keep the token hash in the database
    $this->dbConn->storeRememberToken($userID, $hashedToken);

    // Install cookies with token (not hash!), Httponly and validity period
    setcookie(
      $this->remCookieName,
      $token,
      time() + $this->remTime, // By default, a month
      '/',
      $this->remCookieDomain,
      true, // Only through https (if the site works on https)
      true  // Httponly - JS protection
    );
  }

  /**
   * Checks the availability of token in cookies and uploads the user if the token is valid
   * @return bool - true if the user is found and loaded
   */
  private function validateRememberToken()
  {
    if (!isset($_COOKIE[$this->remCookieName])) return false;

    $token = $_COOKIE[$this->remCookieName];
    $hashedToken = hash('sha256', $token);

    // Trying to find a user on Hash Token
    $user = $this->dbConn->getUserByRememberToken($hashedToken);

    if ($user) {
      $this->userData = $user;
      $this->userID = $user[$this->tbFields['id']];
      $_SESSION[$this->sessionVariable] = $this->userID;
      return true;
    }

    return false;
  }

  /**
   * Function to determine if a property is true or false
   * param string $prop
   * @return bool
   */
  public function is($prop)
  {
    return $this->get_property($prop) == 1 ? true : false;
  }

  /**
   * Get a property of a user. You should give here the name of the field that you seek from the user table
   * @param string $property
   * @return string
   */
  public function get_property($property)
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if (!isset($this->userData[$property])) $this->error('Unknown property <b>' . $property . '</b>', __LINE__);
    return $this->userData[$property];
  }

  /**
   * Is the user an active user?
   * @return bool
   */
  public function is_active()
  {
    return $this->userData[$this->tbFields['active']];
  }

  /**
   * Is the user loaded?
   * @ return bool
   */
  public function is_loaded()
  {
    return empty($this->userID) ? false : true;
  }

  /**
   * Activates the user account
   * @return bool
   */
  public function activate()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ($this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1
	WHERE `{$this->tbFields['userID']}` = '" . $this->escape($this->userID) . "' LIMIT 1");
    if (@mysql_affected_rows() == 1) {
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
  public function insertUser($data)
  {
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
    switch (strtolower($this->passMethod)) {
      case 'sha1':
        $password = "SHA1('" . $data[$this->tbFields['pass']] . "')";
        break;
      case 'md5':
        $password = "MD5('" . $data[$this->tbFields['pass']] . "')";
        break;
      case 'nothing':
        $password = $data[$this->tbFields['pass']];
    }
    foreach ($data as $k => $v) $data[$k] = "'" . $this->escape($v) . "'";
    $data[$this->tbFields['pass']] = $password;
    $this->query("INSERT INTO `{$this->dbTable}` (`" . implode('`, `', array_keys($data)) . "`) VALUES (" . implode(", ", $data) . ")");
    return (int)mysql_insert_id($this->dbConn);
  }

  /*
   * Creates a random password. You can use it to create a password or a hash for user activation
   * param int $length
   * param string $chrs
   * return string
   */
  public function randomPass($length = 10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm')
  {
    for ($i = 0; $i < $length; $i++) {
      $pwd .= $chrs[mt_rand(0, strlen($chrs) - 1)];
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
  private function query($sql, $line = 'Uknown')
  {
    //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
    $res = mysql_query($sql, $this->dbConn);
    if (!$res)
      $this->error(mysql_error($this->dbConn), $line);
    return $res;
  }

  /**
   * A function that is used to load one user's data
   * @access private
   * @param string $userID
   * @return bool
   */
  private function loadUser($userID)
  {
    $loadUser = $this->dbConn->loadUser($userID);
    if (!$loadUser) {
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
  private function error($error, $line = '', $die = false)
  {
    if ($this->displayErrors)
      echo '<b>Error: </b>' . $error . '<br /><b>Line: </b>' . ($line == '' ? 'Unknown' : $line) . '<br />';
    if ($die) exit;
    return false;
  }
}
