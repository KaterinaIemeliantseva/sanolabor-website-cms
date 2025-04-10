<?php
# Name: Database.singleton.php
# File Description: MySQL Singleton Class to allow easy and clean access to common mysql commands
# Author: ricocheting
# Web: http://www.ricocheting.com/
# Update: 2010-07-19
# Version: 3.1.4
# Copyright 2003 ricocheting.com


/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


//require("config.inc.php");
//$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

//$db = Database::obtain();


###################################################################################################
###################################################################################################
###################################################################################################
class Database{

	// debug flag for showing error messages
	public	$debug = false;

	// Store the single instance of Database
	private static $instance;

	private	$server   = ""; //database server
	private	$user     = ""; //database login name
	private	$pass     = ""; //database login password
	private	$database = ""; //database name

	private	$error = "";

	#######################
	//number of rows affected by SQL query
	public	$affected_rows = 0;

	public	$link_id = 0;
	private	$query_id = 0;

	public	$link_id_test = 0;


#-#############################################
# desc: constructor
public function __construct(){
	// error catching if not passed in
    try
    {
    	$this->link_id = new \PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE, DB_USER, DB_PASS);
    	$this->link_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$this->link_id->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch(PDOException $ex)
    {
    	print_r($ex);
        die('Error!');
    }
}#-#constructor()

#-#############################################
# desc: singleton declaration
public static function obtain(){
	if (!self::$instance){
		self::$instance = new Database();
	}

	return self::$instance;
}#-#obtain()

#-#############################################
# Desc: executes SQL query to an open connection
# Param: (MySQL query) to execute
# returns: (query_id) for fetching results etc
public function query($sql, $log = false)
{
	$stmt = $this->link_id->prepare($sql);
	//echo $sql;
    $this->query_id = $stmt->execute();

	$this->affected_rows = 0;

	//log
	if($log) $this->logSpremembe($sql);

	return $this->query_id;
}#-#query()


public function login($uname, $password)
{
    $sql = "SELECT * FROM cms_user where username = :username and password = :password "; //echo $sql;
     $stmt = $this->link_id->prepare($sql);
     $stmt->bindValue(':username', $uname, PDO::PARAM_STR);
     $stmt->bindValue(':password', SHA1($password), PDO::PARAM_STR);
     $stmt->execute();
     return $stmt->fetch(PDO::FETCH_ASSOC);
}#-#query_first()

 function loadUser($userID)
{
    $sql = "SELECT * FROM cms_user where id = :id "; //echo $sql;
     $stmt = $this->link_id->prepare($sql);
     $stmt->bindValue(':id', $userID, PDO::PARAM_INT);
     $stmt->execute();
     return $stmt->fetch(PDO::FETCH_ASSOC);
}#-#query_first()

#-#############################################
# desc: does a query, fetches the first row only, frees resultset
# param: (MySQL query) the query to run on server
# returns: array of fetched results
public function query_first($query_string)
{
    $stmt = $this->link_id->prepare($query_string);
    $stmt->execute();
    $out = $stmt->fetch(PDO::FETCH_ASSOC);

	return $out;
}#-#query_first()


#-#############################################
# desc: fetches and returns results one line at a time
# param: query_id for mysql run. if none specified, last used
# return: (array) fetched record(s)
public function fetch_array($sql)
{
    $stmt = $this->link_id->prepare($sql);
    $stmt->execute();
    $out = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $out;
}#-#fetch()


//ADMIN
public function update_cms($table, $data, $where='1'){
    if(isset($data['id'])) unset($data['id']);
    if(isset($data['c'])) unset($data['c']);
    if(isset($data['m'])) unset($data['m']);
    if(isset($data['token'])) unset($data['token']);

    $q="UPDATE `$table` SET ";
	foreach($data as $key=>$val){
		if(strtolower($val)=='null') $q.= "`$key` = NULL, ";
		elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), ";
        elseif(preg_match("/^increment\((\-?\d+)\)$/i",$val,$m)) $q.= "`$key` = `$key` + $m[1], ";
		else $q.= "`$key`='".$this->escape($val)."', ";
	}

	$q = rtrim($q, ', ') . ' WHERE '.$where.';';
    //echo $q.'<br />';

  	//log
	$this->logSpremembe($q);

	return $this->query($q);
}#-#update()


#-#############################################
# desc: does an insert query with an array
# param: table, assoc array with data (not escaped)
# returns: id of inserted record, false if error
public function insert_cms($table, $data){
    if(isset($data['id'])) unset($data['id']);
    if(isset($data['c'])) unset($data['c']);
    if(isset($data['m'])) unset($data['m']);
    if(isset($data['token'])) unset($data['token']);

	$q="INSERT INTO `$table` ";
	$v=''; $n='';

	foreach($data as $key=>$val){
		$n.="`$key`, ";
		if(strtolower($val)=='null') $v.="NULL, ";
		elseif(strtolower($val)=='now()') $v.="NOW(), ";
		else $v.= "'".$this->escape($val)."', ";
	}

	$q .= "(". rtrim($n, ', ') .") VALUES (". rtrim($v, ', ') .");";
	//echo $q; //die();
	if($this->query($q)){
        $pid = $this->link_id->lastInsertId();
	    //log
		$this->logSpremembe($q);

        return $pid;
	}
	else return false;

}#-#insert()

#-#############################################
# Desc: escapes characters to be mysql ready
# Param: string
# returns: string
public function escape($string){
	if(get_magic_quotes_runtime()) $string = stripslashes($string);
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
    $string = str_replace($search, $replace, $string);
	//return @mysql_real_escape_string($string,$this->link_id);
    return $string;
}#-#escape()

//zapis v log
private function logSpremembe($value)
{
	// global $user;
	// if(isset($_SESSION['userSessionValue']))
	// {
	// //$log_query = "INSERT INTO statistika_dostopov (uporabnik, spremembe, cas) VALUES ( '".$_SESSION['userSessionValue']."', '".$value."', NOW())";
	// $log_query = "INSERT INTO statistika_dostopov (uporabnik, spremembe) VALUES ( '".$_SESSION['userSessionValue']."', '".$this->escape($value)."')";
    // $this->query($log_query);
	// }
}

#-#############################################

#-#############################################
# desc: throw an error message
# param: [optional] any custom error to display
private function oops($msg='')
{ //echo $_SERVER['REMOTE_ADDR'];
	//if($_SERVER['REMOTE_ADDR'] == '192.168.10.183') echo 'napaka';
	if(!empty($this->link_id)){
		$this->error = mysql_error($this->link_id);
	}
	else{
		$this->error = mysql_error();
		$msg="<b>WARNING:</b> No link_id found. Likely not be connected to database.<br />$msg";
	}


	global $user;
	if(isset($_SESSION['userSessionValue']))
	{
		$log_query = "INSERT INTO error_log (msg, uporabnik) VALUES ('".$this->escape($msg)."', '".$_SESSION['userSessionValue']."')"; //echo $this->escape($log_query);
    	$this->query2($log_query);
	}


	if(
	    $_SERVER['REMOTE_ADDR'] != '84.255.239.57'
	    && $_SERVER['REMOTE_ADDR'] != '192.168.10.174'
	    && $_SERVER['REMOTE_ADDR'] != '212.85.177.234'
	    && $_SERVER['REMOTE_ADDR'] != '93.103.169.69'
	) return;


	//if no debug, done here
	//if(!$this->debug) return;
	?>
		<table align="center" border="1" cellspacing="0" style="background:white;color:black;width:80%;">
		<tr><th colspan=2>Database Error</th></tr>
		<tr><td align="right" valign="top">Message:</td><td><?php echo $msg; ?></td></tr>
		<?php if(!empty($this->error)) echo '<tr><td align="right" valign="top" nowrap>MySQL Error:</td><td>'.$this->error.'</td></tr>'; ?>
		<tr><td align="right">Date:</td><td><?php echo date("l, F j, Y \a\\t g:i:s A"); ?></td></tr>
		<?php if(!empty($_SERVER['REQUEST_URI'])) echo '<tr><td align="right">Script:</td><td><a href="'.$_SERVER['REQUEST_URI'].'">'.$_SERVER['REQUEST_URI'].'</a></td></tr>'; ?>
		<?php if(!empty($_SERVER['HTTP_REFERER'])) echo '<tr><td align="right">Referer:</td><td><a href="'.$_SERVER['HTTP_REFERER'].'">'.$_SERVER['HTTP_REFERER'].'</a></td></tr>'; ?>
		</table>
	<?php
}#-#oops()


}//CLASS Database
###################################################################################################

?>
