<?


//определение типа устройств для подключения стилей
require_once('php/Mobile_Detect.php');  // Подключаем скрипт Mobile_Detect.php

$detect = new Mobile_Detect; // Инициализируем копию класса


if (empty ($vizual)) {
//$css=date("d.m.y"); // число.месяц.год 
    $css = rand();
} else {
    $css = rand();
}
//echo "test $css"; 

//pre($detect); 
$css = date("m-d-Y-H-i", filectime('css/calendar-test.css'));

//таблица стилей
echo "<link rel='stylesheet' href='css/calendar-test.css?$css' type='text/css'/>";

// Любое мобильное устройство (телефоны или планшеты).
if ($detect->isMobile()) {
    $devais = 'mobile';

}

// Планшетные компьютеры
if ($detect->isTablet()) {
    $devais = 'tablet';
    // echo "<link rel='stylesheet' href='css/style_table.css?$css' type='text/css'/>";
}
// Исключаем планшеты
if ($detect->isMobile() && !$detect->isTablet()) {
    $devais = 'phone';

//pre($detect); 
    $css = date("m-d-Y-H-i", filectime('css/phone-style.css'));

    //таблица стилей
    echo "<link rel='stylesheet' href='css/phone-style.css?$css' type='text/css'/>";
} else {
    $devais = 'win';
    echo "
	<style>
	
	html {
      background: url(img/body4.jpg) no-repeat;
	  background-size: 100%;
	width:100%;
	height:100%;
	background-attachment: fixed; 
}
</style>";
}

$html = <<<EOT

EOT;

?>
