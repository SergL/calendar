<?
$br='<br>';
$hr="<hr color='blue'>";

	function pre($pre) {
	echo "
<div class='debag' >
<pre >";
	print_r($pre);
	echo "</pre></div>";
	}
	
	function info ($info) {
	echo "<p class='note'>  $info </p>";
	}
	
	function test($test) {
		
		$keyp='$test';
	echo "<p>  $keyp — $test </p>";
	    
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
include("admin/php/setbd.ini");

$linkbd= mysqli_connect($host, $user, $pswd, $database);


mysqli_query($linkbd, "SET NAMES utf8");
		

if (!$linkbd) 
{
    die('<br>Ошибка соединения: ' . mysql_error());
}
else {
//echo 'Успешно соединились';

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
include("admin/php/setbd.ini");

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
?>