<?php
//ini_set('session.save_path', 'E:\sessions');
// **PREVENTING SESSION HIJACKING**
// Prevents javascript XSS attacks aimed to steal the session ID
//ini_set('session.cookie_httponly', 1);

// **PREVENTING SESSION FIXATION**
// Session ID cannot be passed through URLs
//ini_set('session.use_only_cookies', 1);

// Uses a secure connection (HTTPS) if possible
//ini_set('session.cookie_secure', 1);

// ini_set("session.cookie_domain", ".ha2net.com");

  //echo $_SERVER['REMOTE_ADDR'];
/** Configuration Variables **/
if(
    $_SERVER['REMOTE_ADDR'] == '192.168.10.209'
    || $_SERVER['REMOTE_ADDR'] == '192.168.10.174'
    //|| $_SERVER['REMOTE_ADDR'] == '192.168.10.227'
    || $_SERVER['REMOTE_ADDR'] == '212.85.177.234'
    || $_SERVER['REMOTE_ADDR'] == '93.103.169.69'
    || $_SERVER['REMOTE_ADDR'] == '84.255.239.57'
    || $_SERVER['REMOTE_ADDR'] == '46.248.66.190'
    || $_SERVER['REMOTE_ADDR'] == '109.182.50.109'   //siol doma
) {
    define ('DEVELOPMENT_ENVIRONMENT',true);
    define ('DE',true);
}
else {
    define ('DEVELOPMENT_ENVIRONMENT',false);
    define ('DE',false);
}


function isAllowed($ip, $whitelist){
    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
        return true;
    }

    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");

        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            return true;
        }
    }

    return false;
}


// $allowed_ips[] = '149.126.142.155';

// $remote_addr = $_SERVER['REMOTE_ADDR'];

// if(!isAllowed($remote_addr, $allowed_ips))
// {
//     header("HTTP/1.1 401 Unauthorized");
//     die();
// }



//echo $_SERVER['REMOTE_ADDR'];

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
    $secure_connection = true;
} else {
	$secure_connection = false;
}

define('API_SEC_KEY', '84637708-9867-11e7-abc4-cec278n6b50a');
define('MAIN_URL', "http://www.sanolabor.si");

//DB
define('DB_SERVER', "172.28.41.63");
define('DB_USER', "sanolabor_usr2");
define('DB_PASS', "&hRb8G0JcxxS");
define('DB_DATABASE', "sanolabor_db2");

define('MAIN_PAGE', "https://www.sanolabor.si");

define('MAIL_FROM', '');
define('MAIL_FROM_NAME', '');

//bugsnag
//define('BUGSNAG_API_KEY', 'fa18b0a14374db03bd0038e3e004b435');

$_SESSION['RF']["verify"] = "RESPONSIVEfilemanager";

//audit
$GLOBALS['audit_config'] = [];
$GLOBALS['audit_config']['DBSource'] = 'SPOINT-SQL3\WEB';
$GLOBALS['audit_config']['DBName'] = '_web_sanolabor';
$GLOBALS['audit_config']['CustomerID'] = '54e648f0-7dc4-11e8-adc0-fa7ae01bbebc';
$GLOBALS['audit_config']['ApplicationID'] = '54e64cec-7dc4-11e8-adc0-fa7ae01bbebc';
$GLOBALS['audit_config']['ApplicationName'] = 'Sanolabor - spletna trgovina';
$GLOBALS['audit_config']['Force'] = 1;