<?
 $css=date("m-d-Y-H-i", filectime('css/calendar.css'));

 //таблица стилей
echo "<link rel='stylesheet' href='css/calendar.css?$css' type='text/css'/>";

if (empty ($lang))
{
	$lang=ru;
}
echo "<script > var lang='$lang'; </script>";

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

if (!empty ($calendars)) 
{
$jsobj[]='var jscalendar = { ';
foreach ($calendars as $keycal=>$valuecal)
{
foreach ($valuecal as $k=>$values)
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
			$jsobj[]="     'name': '$name', ";
			
			
			if (!empty ($value['exdate']))
			{
				foreach($value['exdate'] as $key=>$val) 
				{
					$exdate[$val]=$val; 
				}
			}
			
			// событие которое идет несколько дней для JS 
			if ($date1 < $date4 )
				{
					
					$jsdaten="$dateb"." $timen";
					$jsdates="$datev"." $times";
					if ($lang=='en') 
					$jsobj[]="     'time': 'in $jsdates to $jsdaten', ";
					else
					$jsobj[]="     'time': 'с $jsdates до $jsdaten', ";
					//info(" $name: 'с $jsdates до $jsdaten', ");
					}
					else {
						if ($lang=='en') 
						$jsobj[]="     'time': 'in $times to $timen', ";
						else 
						$jsobj[]="     'time': 'с $times до $timen', ";
						//info(" $name:  'с $times до $timen', ");
					}
			
			
			if ($date1<=$date2) // условие что дата события начинается раньше последнего дня показываемого месяца
			{
				


// событие которое идет несколько дней
			if ($date1 < $date4 )
				{
					$dx=$value['daten'];
					$dateb=$date4->format('Y-m-d H:i');
					$datec=$date1->format('Y-m-d H:i');
					
					
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
							
							if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
						
			$date = new DateTime($datev);
						$date->modify("+ 1 day");
						$datev=$date->format('Y-m-d');
						}
						}
					}
					
				}
				
			
				
				
				
				
			if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
			{
				$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
						}
			}
			//info ("$datev test1 $name");
			
			if ((empty ($value['rule'])) && (empty ($datein)))
			{
				
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
				if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
						$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
						//echo "<p>$datev test2 $name </p>";
						}
						}
						}
					}
				//включатель на неделю
				if ($vrul['FREQ']=='WEEKLY')
				{
					
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
				if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
						$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
					}
						//echo "<p>$datev test2 $name </p>";
						}
						}
					
				}
				//включатель на месяц
				if ($vrul['FREQ']=='MONTHLY')
				{
					
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
						if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
				//pre("test $datev $name $datein дней повтора ");
				}
						}
						$datev=$datex; 
					}
						$test=$date3->format('Y-m-d');
						$d=$datevf->format('d');
						
						$datev="$calyear"."-$calmdey"."-$d";
						//echo "<p>$datev  test $test</p>";
				if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
					$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
					$calvalue[$datev]['uid'][$uid]=$name;
					}
					if (!empty ($datein)) 
					{
						
						for ($x=0; $x<=$datein; $x++)
						{
							$date = new DateTime($datev);
						$date->modify("+ 1 day");
						$datev=$date->format('Y-m-d');
						if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
						
			$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
						$calvalue[$datev]['uid'][$uid]=$name;
				//pre("test $datev $name $datein дней повтора ");
				}
						}
					}
						}
				}
				
				//включатель на год
				if ($vrul['FREQ']=='YEARLY')
				{
					
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
				if ((empty ($exdate[$datev])) && (empty ($calvalue[$datev]['uid'][$uid])))
				{
					$calvalue[$datev]['name']["$calname"."_$keyc"]=$name; 
					$calvalue[$datev]['uid'][$uid]=$name;
					}
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
}
$jsobj[]="}; ";

//pre($calendars); 

//pre($calvalue); 

foreach ($jsobj as $key =>$val) 
{
	//pre($val); 
}

$jscal=implode('
', $jsobj);
//pre($jscal);
echo "<script> 
$jscal </script>";
//$caption="$caption"." <font color='red'>есть данные для обработки </font>";
}
else {
	$caption=" <font color='red'>Упс, нет данных для обработки </font>";
}





//строим календарь
//  количества дней предыдущего месяца
$datebak=$caljdey-1;
if ($datebak==0)
{
	$datebak=12;
	$calyearb=$calyear-1;
}
else {
	$calyearb=$calyear; 
}
$dateb = "01.$datebak.$calyearb";
$dateback= date("t", strtotime($dateb));



unset($xdm);
unset($xdn);
unset($xd);
$tbody ="<style >

</style >
<table class='calendar' align='center'>
<caption > $caption </caption >
<tr class='h' border='none'><td>
<button class='$cl' form='back' formaction='$findex#calendar' type='submit' name='next' value='$calback' > ＜ </button>
<form id='back'  method='post' enctype='multipart/form-data'>
<input type='hidden' name='ssid' value='$ssid' checked />
<input type='hidden' name='cmont' value='$cmont' checked />
</form>
</td>
<td colspan='5' >
<p>
{$calmonth[$lang][$calmdey]}   $calyear
</p>
</td>
<td>
<button class='$cl' form='next' formaction='$findex#calendar' type='submit' name='next' value='$calnext' > ＞
</button>
<form id='next'  method='post' enctype='multipart/form-data'>
<input type='hidden' name='ssid' value='$ssid' checked />
<input type='hidden' name='cmont' value='$cmont' checked />
</form>
</td></tr>";


//заголовки дней недели
$tbody=$tbody."<tr>";
foreach ($namdn[$lang] as $key=>$val)
{
	if ($key>=6)
	$vdey='vdey';
	
	$tbody=$tbody."<th class='$vdey' > $val </th>";
}
$tbody=$tbody."</tr>";




  //  текущий день месяца (обновляем значение для стилей)
$calxdate0=date("Y.m.j");
$calcolor= array (
"$calxdate0"=> "#ffff00");

$cdn=date("W");
$nd= array (
"$cdn"=> " class='acw' ");


//тело календаря 
$vdey='rdey';
while ($xdm<$calvdey)
{
	if (empty ($xdm)) {
	$calydey ="$calyear"."-$calmdey"."-01"; 
		$date= new DateTime($calydey);
		$calnd=$date->format('W');
		}
		$calnd=$calnd+1;
$tbody="$tbody"."<tr $nd[$calnd] >";
for ($x=1; $x<=7; $x++)
{
	$xdn=$xdn+1;
	if (($xdn>=$caldnf) && ($xdm<$calvdey))
	{
		$xdm=$xdm+1;
		$calxdey="$calyear.$calmdey.$xdm"; 
		$calydey ="$calyear"."-$calmdey"."-$xdm"; 
		$date= new DateTime($calydey);
		$calydey=$date->format('Y-m-d');
		$calnd=$date->format('W');
		$calwn=$date->format('N');
		if ($calwn>=6)
		$vdey='vdey';
		else 
		$vdey='rdey';
		
		if (!empty($calvalue[$calydey]))
		{
			
			foreach ($calvalue[$calydey]['name'] as $key=>$val) 
			{
			$jstest[]="$key"; 
			}
			$jsx=implode('|', $jstest); 
			$xdmv="<div class='today' onclick=\"Modal('$jsx', '$calydey');\" >
 $xdm </div>";
unset($jstest); 
			

		}
		else {
			
			$xdmv=$xdm;
		}
		$calxdate=date("Y.m.j");
		if ($calxdey==$calxdate) 
		{
		$tbody="$tbody"."<td class='cl'  ><div class='disdey'> <font color='$calcolor[$calxdey]'> $xdmv </font></div></td>";
		}
		else 
		{
		$tbody="$tbody"."<td class='$vdey' > $xdmv  </td>";
		}
	}
	else 
	{
		if ($xdm>0)
		{
			$xdm=$xdm+1;
		}
		//$xdm=$xdm+1;
		if ($xdn<$caldnf)
		{
			$xm=$dateback-$caldnf+$x+1;
		}
		if ($xdm>$calvdey)
		{
			$xd=$xd+1;
			$xm=$xd; 
		}
		
		$tbody="$tbody"."<td class='emptytd' ><font class='$vdey' > $xm </font></td>";
	} 
}
$tbody="$tbody"."</tr>";
}

echo " $tbody </table>";



echo " <div id='modaldiv'></div>
<br>&nbsp<br>";


// папка для сканирования 
$dir = 'js/';
$files1=scandir($dir);
//счетчик для массива
$x=0;
$sizearray=sizeof($files1);
$endarray=$sizearray-2;
$faile1=array_slice($files1, 2, $endarray);
foreach ($faile1 as $key=>$value)
 {
 	$faile="$dir"."$value"; //путь к файлу
 	$fsize=filesize($faile); //размер файла
    if ($fsize>1024)
     {
     $fsize=round($fsize/1024, 2); //приводим в  кб с округлением до второго знака
     $vs='kb'; 
     }
     if ($fsize>1024)
     {
     $fsize=round($fsize/1024, 2); //приводим в  mb с округлением до второго знака
     $vs='mb'; 
     }
    // если это файл не нулевого размера
     if ((is_file($faile)) && ($fsize>0))
        {
        	
           $jsreload=date("m-d-Y-H-i", filectime($faile));
        	$filename = "local.ini";
if (file_exists($filename)) 
{
   //echo "подключен скрипт $faile v $jsreload $br";
}

			echo "<script type='text/javascript' src='$faile?$jsreload'></script>";
		
       }
}




//  пишем файл для примерной оценки трафика
$ttest =0;
if (!empty ($ttest)) 
{
$f = fopen("table.html", "w+"); 

fwrite($f,"\n $tbody </table>
$jscal"); 

fclose($f); 
}

?>

