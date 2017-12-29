<?
$br='<br>';
$hr="<hr color='blue'>";


$in_arr = array (
        chr(208), chr(192), chr(193), chr(194),
        chr(195), chr(196), chr(197), chr(168),
        chr(198), chr(199), chr(200), chr(201),
        chr(202), chr(203), chr(204), chr(205),
        chr(206), chr(207), chr(209), chr(210),
        chr(211), chr(212), chr(213), chr(214),
        chr(215), chr(216), chr(217), chr(218),
        chr(219), chr(220), chr(221), chr(222),
        chr(223), chr(224), chr(225), chr(226),
        chr(227), chr(228), chr(229), chr(184),
        chr(230), chr(231), chr(232), chr(233),
        chr(234), chr(235), chr(236), chr(237),
        chr(238), chr(239), chr(240), chr(241),
        chr(242), chr(243), chr(244), chr(245),
        chr(246), chr(247), chr(248), chr(249),
        chr(250), chr(251), chr(252), chr(253),
        chr(254), chr(255)
    );   
 
    $out_arr = array (
        chr(208).chr(160), chr(208).chr(144), chr(208).chr(145),
        chr(208).chr(146), chr(208).chr(147), chr(208).chr(148),
        chr(208).chr(149), chr(208).chr(129), chr(208).chr(150),
        chr(208).chr(151), chr(208).chr(152), chr(208).chr(153),
        chr(208).chr(154), chr(208).chr(155), chr(208).chr(156),
        chr(208).chr(157), chr(208).chr(158), chr(208).chr(159),
        chr(208).chr(161), chr(208).chr(162), chr(208).chr(163),
        chr(208).chr(164), chr(208).chr(165), chr(208).chr(166),
        chr(208).chr(167), chr(208).chr(168), chr(208).chr(169),
        chr(208).chr(170), chr(208).chr(171), chr(208).chr(172),
        chr(208).chr(173), chr(208).chr(174), chr(208).chr(175),
        chr(208).chr(176), chr(208).chr(177), chr(208).chr(178),
        chr(208).chr(179), chr(208).chr(180), chr(208).chr(181),
        chr(209).chr(145), chr(208).chr(182), chr(208).chr(183),
        chr(208).chr(184), chr(208).chr(185), chr(208).chr(186),
        chr(208).chr(187), chr(208).chr(188), chr(208).chr(189),
        chr(208).chr(190), chr(208).chr(191), chr(209).chr(128),
        chr(209).chr(129), chr(209).chr(130), chr(209).chr(131),
        chr(209).chr(132), chr(209).chr(133), chr(209).chr(134),
        chr(209).chr(135), chr(209).chr(136), chr(209).chr(137),
        chr(209).chr(138), chr(209).chr(139), chr(209).chr(140),
        chr(209).chr(141), chr(209).chr(142), chr(209).chr(143)
    );   
function cp1251_to_utf8 ($txt)  {$txt = str_replace($in_arr,$out_arr,$txt);return $txt;}
function utf8_to_cp1251 ($txt)  {$txt = str_replace($out_arr,$in_arr,$txt);return $txt;}



	function code($value) {
	$val=str_replace('<', '&lt', $value);
	$val=str_replace('>', '&gt', $val);
	echo "<style >
		div.code {
			border: 1px solid #00ffff; 
		}
		</style >
<div class='code'><pre>";
	echo "$val";
	echo "</pre></div>";
	}
	
	function pre($pre) {
	echo "<pre>";
	print_r($pre);
	echo "</pre>";
	}
	
	function info ($info) {
		
$filename = "local.ini";

if (file_exists($filename)) 
{
    
// echo " <h2>файл есть </h2>";

// включаем файл
	include ("$filename");
	if (!empty ($test)) {
		$keyp='$ftest';
	echo "<p class='note'> <font color='#00ff00'> $info </font></p>";
	}
} 
	
	}
	
	function test($test) {
		//  проверка файла
		
$filename = "local.ini";

if (file_exists($filename)) 
{
    
// echo " <h2>файл есть </h2>";

// включаем файл
	include ("$filename");
	if (!empty ($test)) {
		$keyp='$ftest';
	pre("<p>  $keyp — $ftest </p>");
	}
} 
else 
{
   //echo " файла $filename  нет ";
}
		
	    
	}
	
	
	function ftrim($str)                             
{                                                    
    return trim(preg_replace('/\s{2,}/', ' ', $str));                                                     
}


///функция получает массив из запроса и базе данных
function arraymsql($sql) 
{ 
	//подключаем базу данных msql
//файл настроек 
include("php/setbd.ini");

$linkbd= mysqli_connect($host, $user, $pswd, $database);


mysqli_query($linkbd, "SET NAMES utf8");
		

if (!$linkbd) 
{
    die('<br>Ошибка соединения: ' . mysql_error());
}
else {
echo 'Успешно соединились';

}
//info($sql);
$res = mysqli_query($linkbd, $sql); 
//pre($res);
if($res===false) {
	info('упс что-то не так в функции запроса к базе');
return false; 
}
while($row= $res->fetch_assoc())
{
	$result[]=$row;
}
//pre($result);
/* очищаем результирующий набор */
mysqli_free_result($res);
mysqli_close($linkbd);

return $result;
}
//-------------------------------------------------------------------------------------
///функция делает запрос к базе данных
function querymsql($sql) 
{ 
	//подключаем базу данных msql
//файл настроек 
include("php/setbd.ini");

$linkbd= mysqli_connect($host, $user, $pswd, $database);

mysqli_query($linkbd, "SET NAMES utf8");
		

if (!$linkbd) 
{
    die('<br>Ошибка соединения: ' . mysql_error());
}
else {
echo "<br>Успешно соединились";

}
//info($sql);

/* Создание таблицы не возвращает результирующего набора */


if (mysqli_query($linkbd, $sql) === TRUE) {
    printf("<br> <font color ='#00ff00'> запрос выполнен </font >");
    $result ='y';
}
else {
    printf("<br> <font color='red'>запрос</font> $sql <font color='red'>не выполнен</font>");

	
    $result ='n';
}
mysqli_close($linkbd);
return $result;
}
//-------------------------------------------------------------------------------------
//получение списка файлов и папок 
function getArrayFiles($path_dir) {
    $array_path = array();
    $dir = new RecursiveDirectoryIterator($path_dir);
    foreach(new RecursiveIteratorIterator($dir) as $val) {
        if($val->isFile()) {
            $array_path[] = $val->getPathname();
        }
    }
    return $array_path;
}




?>