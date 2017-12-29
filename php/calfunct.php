<?
function clean($value = "") {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    
    return $value;
  }
function Calics($callinc) {
if(preg_match('/https/i', $callinc))
{
$caltest =getSslPage($callinc);
}
else {
	$caltest =file_get_contents($callinc);
	$caltest= preg_replace('/"/', "'", $caltest); 
	}
$testarray=preg_split("/[\n]/", $caltest);

//pre($callinc);

//фикс перевода строки
foreach ($testarray as $key =>$value) 
{
	$value=clean($value); 
	$value= preg_replace('/\\\n/', ' ', $value); 
	$k=$k+1; 
	if (!preg_match('/^[A-Z]/', $value))
	{
		
		$value=ftrim($value);
		$x=$x+1;
		$k=$k-1;
		$key=$key-$x;
		$testarray[$key]="$testarray[$key]"."$value";
		$testarray[$key]= preg_replace('/\\\n/', ' ', $testarray[$key]); 
		//pre("$x $key $testarray[$key]");
		
	}
	else {
		$x=0;
		$testarray2[$k]=$value;
		}
}

//pre($testarray); 
//pre($testarray2); 
$x=0;


foreach ($testarray as $key =>$value) 
{
	$value=ftrim($value);
	//$value= preg_replace('/.n/', '  ', $value); 
	$value= preg_replace('/\\r/', ' ', $value); 
	$value= preg_replace('/\'/', '', $value); 
	$value= preg_replace('/\\\/', ' ', $value); 
	//название календаря 
	if (preg_match('/X-WR-CALNAME/', $value)) 
	{
		$caln=explode(':', $value);
		$calname=$caln[1];
		$result[$callinc]['calname']=$calname;
	}
//комментарий или описание календаря 
	if (preg_match('/X-WR-CALDESC/', $value)) 
	{
		$cald=explode(':', $value);
		$caldesc=$cald[1];
		$result[$callinc]['caldesc']=$caldesc;
	}
//событие календаря 
	if (preg_match('/BEGIN:VEVENT/', $value)) 
	{
		$x=$x+1;
		if($x==1) $y=$key; 
	}
	if ($x>0)
	{
	//дата начала
	if ((preg_match('/DTSTART/', $value)) && ($x>0))
	{
		$calx=explode(':', $value);
		$calxd=preg_replace('/[TZID]/', ' ', $calx[1]);
		$calxd=explode(' ', $calxd);
		
        preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})/", $calxd[0], $res);
        $daten=implode ('-', (array_slice($res, 1)));
       // pre($daten);
        preg_match("/([0-9]{2})([0-9]{2})/", $calxd[1], $res);
        $timen=implode (':', (array_slice($res, 1)));
       // pre($timen);
       
       
		if (!preg_match('/TZID/', $value))
		{
			$caldey="$daten $timen";
			$date= new DateTime($caldey, new DateTimeZone('Europe/London'));
			$date->setTimezone(new DateTimeZone('Europe/Kiev'));
			$calydey=$date->format('Y-m-d H:i');
			$daten=$date->format('Y-m-d');
			$timen=$date->format('H:i');
		}
       
       
		$result[$callinc][$x]['dates']=$daten;
$result[$callinc][$x]['times']=$timen;
	}
	//дата окончания
	if (preg_match('/DTEND/', $value)) 
	{
		$calx=explode(':', $value);
		$calxd=preg_replace('/[TZID]/', ' ', $calx[1]);
		$calxd=explode(' ', $calxd);
		
        preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})/", $calxd[0], $res);
        $daten=implode ('-', (array_slice($res, 1)));
        //pre($daten);
        preg_match("/([0-9]{2})([0-9]{2})/", $calxd[1], $res);
        $timen=implode (':', (array_slice($res, 1)));
        //pre($timen);
        
		if (!preg_match('/TZID/', $value))
		{
			$caldey="$daten $timen";
			$date= new DateTime($caldey, new DateTimeZone('Europe/London'));
			$date->setTimezone(new DateTimeZone('Europe/Kiev'));
			$daten=$date->format('Y-m-d');
			$timen=$date->format('H:i');
		}
       
		$result[$callinc][$x]['daten']=$daten;
$result[$callinc][$x]['timen']=$timen;
	}
	//имя события
	if (preg_match('/SUMMARY/', $value)) 
	{
		$caln=explode(':', $value);
		$calname=$caln[1];
		$result[$callinc][$x]['name']=$calname;
	}
	//подробности события
	if (preg_match('/DESCRIPTION/', $value)) 
	{
		$caln=explode('N:', $value);
		$calname=trim($caln[1]);
		$result[$callinc][$x]['desc']=$calname;
	}
	//правила повторения
	if (preg_match('/RRULE/', $value)) 
	{
		$caln= explode(':', $value);
		$calname=explode(';', $caln[1]);
		//pre($calname);
		//извлекаем правила
		unset($calrules); 
		foreach ($calname as $key=>$val) 
		{
			$calrul=explode('=', $val);
			$cx=$calrul[0];
			$calrules[$cx]=$calrul[1];
		}
		$result[$callinc][$x]['rule']=$calrules;
	}
	//идентификатор события
	if (preg_match('/UID/', $value)) 
	{
		$caln= explode(':', $value);
		$calname=$caln[1];
		//pre($calname);
		$result[$callinc][$x]['uid']=$calname;
	}
	//место локация 
	if (preg_match('/LOCATION/', $value)) 
	{
		$caln= explode(':', $value);
		$calname=$caln[1];
		$s=$key+1;
		if (!preg_match('/:/', $testarray[$s]))
		{
			$loc= trim($testarray[$s]);
		$calname=$caln[1].$loc;
		}
		$calname=preg_replace('/[\r]/', '', $calname); 
		$result[$callinc][$x]['location']=$calname;
	}
	//даты исключений из календаря 
	if (preg_match('/EXDATE/', $value)) 
	{
		$calx=explode(':', $value);
		$calxd=preg_replace('/[TZ]/', ' ', $calx[1]);
		$calxd=explode(' ', $calxd);
		
        preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})/", $calxd[0], $res);
        $daten=implode ('-', (array_slice($res, 1)));
        //pre($daten);
        preg_match("/([0-9]{2})([0-9]{2})/", $calxd[1], $res);
        $timen=implode (':', (array_slice($res, 1)));
        //pre($timen);
		$result[$callinc][$x]['exdate'][]=$daten;
	}
	
	
	}//x>0
}
$test=array_slice($testarray, $y);
//pre($test); 
    return $result;
} //конец функции

//функция обработки запроса к https 
function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


//функция обработки запроса к https 
function getSinfo($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_getinfo($ch,CURLINFO_FILETIME);
    curl_close($ch);
    return $result;
}

?>