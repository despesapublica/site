<?php

function formatUUID($uuid, $binary=true){
	if($binary)
	{
		$uuidReadable = unpack("h*",$uuid);
	}
	else
	{
		$uuidReadable = $uuid;
	}
	$uuidReadable = preg_replace("/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", "$1-$2-$3-$4-$5", $uuidReadable);
	$uuidReadable = array_merge($uuidReadable);
	
	return $uuidReadable;
}

function floatvalue($value) {
     $value_clean = preg_replace("/[^0-9.,]/","",$value);
      
     $lastIndexP = strrpos($value_clean, '.');
     $lastIndexV = strrpos($value_clean, ',');
     
     if($lastIndexP>$lastIndexV)
     {
     	$value_clean[$lastIndexP] = 'X';
     	$value_clean = preg_replace("/[^0-9X]/","",$value_clean);
     	$value_clean = str_replace('X', '.', $value_clean);
     }
     else if($lastIndexV>$lastIndexP)
     {
     	$value_clean[$lastIndexV] = 'X';
     	$value_clean = preg_replace("/[^0-9X]/","",$value_clean);
     	$value_clean = str_replace('X', '.', $value_clean);
     }
     return $value_clean;
} 

function datevalue($value) {
     $value_clean = trim($value);
     
     if(empty($value_clean))
     	return null;
     	
     list($p1, $p2, $p3) = explode('-', $value_clean);
     
     if(empty($p1) && empty($p2) && empty($p3))
     {
     	list($p1, $p2, $p3) = explode('/', $value_clean);
     }
     
     //Year-Month-Day
     if(strlen($p1) == 4)
     {
     	return $p1.'-'.$p2.'-'.$p3;
     }
     else if(strlen($p3) == 4)
     {
     	return $p3.'-'.$p2.'-'.$p1;
     }
     
     return $value_clean;
} 

function formatMoney($number, $fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f', $number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
} 

function my_sleep($seconds)
{
    $start = time();
    $now = $start;
    $dif = 0;
    $i = 0;
    while($i<40 && $dif < $seconds) {
    	
    	$dif = $seconds - $dif;
    	sleep($dif);
        $now = time();
        $dif = $now-$start;
        $i++;
    }
} 


function fakeUserAgentHttpGet($address) {
	$agents[] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0)";
	$agents[] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
	$agents[] = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0;)";
	$agents[] = "Opera/9.63 (Windows NT 6.0; U; ru) Presto/2.1.1)";
	$agents[] = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5";
	$agents[] = "Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.18) Gecko/20081203 Firefox/2.0.0.18";
	$agents[] = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12 (.NET CLR 3.5.30729)";
	$agents[] = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-us) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/3.2.1 Safari/525.27.1";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $address);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE , true);
    curl_setopt($ch, CURLOPT_HEADER , true);
	curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
	//curl_setopt ( $this -> ch , CURLOPT_TIMEOUT, 20);
	$data = curl_exec($ch);
	
	$response = curl_exec($ch);
	$error = curl_error($ch);
	$result = array( 'header' => '',
					 'body' => '',
					 'curl_error' => '',
					 'http_code' => '',
					 'last_url' => '');
	if ( $error != "" )
	{
		$result['curl_error'] = $error;
		return $result;
	}
   
	$header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
	$result['header'] = substr($response, 0, $header_size);
	$result['body'] = substr( $response, $header_size );
	$result['http_code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	$result['last_url'] = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	return $result;
}

?>