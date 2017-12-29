
<?

//error_reporting(E_ERROR);
session_start();
$valuec=session_id();
setcookie("Googlecalendar", $valuec, time()+60*60*24*1);  /* срок действия 30  дней*/
//служебный раздел 
date_default_timezone_set('Europe/Kiev'); 

//подключаем файлы с функциями
include ('php/functionbd.php');
include ('php/translit.fn');
include ('php/calfunct.php');
include ('php/cssin.php');
include ('php/array_bd.php');

//pre($valuec);
//pre($_COOKIE); 
//pre($_SESSION);
//pre($_POST[session]);
$testdate=date("d.m.y H:i"); // число.месяц.год 
info("время сервера $testdate");

$date=date("d.m.y"); // число.месяц.год 
//включить отладку 
$debag=1;
//получаем данные формы  

if (!empty ($_POST))
{
	$debag=0;
	foreach ($_POST as $key => $value )
	{
			if ($debag!=0)
			{
				if (is_array($value)) 
				{
				pre("<font color='red' ><b> $key </b></font> - <font color='green' >");
pre($value); pre("</font> <hr color='blue'>");
				
				}
				else {
					echo " <font color='red' ><b> $key </b></font> - <font color='green' > $value </font> <hr color='blue'>";
				}
			}
			$$key = $value; 
	}
}


if (empty ($ssid))
{
	$ssid=rand();
}


if (!empty ($recordics))
{
echo"<div class='help'>";
if ((!empty ($recordics)) && (!empty($typeics)) && (!empty($cityics)) && (!empty($ics)) && (!empty($langcal))) 
{
	if (preg_match('/.ics/', $ics)) 
	{
		
		$typeics=clean($typeics);
		$cityics=clean($cityics);
		$ics=clean($ics);
		$langcal=clean($langcal);
		
		$vkey[]="`type calendar`"; 
		$strval[]="'$langcal-$typeics'";
		$vkey[]="`cantry city`"; 
		$strval[]="'Украина $cityics'";
		$vkey[]="`ics link`"; 
		$strval[]="'$ics'";
		$keystr=implode(", ", $vkey);
        $valstr=implode(", ", $strval);

$sql = "INSERT INTO `GoogleCal` ($keystr) VALUES ($valstr);";
pre($sql);
//делаем запрос к базе
//querymsql($sql);
	}
	else {
		echo "<h1><p class='infor' >
		ссылка на календарь не соответствует формату ics </p></h1>";
	}
}
else {
	echo "<h1><p class='infor' >
Не все поля заполнены </p></h1>";
}
echo"</div>";
}


$emailadmin="virikidorhom@gmail.com";  //доп адрес 

//отправляем заявку
if (!empty ($newacc)) 
{
	echo"<div class='help'>";
	if ((!empty ($cityics))  && (!empty ($mail)) && (!empty ($fbacc)) && (!empty($langcal))) 
	{
	$cityics=clean($cityics);
	$mail=clean($mail);
	$langcal=clean($langcal);
	$fbacc=clean($fbacc); 
	$typeics=clean($typeics);
	$msg=" 
Сообщение с сайта Танго календарь
Заявка на открытие доступа к календарю 
  
  Язык: $langcal
  
  Тип: $typeics 
  
  Город: $cityics 
  
 Почта: $mail

 facebook : $fbacc

"; 
 if (mail("$emailadmin", "Заявка на открытие доступа к календарю", "$msg"))
{ 
 
 echo "<h1><p class='infog' >
Ваша заявка отправлена, спасибо </p></h1>";
}
 else {
	echo "<h1><p class='infor' >
ошибка отправки заявки, попробуйте еще </p></h1>";
}
 }
 else {
	echo "<h1><p class='infor' >
Не все поля заполнены </p></h1>";
}
echo"</div>";

	
}



echo "
<HTML><head>";
$title ='Добавление Google календаря ';
include ('php/cssin.php');
include ('php/headhtml.php');

echo "</head>";



echo "<body>";

echo "
<script>
	function Seldiv(id, id2) {
		 document.getElementById(id).style.display = 'block';
		 document.getElementById(id2).style.display = 'none';
	}
</script>
<div class='help'>
<h1> Инструкция по работе с google календарями </h1>

	<div class='accordion'>
  <input class='toggle-box' id='block-1' type='checkbox' >
    <label for='block-1'> Как добавить календарь Google </label>
  <div class='box'>

<p>
Доступ по ссылке работает только для общедоступных календарей. $br
(календари на сайте являются общедоступными) <span class='infor' > * </span></p>

<p>
<span class='inforg'>
1. Откройте <a href='https://calendar.google.com/calendar' target='blank'>  <span class='infog'><big>Google Календарь </big></span> </a> </span><br>
	<a id='asel1' name='' onclick=\"Seldiv('mobi', 'asel1');\">
		<span class='infog'><big>
Если вы зашли с телефона нажмите здесь </big></span></a>
		<span id='mobi' style='display: none; '>
	$br 
При добавления календаря с мобильного устройства вы сначала можете увидеть такую картинку
	$br
	<img src='img/help/Screenshot_1.jpg' ></img>$br
	Смело переходим на сайт для мобильный устройств и видим 
	<br>
	<img src='img/help/Screenshot_2.jpg' ></img>$br
	прокручиваем в низ страницы и жмем на 'версия для настольных ПК' 
	$br
	<img src='img/help/Screenshot_3.png' ></img>$br 
	бинго если вы увидели нечто подобное то можете продолжать дальше
	$br
	<img src='img/help/Screenshot_4.png' ></img>$br 
	</span>
</p>

<p><span class='inforg'>
2.Рядом с пунктом ''Другие календари'' в левой части страницы нажмите на стрелку Стрелка вниз. </span>
	$br
	<img src='img/help/Screenshot_5.png' ></img>$br 
	Если вы уже поменяли себе дизайн календаря на новый то этот пункт находится выше пункта 'Мои календари'
	$br
	<img src='img/help/Screenshot_6.png' ></img>$br 
	
</p>
<p><span class='inforg'>
3.Выберите Добавить по URL.</span>
	
	$br<img src='img/help/Screenshot_7.png' ></img>$br 
	
</p>
<p><span class='inforg'>
4.Введите адрес календаря в формате ссылки ICAL.</span>
	$br<img src='img/help/Screenshot_8.png' ></img>
	$br<img src='img/help/Screenshot_8a.png' ></img>$br
	<span class='infor' > * </span>
ссылку на календарь в этом формате можно получить на сайте если нажать на <span class='inforg'><sup><big> ... </big></sup></span> рядом с интересующим вас календарем $br

<a href='help.php#calicslink' onclick=\"document.getElementById('block-2').checked = true; \" >  <span class='infog'><big> подробно </big></span></a>

</p>
<p><span class='inforg'>
5.Нажмите кнопку Добавить календарь. Календарь появится в списке ''Другие календари''.</span>
	$br<img src='img/help/Screenshot_11.png' ></img>$br
</p>
	
<span class='infor'><big>*</big></span>
Примечание. Обновление данных Google Календаря может занять до 12 часов
<br>&nbsp<br>
  </div>
</div>

 <br>&nbsp<br>
<a name='calicslink'></a>
<div class='accordion'>
  <input class='toggle-box' id='block-3' type='checkbox'>
    <label for='block-3'> как включить календарь на телефоне </label>
  <div class='box'>
  	<p class='inforg'>
<span class='infor'><big>*</big></span>
Календарь должен быть добавлен в ваш Google календарь </p>
<p> Зайти в настройки календаря,

$br<img src='img/help/Screenshot_12.png' ></img>$br 

управление календарями. 

$br<img src='img/help/Screenshot_13.png' ></img>$br 

включить. 
$br<img src='img/help/Screenshot_14.png' ></img>$br 


</p>
 </div>
 </div>
 
<br>&nbsp<br>
<a name='calicslink'></a>
<div class='accordion'>
  <input class='toggle-box' id='block-2' type='checkbox'>
    <label for='block-2'> где взять ссылку на календарь в формате ics </label>
  <div class='box'>
  <p class='inforg'>
  	На главной странице сайта в списке календарей напротив названия календаря находим три точки ••• и жмем на них </p>
$br<img src='img/help/Screenshot_9.png' ></img>$br
 в появившемся окне ссылка будет текстовом поле, выделяем и копируем 
 $br<img src='img/help/Screenshot_10.png' ></img>
 $br<img src='img/help/Screenshot_10a.png' ></img>$br
 </div>
 </div>


 <br>&nbsp<br>
<a name='calicslink'></a>
<div class='accordion'>
  <input class='toggle-box' id='block-4' type='checkbox'>
    <label for='block-4'> добавить календарь в базу </label>
  <div class='box'>
";

include ('php/bd/city.ua.bd');

foreach ($xcity[ru]['Украина'] as $key =>$value )
{
	foreach ($value as $k =>$val)
	{
		$vs=explode(' ', $val);
		$val=$vs[3];
		$city[]="'$val'";
	}
	
}
	$citystr=implode(',', $city);
	echo "
		<script type='text/javascript' language='javascript'>
var autocomplete = [$citystr];
</script >
<style>
input {
    top: 10px;
    left: 150px;
}
 
#autocomplete {
    top: 40px;
    left: 150px;
    background: rgba(250,250,250,0.8);
    display: none; 
    padding: 0.5em;
    width: 200px; 
    font-size: 110%;
}

</style>
<div class='newcalform'>
	
  <form id='form' action='index.php' method='post'>
  	  <script>
//путь от корня сайта к текущей странице
var act=window.location.pathname;
//alert(act);
var f=this.form;
f.setAttribute('action', act);
</script >
  
  <p>введите город </p>
<input type='text' id='input' name='cityics'/>
<br>
<div id='autocomplete' ></div>




<script language='javascript' type='text/javascript'>
var tx=0;
input.oninput=function (){
	document.getElementById('autocomplete').innerHTML='';
	var result = autocomplete.filter(function(text) {
	var val1=document.getElementById('input').value;
	var size=val1.length;
	//logAlert (size);
	var val=new RegExp(val1, \"i\");
	//logAlert(val);
	if ((val.test(text)) && (size>2))
{
	document.getElementById('autocomplete').style.display = 'inline-block';
  return text ;  
  }
  
});


	 result.forEach(function(item, i, arr) {
	var div=document.getElementById('autocomplete').innerHTML;
	
document.getElementById('autocomplete').innerHTML=div + '<a name=\'\' onclick=\"funComplit(\'' + item + '\')\">' + item + '</a><hr>';
}); 
	
}

function funComplit(paste){
	document.getElementById('autocomplete').style.display = 'none';
	document.getElementById('autocomplete').innerHTML='';
	document.getElementById('input').value=paste;

	document.getElementById('s').innerHTML=' (' + paste + ')';
	
}
function selChenge(id) {
	var val=document.getElementById(id).value;
	//alert('test- ' + val); 
	if ((val=='milonga') || (val=='test') || (val=='FestivalUa') || (val=='SeminarUa'))
	{
		 document.getElementById('tics').style.display = 'none';
		 document.getElementById('mics').style.display = 'block';
	}
	else {
	 document.getElementById('tics').style.display = 'block';
		 document.getElementById('mics').style.display = 'none';
	}
	if (val=='test')
	{
		 document.getElementById('mics').style.display = 'none';
	}
}
</script>
<p>выберите тип календаря </p>

<select name='typeics' id='typeics' style='zoom: 2;' onchange=\"selChenge('typeics');\">
";

echo"<option value='test' ><span id='icsyest'>
категории </span></option >";

echo"<option value='FestivalUa'><span id='icsfest'>
Фестивали Украины </span></option >";
echo"<option value='SeminarUa'><span id='icssem'>
Семинары </span></option >";
foreach ($calselics as $key=>$val) 
{
	echo"<option value='$val'><span id='ics$val'>
$key </span></option >";
}
echo "
</select ><font id='s'></font>


<div class='formlang'>
<p>выберите языковую локализацию календаря </p>
<input type='radio' name='langcal' value='ru/ua' > <span> укр/рус </span> 
<input type='radio' name='langcal' value='en' > <span> англ </span> 
</div>

<div id='tics' style='display: none; ' >
	
<p>введите ссылку календаря </p>
<textarea  name ='ics' value='' rows='5' cols='30'></textarea>
<br>&nbsp<br>
 &nbsp &nbsp
	<input class='send' type='submit'  name='recordics' value='отправить'>
	
</div>

<div id='mics' style='display: none; ' >
	<p class='inforg'>
		Чтобы добавлять события в календарь этого типа нужно подать заявку для открытие доступа к календарю и затем добавить его в свои календари.
$br Если календаря для вашего города еще нет то он будет создан и появится в списке. 
</p>
<p class='infog'>
	Для подачи заявки заполните поля ниже
</p>
email  — <span clasd='infog'>
	почтовый ящик google аккаунта (без него доступ к редактированию календаря не открывается)</span>
	<br>
<input type='text' id='input' name='mail'/> <span class='infor' > * </span>
<br>&nbsp<br>
	
facebook  —  <span clasd='infog'>
ссылка на вашу страницу чтобы я мог убедиться что вы не робот  ))) </span>
$br
<input type='text' id='input' name='fbacc'/> <span class='infor' > * </span>

<br>&nbsp<br>
 &nbsp &nbsp
	<input class='send' type='submit'  name='newacc' value='отправить'>
	
</div>

<br>&nbsp<br><br>&nbsp<br><br>&nbsp<br>
</form>
</div><div>
       
</div>
 </div>
 </div>
 
 
</div>
";



?>
	<br>&nbsp<br><br>&nbsp<br>
	</body>
</HTML>