<?php 
session_start();
$valuec=session_id();
setcookie("Googlecalendar", $valuec, time()+60*60*24*1);  /* срок действия 30  дней*/
//подключаем файлы с функциями
include ('php/functionbd.php');

while(list ($key, $val) = each ($_POST))
{
	$$key=$val; 
	//echo "$key - $val <br>";
}

if (empty ($_SESSION[acc][$ssid][$acc]))
{
	$_SESSION[acc][$ssid][$acc]=1;
	//pre("test 1");
	//pre($_SESSION[acc][$ssid]);
}
else 
{
	unset($_SESSION[acc][$ssid][$acc]);
	//pre("test 2");
	//pre($_SESSION[acc][$ssid]);
}


?>