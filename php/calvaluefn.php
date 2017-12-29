<?
//  проверка файла
$filename = "local.ini";
if (file_exists($filename)) 
{   
// echo " <h2>файл есть </h2>";
// включаем файл конфигурации 
	include ("$filename");
} 
else 
{
}


//  проверка файла
$filename = "php/bd/Tango_of_Liberty.ics";
$fsize=filesize($filename); //размер файла
  
if (file_exists($filename))  {
$dfile=date("m d Y H", filectime($filename));
if (($dfile!=date("m d Y H")) || ($fsize==0))
$calreload=1;
}


//подключаем список календарей
include ('php/bd/calendars.bd');


 
if ((!empty ($calreload)) )
{
	foreach ($calselect as $key=>$value) 
	{
		$result=getSslPage($value);
//  пишем файл

$f = fopen("php/bd/$key.ics", "w+"); 

fwrite($f,"$result"); 

fclose($f); 
	}

unset($_SESSION['calendars']); 
info("удаляем сессию");
$Calinfo ='база обновлена';
}
unset($_SESSION['calendars']); 
if (empty ($_SESSION['calendars']))
{
foreach($calselect as $key=>$val)
{
	Calics("php/bd/$key.ics"); 
}
}

  //блок проверки состояния сессии
if (isset ($_SESSION['calendars']))
{
	info(" сессия не стерта ");
	if (empty ($_SESSION['calendars']))
	info(" сессия пустая");
	else 
	info(" сессия не пустая ");
}

$calendars=$_SESSION['calendars']; 


if (empty ($lang))
{
	$lang=ru;
}

// языковой массив календаря
$calmonth[ua]= array (
"01"=> "січень",
"02"=> "лютий",
"03"=> "березень ",
"04"=> "квітень ",
"05"=> "травень ",
"06"=> "червень", 
"07"=> "липень", 
"08"=> "серпень", 
"09"=> "вересень", 
"10"=> "жовтень",
"11"=> "листопад",
"12"=> "грудень");

$namdn[ua]= array (
"1"=> "Пн",
"2"=> "Вт",
"3"=> "Ср",
"4"=> "Чт",
"5"=> "Пт",
"6"=> "Сб",
"7"=> "Нд");

$calmonth[ru]= array (
"01"=> "январь",
"02"=> "февраль",
"03"=> "март",
"04"=> "апрель",
"05"=> "май",
"06"=> "июнь", 
"07"=> "июль", 
"08"=> "август", 
"09"=> "сентябрь", 
"10"=> "октябрь",
"11"=> "ноябрь",
"12"=> "декабрь");

$namdn[ru]= array (
"1"=> "Пн",
"2"=> "Вт",
"3"=> "Ср",
"4"=> "Чт",
"5"=> "Пт",
"6"=> "Сб",
"7"=> "Вс");

$calmonth[en]= array (
"01"=> "January ",
"02"=> "February ",
"03"=> "March  ",
"04"=> "April  ",
"05"=> "May ",
"06"=> "June ", 
"07"=> "July ", 
"08"=> "August ", 
"09"=> "September ", 
"10"=> "October ",
"11"=> "November ",
"12"=> "December ");

$namdn[en]= array (
"1"=> "Mo ",
"2"=> "Tu ",
"3"=> "We ",
"4"=> "Th ",
"5"=> "Fr ",
"6"=> "Sa",
"7"=> "Su ");



//    присваиваем значения
 // переменные 
$calmdey=date("m"); // текущий месяц 
$caljdey=date("n"); // текущий месяц 
$calyear=date("Y");  //  текущий год
$cald=date("d");  //  текущий день месяца 
$caldw=date("N");  //  текущий день недели
$caln=$calwdate[$caldw];  //  форматирование  дня недели для смены 0 на 7
$calxdate=date("Y.m.d");  // для подсветки текущего дня
//  получаем количество дней
$calvdey=date("t"); 

//  для вычисления дня недели первого дня текущего месяца
$cald=$cald-1;  
$caldnf = date("N",strtotime("-$cald days"));


/////////////////////////
//листаем календарь
if (!empty ($next))
{
	//echo "<h2>листает $next </h2>";
	$date = new DateTime($next);
	
	$calmdey=$date->format("m"); // текущий месяц 
$caljdey=$date->format("n"); // текущий месяц 
$calyear=$date->format("Y");  //  текущий год
$cald=$date->format("d");  //  текущий день месяца 
$caldw=$date->format("N");  //  текущий день недели
$caln=$calwdate[$caldw];  //  форматирование  дня недели для смены 0 на 7

//  получаем количество дней
$calvdey=$date->format("t"); 


}
////////////////////


//echo "<h2>  $calyear </h2>";
//обработка данных событий календаря 
//pre($calendars); 



$date2 = new DateTime("$calyear"."-$calmdey"."-$calvdey");
$date=$date2; 
$date->modify("+1 day");
$calnext=$date->format('Y-m-d');

$date3 = new DateTime("$calyear"."-$calmdey"."-01");
//  для вычисления дня недели первого дня текущего месяца
$caldnf = $date3->format('N');

$date=$date3; 
$date->modify("-1 day");
$calback=$date->format('Y-m-d');


$jsobj[]='var jscalendar = { ';
foreach ($calendars as $k=>$values)
{
	$calname =ftrim($values['calname']);
	$jsobj[]=" '$calname': {";
	foreach($values as $key=>$value)
	{
		if ($key!='calname' && $key!='caldesc') 
		{
			$keyc=$key; 
			$jsobj[]="    '$key': {";
			//обработка события
			$uid=$value['uid'];
			$datev=$value['dates'];
			$dateb=$value['daten'];
			$times=$value['times'];
			$timen=$value['timen'];
			$jsval=$value['location'];
			$jsobj[]="     'location': '$jsval', ";
			$jsval=htmlspecialchars($value['desc']);
			$jsobj[]="     'desc': '$jsval', ";
			$date4 = new DateTime("$dateb");
			$date5=$date4;
			$date4->modify("-1 day");
			$name=ftrim($value['name']);
			$date1 = new DateTime($datev);
			//массив исключений 
			unset($exdate); 
			$jsobj[]="     name: '$name', ";
			
			
			if (!empty ($value['exdate']))
			{
				foreach($value['exdate'] as $key=>$val) 
				{
					$exdate[$val]=$val; 
				}
			}
			if ($date1<=$date2) // условие что дата события начинается раньше последнего дня показываемого месяца
			{
				


// событие которое идет несколько дней
			if ($date1 < $date4 )
				{
					$dx=$value['daten'];
					$dateb=$date4->format('Y-m-d H:i');
					$datec=$date1->format('Y-m-d H:i');
					$jsdat=$date5->format('Y-m-d');
					$jsdaten="$jsdat"." $timen";
					$jsdates="$datev"." $times";
					$jsobj[]="     'time': 'с $jsdates до $jsdaten', ";
					
					//echo "<h2>$datec test $dateb $name </h2>";
					
						$interval = $date1->diff($date4);
					unset($datein);
$datein=$interval->format('%R%a');
//pre("повтор $datein $name ");
$datex=$datein;
						
					if (empty($value['rule']))
					{
						
						for ($x=0; $x<=$datex; $x++)
						{
							
							if (empty ($exdate[$datev]))
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
			$date = new DateTime($datev);
						$date->modify("+ 1 day");
						$datev=$date->format('Y-m-d');
						}
					}
					
				}
			
				
				
				
				
			if (empty ($exdate[$datev]))
			{
				$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
			}
			info ("$datev test1 $name");
			
			if ((empty ($value['rule'])) && (empty ($datein)))
			{
				$jsobj[]="     'time': 'с $times до $timen', ";
			}
			if (!empty ($value['rule']))
			{
				unset($vrul); //очищает переменную
				$vrul=$value['rule'];
				//pre($vrul); 
				// определяем интервал повтора
				if (empty($vrul['INTERVAL']))
				{
					$in=1;
				}
				else {
					$in=trim($vrul['INTERVAL']);
				}
				
				//определяем крайнюю дату 
				
				if (!empty($vrul['UNTIL']))
				{
					preg_match("/([0-9]{4})([0-9]{2})([0-9]{2})/", $vrul['UNTIL'], $res);
			        $daten=implode ('-', (array_slice($res, 1)));
					if ($daten > $date2) 
					{
						$daten="$calyear"."-$calmdey"."-$calvdey"; 
					}
				}
				//если условие это количество повторов
				
				else if (!empty ($vrul['COUNT']))
				{ 
					$xf=$vrul['COUNT'];
					$date = new DateTime($datev);
					$test=$date->format('Y-m-d');
					//echo "<p> тест $test </p>";
				//включатель на дни 
				if ($vrul['FREQ']=='DAILY')
				{
					for ($x=0; $x<$xf; $x++)
					{
						//добавляем день 
						$date->modify("+ $in day");
						//pre("test $x");
						if ($date>$date2) 
						$x=$xf;
					}
				}
				
				//включатель на неделю
				if ($vrul['FREQ']=='WEEKLY')
				{
					for ($x=0; $x<$xf; $x++)
					{
						$ix=$in*7;
						//добавляем день 
						$date->modify("+$ix  day");
						if ($date>$date2) 
						$x=$xf;
					}
				}
				//включатель на месяц
				if ($vrul['FREQ']=='MONTHLY')
				{
					$m=$date->format('m');
					$yx=$date->format('Y');
					//pre("test $m"); 
					for ($x=0; $x<$xf; $x++)
					{
						//pre("test $m"); 
					$m=$m+1;
					if ($m>12)
					$m=1; $yx=$yx+1;
					
					$dates="$yx"."-$m"."-$calvdey";
					$date = new DateTime($dates);
						if ($date>$date2) 
						$x=$xf;
					}
					//pre($dates); 
				}
				
				$daten=$date->format('Y-m-d');
				}
				
				else { 
					$daten="$calyear"."-$calmdey"."-$calvdey"; 
					
					//echo "<h2>$datev test $daten </h2>";
				}
				
				
				
				
				//включатель на дни 
				if ($vrul['FREQ']=='DAILY')
				{
					$jsobj[]="     'time': 'с $times до $timen', ";
					$datevf = new DateTime($datev);
					$datenf = new DateTime($daten);
					while ($datevf<$datenf) 
					{
						
						//добавляем день 
						$date = new DateTime($datev);
						$date->modify("+ $in day");
						$datev=$date->format('Y-m-d');
						//echo "<p>$datev </p>";
						$datevf = new DateTime($datev);
						if ($date>=$date3) {
				if (empty ($exdate[$datev]))
						$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						//echo "<p>$datev test2 $name </p>";
						}
						}
					}
				//включатель на неделю
				if ($vrul['FREQ']=='WEEKLY')
				{
					$jsobj[]="     'time': 'с $times до $timen', ";
					//echo "<h2>$datev test $daten </h2>";
					$datevf = new DateTime($datev);
					$datenf = new DateTime($daten);
					while ($datevf<$datenf) 
					{
						//echo "<h2>$name $datev test $daten $ix</h2>";
						$ix=$in*7;
						//добавляем день 
						$date = new DateTime($datev);
						$date->modify("+$ix  day");
						$datev=$date->format('Y-m-d');
						//echo "<p>$datev $ix </p>";
						$datevf = new DateTime($datev);
						if ($date>=$date3) {
				if (empty ($exdate[$datev]))
						$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						//echo "<p>$datev test2 $name </p>";
						}
						}
					
				}
				//включатель на месяц
				if ($vrul['FREQ']=='MONTHLY')
				{
					$jsobj[]="     'time': 'с $times до $timen', ";
					//echo "<h2>$datev test $daten </h2>";
					$datevf = new DateTime($datev);
					$datenf = new DateTime($daten);
					if ((($datevf<=$datenf) && ($datevf<$date3)) || (!empty ($datein)) )
					{
						
					if (!empty ($datein)) 
					{
						$datex=$datev; 
						for ($x=0; $x<=$datein; $x++)
						{
							
							$date = new DateTime($datev);
						$date->modify("+ 1 day");
						$datev=$date->format('Y-m-d');
						if (empty ($exdate[$datev]))
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
				//pre("test $datev $name $datein дней повтора ");
						}
						$datev=$datex; 
					}
						$test=$date3->format('Y-m-d');
						$d=$datevf->format('d');
						
						$datev="$calyear"."-$calmdey"."-$d";
						//echo "<p>$datev  test $test</p>";
				if (empty ($exdate[$datev]))
					$calvalue[$datev]['name']["$calname"."_$keyc"]="$name"; 
					
					if (!empty ($datein)) 
					{
						
						for ($x=0; $x<=$datein; $x++)
						{
							$date = new DateTime($datev);
						$date->modify("+ 1 day");
						$datev=$date->format('Y-m-d');
						if (empty ($exdate[$datev]))
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
				//pre("test $datev $name $datein дней повтора ");
						}
					}
						}
				}
				
				//включатель на год
				if ($vrul['FREQ']=='YEARLY')
				{
					$jsobj[]="     'time': 'с $times до $timen', ";
					//echo "<h2>$datev test $daten </h2>";
					$datevf = new DateTime($datev);
					$datenf = new DateTime($daten);
					if (($datevf<=$datenf) && ($datevf<$date3))
					{
						$test=$date3->format('Y-m-d');
						$d=$datevf->format('d');
						$cm=$datevf->format('m');
						
						$datev="$calyear"."-$cm"."-$d";
						//echo "<p>$datev  test $test</p>";
				if (empty ($exdate[$datev]))
					$calvalue[$datev]['name']["$calname"."_$keyc"]="$name"; 
						}
				}
				
			}
			
			//$jsobj[]="     'empty': 'test' ";
			}
			$jsobj[]="    },";
		}
	}
	$jsobj[]="  },";
}
$jsobj[]="}";

$jscal=implode('
', $jsobj);
//pre($jscal);
echo "
<script>
	$jscal 
</script >";

?>