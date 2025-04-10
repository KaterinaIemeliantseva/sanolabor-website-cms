<?php
namespace library;

class Helpers
{

  function mbUnserialize($serial_str)
	{
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
		return unserialize($out);
	}

  function convertChars($x)
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
     $rez=str_replace("+", " ", $rez);

     return $rez;
   }

   function convertCharsBack($x)
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

    function parseDateTime($date)
    {
      $out = explode(' ', $date);
      if(!isset($out[0]))
      {
        $out[0] = '';
      }

      if(!isset($out[1]))
      {
        $out[1] = '';
      }

      return $out;
    }

   function mbCutText($string, $length, $delimiter = ' ', $postfix = '')
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


  function getTax ($tax, $price, $dec = 2)
  {
    $tax_factor = (($tax * 100) / (100 + $tax)) / 100;
    $tax_amount = $price * $tax_factor;

    return number_format($tax_amount, $dec);

  }

  function dateToSlo($date)
  {
  	$chunk = explode("-", $date);

  	$newdate = $chunk[2] . "." . $chunk[1] . "." . $chunk[0];
  	return($newdate);
  }

  

}
