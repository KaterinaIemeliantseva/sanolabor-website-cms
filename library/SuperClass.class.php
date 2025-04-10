<?php
class SuperClass
{
    public $main_url = MAIN_URL;

    function sesMail($mail_settings)
	{
		$mail_from = (!empty($mail_settings['ses_from'])) ? $mail_settings['ses_from'] : MAIL_FROM;

		$m = new SimpleEmailServiceMessage();
  		$m->addTo($mail_settings['ses_to']);
  		$m->setFrom($mail_from);
  		$m->setSubject($mail_settings['ses_subject']);
  		$m->setMessageFromString(null, $mail_settings['ses_message']);

		if(!empty($mail_settings['ses_attachment']))
		{
			$m->addAttachmentFromFile(basename($mail_settings['ses_attachment']), $mail_settings['ses_attachment'], 'application/pdf');
		}

  		$ses = new SimpleEmailService(MAIL_ACCESS_KEY_ID, MAIL_SECRET_ACCESS_KEY, SimpleEmailService::AWS_US_WEST_2);
  		$ses->setVerifyHost(false);
  		$ses->setVerifyPeer(false);

  		if($ses->sendEmail($m))
  		{
			  return true;
  		}
  		else
  		{
  			//echo 'Prišlo je do napake!';
  		}

		return false;
	}

  function cleanTextDiff($string)
  {
    $out = str_ireplace(array("\r","\n"),'', $string);
  //  $out = trim(preg_replace('/\s\s+/', ' ', $out));
    $out = preg_replace('/\s+/', '', trim($out));
    $out = preg_replace('~[\r\n]+~', '', $out);
    $out = str_replace(PHP_EOL, '', $out);
    $out = trim($out);
    $out = json_encode($out);
    return $out;
  }

  function removeNewline($string)
  {
    return trim(preg_replace('/\s\s+/', ' ', $string));
  }

  function cp1250_to_utf8_array($input)
  {
    foreach ($input as $key => $value)
    {
      $input[$key] = iconv('cp1250', 'utf-8', $value);
    }
    return $input;
  }

	function preformat($array)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

  function sumnikiNazaj($x)
    {
      $rez=str_replace("[sss]", "š", $x);
      $rez=str_replace("[ccc]", "č", $rez);
      $rez=str_replace("[ccd]", "ć", $rez);
      $rez=str_replace("[ddd]", "đ", $rez);
      //$rez=str_replace("&#269;", "[ccc]", $rez);
      $rez=str_replace("[zzz]", "ž", $rez);
      $rez=str_replace("[SSS]", "Š", $rez);
      $rez=str_replace("[CCC]", "Č", $rez);
      $rez=str_replace("[CCD]", "Ć", $rez);
      $rez=str_replace("[DDD]", "Đ", $rez);
      //$rez=str_replace("&#268;", "[CCC]", $rez);
      $rez=str_replace("[ZZZ]", "Ž", $rez);
      return $rez;
    }

	function generate($array, $page = 1, $perPage = 10)
    {

      // Assign the items per page variable
      if (!empty($perPage))
        $_perPage = $perPage;

      // Assign the page variable
      $_page = $page; // if we don't have a page number then assume we are on the first page


      // Take the length of the array
      $_length = count($array);

      // Get the number of pages
      $_pages = ceil($_length / $_perPage);

      // Calculate the starting point
      $_start  = ceil(($_page - 1) * $_perPage);

      // Return the part of the array we have requested
      return array_slice($array, $_start, $_perPage);
    }

	function createDateRangeArray($strDateFrom,$strDateTo) {
        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom) {
          array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry

          while ($iDateFrom<$iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
          }
        }
        return $aryRange;
      }

	function sumniki($x)
	  {
	    $rez=str_replace("š", "[sss]", $x);
	    $rez=str_replace("č", "[ccc]", $rez);
	    $rez=str_replace("ć", "[ccd]", $rez);
	    $rez=str_replace("đ", "[ddd]", $rez);
	    //$rez=str_replace("&#269;", "[ccc]", $rez);
	    $rez=str_replace("ž", "[zzz]", $rez);
	    $rez=str_replace("Š", "[SSS]", $rez);
	    $rez=str_replace("Č", "[CCC]", $rez);
	    $rez=str_replace("Ć", "[CCD]", $rez);
	    $rez=str_replace("Đ", "[DDD]", $rez);
	    //$rez=str_replace("&#268;", "[CCC]", $rez);
	    $rez=str_replace("Ž", "[ZZZ]", $rez);
	    return $rez;
	  }


	function odstejOdstotek($vrednost, $odstotek)
	{
		$x = ($vrednost / 100) * $odstotek;
		$nova_vrednost = $vrednost - $x;

		return $nova_vrednost;
	}

	function mb_unserialize($serial_str)
	{
        $recovered = preg_replace_callback(
            '!(?<=^|;)s:(\d+)(?=:"(.*?)";(?:}|a:|s:|b:|d:|i:|o:|N;))!s',
            function($match) {
                return 's:' . mb_strlen($match[2], '8bit');
            },
            $serial_str
        );

       return unserialize($recovered);
	}


	function getLink($root, $redirect, $redirect2 = array())
	{
	  $r = '';

	  foreach($redirect2 as $key=>$val)
	  {
			if($val)
			{
				$r .= ($r == '') ? '?' : '&';
				$r .= $key.'='.$val;
			}
	  }

	  foreach($redirect as $key=>$val)
	  {
			if(isset($_GET[$val])) $r .= '&'.$val.'='.$_GET[$val];
	  }

	  return $root.$r;
	}

	function sortmulti ($array, $index, $order, $natsort=FALSE, $case_sensitive=FALSE)
	{
        if(is_array($array) && count($array)>0) {
            foreach(array_keys($array) as $key)
            $temp[$key]=$array[$key][$index];
            if(!$natsort) {
                if ($order=='asc')
                    asort($temp);
                else
                    arsort($temp);
            }
            else
            {
                if ($case_sensitive===true)
                    natsort($temp);
                else
                    natcasesort($temp);
            if($order!='asc')
                $temp=array_reverse($temp,TRUE);
            }
            foreach(array_keys($temp) as $key)
                if (is_numeric($key))
                    $sorted[]=$array[$key];
                else
                    $sorted[$key]=$array[$key];
            return $sorted;
        }
    	return $sorted;
	}

	function clearArray(&$parent)
	{
	  unset($parent['fields']);
	  foreach ($parent as $k => &$v)
	  {
	    if (is_array($v))
	    {
	      clear_fields($v);
	    }
	  }
	}

  function getCurrentNicename($url)
  {
    $var_array = explode("/",urldecode($url));
    $nicename = $var_array[sizeof($var_array) - 1];
    return $nicename;
  }

  function isValidEmail($email_address)
  {
    $regex = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
    return (preg_match($regex, $email_address));
  }

  function arrayChangeKeyName( $orig, $new, &$array )
  {
    if ( isset( $array[$orig] ) )
    {
        $array[$new] = $array[$orig];
        unset( $array[$orig] );
    }
    return $array;
  }

  function mbCutText($string, $length, $delimiter = ' ', $postfix = ' ...')
  {
    $stringLength = mb_strlen($string, 'UTF-8');
    if (strlen($string)>$length) $string = substr($string,0,$length);
    if($length < $stringLength and mb_strpos($string, $delimiter, null, 'UTF-8') !== false)
    {
      while (mb_substr($string, $stringLength-1, $stringLength, 'UTF-8') != $delimiter)
      {
        $stringLength--;
      }
      return mb_substr($string, 0, $stringLength, 'UTF-8').$postfix;
    }
    return $string;
  }

  function date_to_array($date)
  {
  	$chunk = explode("-", $date);

  	return($chunk);
  }

  function date_to_slo($date)
  {
  	$chunk = explode("-", $date);

  	$newdate = $chunk[2] . "." . $chunk[1] . "." . $chunk[0];
  	return($newdate);
  }

  function slo_to_date($date)
  {
	$date=str_replace(" ","", $date);
	$date=str_replace("-",".", $date);
	$date=str_replace("/",".", $date);
	$chunk = explode(".", $date);
    if(!isset($chunk[0]) || !isset($chunk[1]) || !isset($chunk[2]))
    {
            return '1983-10-03';
    }

	$newdate = $chunk[2] . "-" . $chunk[1] . "-" . $chunk[0];
	return($newdate);
  }

  function DatumDobiDan($date)
  {
  	$chunk = explode("-", $date);
  	return($chunk[2]);
  }

  function DatumDobiMesec($date)
  {
  	$chunk = explode("-", $date);
  	return($chunk[1]);
  }

  function PristejOdstejDatum($month,$day,$year, $action)
  {
    $start = $year.'-'.$month.'-'.$day;
    $end = date('Y-m-d', strtotime($start . $action));

    return $end;
  }

  function datetime_to_slo($datetime)
  {
  	$date_time = explode(" ", $datetime);
  	$chunk = explode("-", $date_time[0]);
  	$date = $chunk[2] . "." . $chunk[1] . "." . $chunk[0];
  	$chunk = explode("-", $date_time[1]);
  	//$time = $chunk[0] . ":" . $chunk[1];
  	$time=$date_time[1];

  	return($date." ob ".$time);
  }

  function datetime_to_slo2($datetime)
  {
  	$date_time = explode(" ", $datetime);
  	$chunk = explode("-", $date_time[0]);
  	$date = $chunk[2] . "." . $chunk[1] . "." . $chunk[0];
  	$chunk = explode("-", $date_time[1]);
  	//$time = $chunk[0] . ":" . $chunk[1];
  	$time=$date_time[1];

  	return($date." ".$time);
  }

  function getFilename($path)
  {
    $file = explode("/", $path);
    $filename = $file[sizeof($file) - 1];
    return $filename;
  }

  function getExt($path)
  {
    $file = explode(".", $path);
    $ext = $file[sizeof($file) - 1];
    return $ext;
  }

  function formatBytes($filename, $precision = 2)
  {

	  $bytes = filesize(getcwd().$filename);
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

  function generatePassword($length=6, $strength=0)
  {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength & 2) {
		$vowels .= "AEUY";
	}
	if ($strength & 4) {
		$consonants .= '23456789';
	}
	if ($strength & 8) {
		$consonants .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
  }

  function createRandomPassword($length)
  {

    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= $length) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }

    return $pass;
  }

  function getClass($instance)
  {
      return str_replace('BAL', '', get_class($instance));
  }

  function GetDatesBetween($fromDate, $toDate)
  {
    $dateMonthYearArr = array();
    $fromDateTS = strtotime($fromDate);
    $toDateTS = strtotime($toDate);

    for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
    {
      $currentDateStr = date('Y-m-d',$currentDateTS);
      $dateMonthYearArr[] = $currentDateStr;
    }

    return $dateMonthYearArr;
  }

  function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array())
  {
  	$first_of_month = gmmktime(0,0,0,$month,1,$year);
  	#remember that mktime will automatically correct if invalid dates are entered
  	# for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
  	# this provides a built in "rounding" feature to generate_calendar()

  	$day_names = array(); #generate all the day names according to the current locale
  	for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
  		$day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

  	list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
  	$weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
  	$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

  	#Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
  	@list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
  	if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
  	if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
  	$calendar = '<table class="calendar">'."\n".
  		'<caption class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</caption>\n<tr>";
    $x=1;
  	if($day_name_length)
    { #if the day names should be shown ($day_name_length > 0)
		#if day_name_length is >3, the full name of the day will be printed
		  $x=1;
		  foreach($day_names as $d)
      {
  		//$calendar .= '<th abbr="'.htmlentities($d).'">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</th>';
  			if($x == 4) $calendar .= '<th abbr="Četrtek">Čet</th>';
        else $calendar .= '<th abbr="'.$d.'">'.substr($d,0,$day_name_length).'</th>';
        $x++;
      }
		  $calendar .= "</tr>\n<tr>";

	   }

  	if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; #initial 'empty' days
  	for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
  		if($weekday == 7){
  			$weekday   = 0; #start a new week
  			$calendar .= "</tr>\n<tr>";
  		}
  		if(isset($days[$day]) and is_array($days[$day])){
  			@list($link, $classes, $content) = $days[$day];
  			if(is_null($content))  $content  = $day;
  			$calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
  				($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
  		}
  		else $calendar .= "<td>$day</td>";
  	}
  	if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

  	return $calendar."</tr>\n</table>\n";
  }


	function generateSlug($phrase, $maxLength)
	{
		$result = strtolower($this->odstraniSumnike($phrase));

    // $result = preg_replace('/[^a-zA-Z0-9_ -]/s','',$result);
		$result = preg_replace("/[^a-z0-9\s-]/", "", $result);
		$result = trim(preg_replace("/[\s-]+/", " ", $result));
		$result = trim(substr($result, 0, $maxLength));
		$result = preg_replace("/\s/", "-", $result);

		return $result;
	}

	function odstraniSumnike($input='')
	{
		$input = str_replace('Č', 'c', $input);
		$input = str_replace('č', 'c', $input);
		$input = str_replace('Š', 's', $input);
		$input = str_replace('š', 's', $input);
		$input = str_replace('Ž', 'z', $input);
		$input = str_replace('ž', 'z', $input);

		return $input;
	}

	function utf_to_cp1250_array($input)
	{
	  foreach ($input as $key => $value)
	  {
	    $input[$key] = iconv('utf-8', 'cp1250', $value);
	  }
	  return $input;
	}


  function apiCallAsync($url, $post_data)
  {

    $ch = curl_init($url);
    
    $data = http_build_query($post_data);
 
    $curl = curl_init();                

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_POST, TRUE);
    curl_setopt ($curl, CURLOPT_POSTFIELDS, $data); 

    curl_setopt($curl, CURLOPT_USERAGENT, 'api');

    curl_setopt($curl, CURLOPT_TIMEOUT, 3); 
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl,  CURLOPT_RETURNTRANSFER, false);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);

    curl_exec($curl);   

    curl_close($curl);  
  }


  function post($url, $postVars = array())
  {
    //Transform our POST array into a URL-encoded query string.
    $postStr = http_build_query($postVars);
    //Create an $options array that can be passed into stream_context_create.
    $options = array(
        'http' =>
            array(
                'method'  => 'POST', //We are using the POST HTTP method.
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postStr //Our URL-encoded query string.
            )
    );
    //Pass our $options array into stream_context_create.
    //This will return a stream context resource.
    $streamContext  = stream_context_create($options);
    //Use PHP's file_get_contents function to carry out the request.
    //We pass the $streamContext variable in as a third parameter.
    $result = file_get_contents($url, false, $streamContext);
    //If $result is FALSE, then the request has failed.
    if($result === false){
        //If the request failed, throw an Exception containing
        //the error.
        $error = error_get_last();
        throw new Exception('POST request failed: ' . $error['message']);
    }
    //If everything went OK, return the response.
    return $result;
}


    function apiCall($url, $post_data)
    {

        //echo $url;

        //if(DE) return null;

        
        
        // if(!empty($_SESSION['token']))
        // {
        //     $post_data['token'] = $_SESSION['token'];
        //     $post_data['SID'] = $_COOKIE['PHPSESSID'];
        // }
        if (strpos($url, 'http') !== false)
        {
            $ch = curl_init($url);
        }
        else
        {
          $ch = curl_init('http://sanolaborcms.ha2net.com'.$url);
        }
 
        $post_data['token'] = API_SEC_KEY;


        $data = http_build_query($post_data); //echo $data;
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
       
            //curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        if(!empty($post_data['nosignal']))
        {
          curl_setopt($ch, CURLOPT_TIMEOUT, 5);
          curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        }

        
        //curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
        //curl_setopt($ch, CURLOPT_COOKIESESSION, true);

        //$tmpfname = dirname(dirname(__FILE__)).DS.'public'.DS.'cookiejar'.DS.$_COOKIE['PHPSESSID'].'.txt'; //echo $tmpfname; die();
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $tmpfname);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $tmpfname);
        //curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID='.$_COOKIE['PHPSESSID']);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded', 'Content-Length: ' . strlen($data)));
        $result = curl_exec($ch);

        if(curl_exec($ch) === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
        if(DE)
        {
            //echo 'http://cmsll.ha2net.com'.$url;
           //echo '<br />out: '; print_r($result);
           //return $result;
        }

        $result = json_decode($result);

        return $result;
    }

    function html_label($opt)
    {
        ?>
        <p>
        <label <?php  if(!empty($opt['id'])) echo 'id="'.$opt['id'].'"'; ?> <?php  if(!empty($opt['custom'])) echo $opt['custom']; ?> ><?php echo $opt['label']; ?></label><br />
        <?php if(!empty($opt['value'])) echo ($opt['value']);?>
        <?php  if(!empty($opt['span_id'])): ?>
        <span <?php echo 'id="'.$opt['span_id'].'"'; ?>></span>
        <?php endif; ?>
        </p>
        <?php
    }

    function html_input_hidden($opt)
    {
        ?>
        <input type="hidden" 
        
          name="<?php echo $opt['name']; ?>" 

          <?php echo $opt['name']; ?> value="<?php if(!empty($opt['value']) || $opt['value'] == '0') echo htmlspecialchars($opt['value']);?>" />
        <?php
    }

    function html_input($opt)
    {
        if(!empty($opt['datepicker']))
        {
            $opt['value'] = (!empty($opt['value'])) ? $this->date_to_slo($opt['value']) : date('d.m.Y');
        }
        ?>
        <p>
            <?php if(!empty($opt['label'])): ?>
                <label><?php echo $opt['label']; ?></label>
            <?php endif; ?>
        <input 
        <?php if(!empty($opt['maxlength'])): ?>
          maxlength="<?php echo $opt['maxlength']; ?>"
        <?php endif; ?>

        <?php if(!empty($opt['minlength'])): ?>
          minlength="<?php echo $opt['minlength']; ?>"
        <?php endif; ?>

        <?php if(!empty($opt['id'])): ?>
          id="<?php echo $opt['id']; ?>" 
        <?php endif; ?>

        <?php if(!empty($opt['rule'])): ?>
          data-rule-<?php echo $opt['rule']; ?>="true"
        <?php endif; ?>

        class="text-input form-control <?php if(!empty($opt['datepicker'])) echo ' datepicker '; ?> <?php if(!empty($opt['class'])) echo $opt['class']; ?>" type="<?php echo (!empty($opt['type'])) ?  $opt['type'] : 'text'; ?>" name="<?php echo $opt['name']; ?>" <?php  if(!empty($opt['custom'])) echo $opt['custom']; ?> <?php  if(!empty($opt['required'])) echo ' required '; ?> value="<?php if(!empty($opt['value']) || $opt['value'] == 0) echo htmlspecialchars($opt['value']);?>" />
        </p>
        <?php
    }

    function html_single_file_upload($opt)
    {
        ?>
        <p>
            <div class="input-append">
                <label><?php echo $opt['label']; ?></label><br />
                <div class="row">
                    <div class="col-lg-8 no-border">
                        <input class="html_single_file_upload text-input form-control  " readonly name="<?php echo $opt['name']; ?>" id="<?php echo $opt['name']; ?>" type="text" value="<?php if(!empty($opt['value'])) echo htmlspecialchars($opt['value']);?>" <?php  if(!empty($opt['required'])) echo ' required '; ?> />
                    </div>
                    <div class="col-lg-3 col-lg-offset-1 no-border">
                        <a href="/public/resources/filemanager/dialog.php?type=0&field_id=<?php echo $opt['name']; ?>&relative_url=1" class="btn iframe-btn" type="button">Izberi</a>
                        <a class="delete_single_file_upload" data-input_id="<?php echo $opt['name']; ?>" href="#" title="Izbriši"><i class="icon-trash icon-1_5x"></i></a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </p>
        <?php
    }


    function html_checkbox($opt)
    {
        ?>
        <label>
            <input type="hidden" value="0" name="<?php echo $opt['name']; ?>" /> 
            <input type="checkbox"
            name="<?php echo $opt['name']; ?>"
            id="<?php echo $opt['name']; ?>"
            <?php if(!empty($opt['required'])) echo ' required '; ?>
            <?php if($opt['status']) echo ' checked="true" ';?>
            value="<?php echo (!empty($opt['value'])) ? $opt['value'] : 1; ?>" />
            <?php echo $opt['label']; ?>
        </label>
        <?php
    }

    function html_editor($opt)
    {

        ?>
        <p>
        <label><?php echo $opt['label']; ?></label>
        <textarea style="height:600px;" class="editor_short ckeditor" id="editor_<?php echo $opt['name']; ?>" name="<?php echo $opt['name']; ?>" rows="25" cols="40"><?php if(!empty($opt['value'])) echo $opt['value'];?></textarea>
        </p>
        <script>
         
          var config = {};
          config.height = '<?php echo (!empty($opt['height'])) ? $opt['height'] : 300; ?>px';

          <?php if(!empty($opt['l70'])): ?>
          config.contentsCss = ['//use.typekit.net/fpz7kdw.css', 'https://fonts.googleapis.com/icon?family=Material+Icons', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', 'http://70let.ha2net.com/css/site.min.css'];
          <?php endif; ?>

          var editor_<?php echo $opt['name']; ?> = CKEDITOR.replace('editor_<?php echo $opt['name']; ?>', config);


            editor_<?php echo $opt['name']; ?>.on('save', function(evt) {
                //console.log( 'Total bytes: ' + evt.editor.getData().length );
                $(".active .save_button_keep_state").trigger("click");
                return false;
            });
        </script>
        <?php
    }

    function html_tags($opt)
    {
        ?>
        <input type="hidden" name="tags[<?php echo $opt['tip']; ?>][]" value="0"/>
        <?php
        $this->html_select2(['id' => 'tags', 'label' => 'Ključne besede<br /><small>novo ključno besedo potrdi z enter</small>', 'name' => 'tags['.$opt['tip'].'][]', 'multiple' => true, 'tags' => true, 'url' => '/webapp/s2?c=Tag&m=getAllSelect', 'get_selected' => ['id' => $opt['id'] , 'c' => 'Tag', 'm' => 'getPovezaniSelected', 'tip' => $opt['tip']]]);
    }

    function html_select2($opt)
    {
        $selected_list = [];
        //print_r($opt);
        if(!empty($opt['get_single']))
        {
            $selected_kat = $this->apiCall('/webapp/select2Single', $opt['get_single']); //if(DE) print_r($selected_kat);
            if($selected_kat && $selected_kat->success && $selected_kat->data)
            {
                //print_r($selected_kat->data);

                // Old version
                // $selected_list[] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->naziv];

                // New version
                if(isset($selected_kat->data->naziv) && !empty($selected_kat->data->naziv)) {
                  $selected_list[] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->naziv];
                } else {
                  $selected_list[] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->name];
                }
            }
        }

        if(!empty($opt['get_list']))
        {
            $selected_kat = $this->apiCall('/webapp/select2List', $opt['get_list']); //if(DE) print_r($selected_kat);
            if($selected_kat && $selected_kat->success)
            {
                foreach($selected_kat->data as $s)
                {
                    $selected_list[] = ['id' => $s->id, 'text' => $s->text];
                }
            }
        }

        if(!empty($opt['get_selected']))
        {

            //$selected_kat = $this->apiCall('/webapp/base/call', ['id' => $opt['get_selected']['id'], 'c' => $opt['get_selected']['c'], 'm' => $opt['get_selected']['m']]);
            $selected_kat = $this->apiCall('/webapp/base/call', $opt['get_selected']); 
     

            if($selected_kat && $selected_kat->success)
            {
                if(is_object($selected_kat->data))
                {
                    //$opt['selected'] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->naziv];
                    $selected_list[] = ['id' => $selected_kat->data->id, 'text' => $selected_kat->data->naziv];
                }
                else
                {
                    foreach($selected_kat->data as $s)
                    {
                        $selected_list[] = ['id' => $s->id, 'text' => $s->text];
                    }
                }
            }
        }

        if(!empty($opt['selected']))
        {
            $selected_list[] = $opt['selected'];
        }

        if(empty($selected_list) && isset($opt['get_single']['table']) && $opt['get_single']['table'] == 'dobavitelj_users'){
            $selected_list[] = ['id' => $opt['get_single']['id'], 'text' => $opt['get_single']['dobavitelj_name']];
        }
        ?>
        <p>
            <input type="hidden" name="<?php echo $opt['name']; ?>" value="0"/>
            <label><?php echo $opt['label']; ?></label>
            <select
                <?php if(!empty($opt['custom'])): ?> <?php echo $opt['custom']; ?> <?php endif; ?>
                <?php if(!empty($opt['id'])): ?>id="<?php echo $opt['id']; ?>"<?php endif; ?>
                <?php if(!empty($opt['required'])) echo ' required '; ?>
                <?php if(!empty($opt['multiple'])) echo ' multiple="multiple" '; ?>
                <?php if(!empty($opt['tags'])) echo ' data-tags="true" '; ?>
                class="form-control select2<?php if(!empty($opt['multiple'])) echo '_multiple'; ?>"
                name="<?php echo $opt['name']; ?>"
                placeholder="gfd4324"
     
                data-ajax--url="<?php echo $opt['url']; ?>" >

                <?php
                if($selected_list):
                    foreach($selected_list as $selected):
                    ?> <option selected value="<?php echo $selected['id'] ?>"><?php echo $selected['text'] ?></option> <?php
                    endforeach;
                endif; ?>

            </select>
        </p>

        <?php
    }

    function html_save_button($data, $opt = [])
    {
        ?>
 
        <p class="main_buttons">
            <img src="/public/resources/images/ajax-loader.gif" class="ajaxLoader hide" />
            <?php if(empty($opt) || (!empty($opt) && !empty($opt['save']))): ?><?php //if($data): ?><input data-reload="<?php if(!empty($opt['reload'])) echo '1';  ?>" data-form_name="<?php if(!empty($opt['form_name'])) echo $opt['form_name'];  ?>" data-close="0" class="btn btn-success button save_button save_button_keep_state" type="submit" value="Shrani" /><?php //endif; ?><?php endif; ?>
            <?php if(empty($opt) || (!empty($opt) && !empty($opt['save_close']))): ?><input data-reload="<?php if(!empty($opt['reload'])) echo '1';  ?>" data-form_name="<?php if(!empty($opt['form_name'])) echo $opt['form_name'];  ?>" data-close="1" class="btn btn-warning button save_button" type="submit" value="Shrani in zapri" /><?php endif; ?>
            <?php if(empty($opt) || (!empty($opt) && !empty($opt['close']))): ?><input data-reload="<?php if(!empty($opt['reload'])) echo '1';  ?>" data-form_name="<?php if(!empty($opt['form_name'])) echo $opt['form_name'];  ?>" class="btn btn-danger button close_edit" type="submit" value="Zapri" /><?php endif; ?>

            <?php if(!empty($opt) && !empty($opt['discard'])): ?><input data-reload="<?php if(!empty($opt['reload'])) echo '1';  ?>"  data-form_name="<?php if(!empty($opt['form_name'])) echo $opt['form_name'];  ?>" class="btn btn-danger button discard_changes" type="submit" value="Zavrni" /><?php endif; ?>

        </p>
        <?php
    }

    function html_file_upload($data)
    {
        $limit = (!empty($data['limit'])) ? $data['limit'] : 50;
        ?>
        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Dodaj ...</span>
            <input id="fileupload<?php echo $data['id']; ?>" type="file" name="files[]" data-url="<?php echo $data['url']; ?>" <?php if($limit > 1) echo ' multiple '; ?> >
        </span>
        <br>
        <br>
        <div id="progress<?php echo $data['id']; ?>" class="progress"><div class="progress-bar progress-bar-success"></div></div>
        <br />
        <table class="uf_table" id="uf_box<?php echo $data['id']; ?>"></table>
        <script>
        initFileUpload('<?php echo $data['id']; ?>', '<?php echo $data['item_id']; ?>', '<?php echo $data['type']; ?>', <?php echo $limit; ?>); 
        
        
        </script>
        <?php
    }

    function get_combinations($arrays) 
    {
      $result = array(array());
      foreach ($arrays as $property => $property_values) 
      {
        $tmp = array();
        foreach ($result as $result_item) 
        {
          foreach ($property_values as $property_value) 
          {
            $tmp[] = array_merge($result_item, array($property => $property_value));
          }
        }

          if($tmp)
          {
            $result = $tmp;
          }
        
      }

      return $result;
    }

    function sort_array($input_array)
    {
      ksort($input_array);
      //print_r($input_array);
      foreach ($input_array as $key => $value) 
      {
        sort($value);
        $input_array[$key] = $value;
        # code...
      }

      return $input_array;

    }

}

