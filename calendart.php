
<?

$filename = "local.ini";
if (file_exists($filename)) 
{
   session_start();
}
//служебный раздел 

echo "<HTML >";
date_default_timezone_set('Europe/Kiev'); 

$date=date("d.m.y"); // число.месяц.год 
//включить отладку 
$time=date('H:i');
//echo "$time"; 
$debag=0;
//получаем данные формы  
if (!empty ($_POST))
{
	foreach ($_POST as $key => $value )
	{
		if ($debag!=0)
		{
			echo " <font color='red' ><b> $key </b></font> - <font color='green' > $value </font> <hr color='blue'>";
		}
		$$key = "$value"; 
	}
}





//подключаем файлы с функциями
include ('php/functionbd.php');
include ('php/translit.fn');
include ('php/calfunct.php');
//include ('php/cssin.php');

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
$filename = "php/bd/shool_Tango_of_Liberty.ics";
if (file_exists($filename)) {
$dfile=date("m d Y H", filectime($filename));
if ($dfile!=date("m d Y H"))
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
$Calinfo ="сессия очищена ";
$Calinfo ='база обновлена';
}
unset($_SESSION['calendars']); 
if (empty ($_SESSION['calendars']))
{
foreach($calselect as $key=>$val)
{
	$_SESSION['calendars'][]=Calics("php/bd/$key.ics"); 
	
}
}

  //блок проверки состояния сессии
if (isset ($_SESSION['calendars']))
{
	//info(" сессия не стерта ");
	if (empty ($_SESSION['calendars']))
	{
	//info(" сессия пустая");
	}
	else {
	//info(" сессия не пустая ");
	$calendars=$_SESSION['calendars']; 
	}
}



//$ctest="дата файла $dfile ";
$caption="Танго календарь <font color='red'>$Calinfo</font> $ctest ";
$cl='c'; 
 $css=date("m-d-Y-H-i", filectime('css/calendar-test.css'));

//таблица стилей
echo "
<link rel='stylesheet' href='css/calendart.css?$css' type='text/css'/>

";
  
include ('calendarua.php'); 

//pre($calendars); 
//pre($calvalue); 
//pre($jscal);
echo "</HTML >";
?>