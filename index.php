<?
//error_reporting(E_ERROR);
session_start();
$valuec = session_id();
setcookie("Googlecalendar", $valuec, time() + 60 * 60 * 24 * 1);  /* срок действия 30  дней*/
//служебный раздел 
date_default_timezone_set('Europe/Kiev');

$emailadmin = "virikidorhom@gmail.com";  //доп адрес

//подключаем файлы с функциями
include_once(__DIR__ . '/php/functionbd.php');
include_once(__DIR__ . '/php/translit.fn');
include_once(__DIR__ . '/php/calfunct.php');
include_once(__DIR__ . '/php/cssin.php');
include_once(__DIR__ . '/php/array_bd.php');
include_once(__DIR__ . '/template/template.php');
//функция вывода тестовой информации
unset($_SESSION['calendarUA']['log']);
function logtest($value)
{
    $_SESSION['calendarUA']['log'][] = $value;
}


if (isset($newlang) || empty ($lang)) {
    $lang = "ru";
}
$testdate = date("d.m.y H:i"); // число.месяц.год
info(sprintf($tmpl[$lang]["time_server"], $testdate));

$date = date("d.m.y"); // число.месяц.год
//включить отладку 
$debug = 1;
//получаем данные формы  
if (!empty ($_POST)) {
    $debug = 0;
    foreach ($_POST as $key => $value) {
        if ($debug != 0) {
            if (is_array($value)) {
                logtest("<font color='red' ><b> $key </b></font> - <font color='green' >");
                logtest($value);
                logtest("</font> <hr color='blue'>");

            } else {
                echo " <font color='red' ><b> $key </b></font> - <font color='green' > $value </font> <hr color='blue'>";
            }
        }
        $$key = $value;
    }
}


if (empty ($ssid)) {
    $ssid = rand();
}

if (isset($newlang) && !empty ($newlang)) {
    $_SESSION['setting'][$ssid]['lang'] = $newlang;
}

if (!empty ($_SESSION['setting'][$ssid])) {
    foreach ($_SESSION['setting'][$ssid] as $key => $val) {
        $$key = $val;
        //echo " <font color='red' ><b> $key </b></font> - <font color='green' > $val</font> <hr color='blue'>";
    }
}

if (!empty ($newacc)) {
    send_email_requet($emailadmin, $tmpl,$lang,$acclink, $mail, $fbacc);
}

if (!empty ($gcsel)) {
    unset($_SESSION[gcsel][$ssid]);
    $_SESSION[gcsel][$ssid] = $gcsel;
}

if (!empty($_SESSION[gcsel][$ssid])) {
    $gcsel = $_SESSION[gcsel][$ssid];
    //logtest($gcsel);
}
if (empty ($gcsel)) {
    $gcsel[1] = 'tango_event_in_ua_fest.ics';
    unset($_SESSION[gcsel][$ssid]);
    $_SESSION[gcsel][$ssid] = $gcsel;
    //$capname='Фестивали Украины ';
}


//отправляем заявку

//pre($_POST);


//  проверка файла
$filename = "local.ini";
if (file_exists($filename)) {
// echo " <h2>файл есть </h2>";
// включаем файл конфигурации 
    include_once("$filename");
} else {
}

if (!empty ($recalreload)) {
    unset($_SESSION['calendarbd']);
    $calreload = 1;
}

//unset($_SESSION['calendarua']);

//если не пустой массив списка выбранных календарей
if (!empty ($gcsel)) {
    unset($calselect);
    unset($_SESSION['calendarua'][$ssid]);
    $calselect = $gcsel;
}


//  проверка файла
$filename = "php/bd/Ukraina_Kiev_milonga_2.ics";
if (file_exists($filename)) {
    $dfile = date("m d Y H", filectime($filename));
    if ($dfile != date("m d Y H")) {
//  проверка файла
        $filename2 = "local.ini";
        if (file_exists($filename2)) {
            $dfile = date("m d Y", filectime($filename));
            if ($dfile != date("m d Y")) {
                $calreload = 1;
                unset($_SESSION['calendarbd']);
                info("локально ");
            }
        } else {
            $calreload = 1;
            unset($_SESSION['calendarbd']);
        }
    }
}


//unset($_SESSION['calendarua'][$ssid]);
if (empty ($_SESSION['calendarua'][$ssid])) {
    foreach ($calselect as $key => $val) {
        //pre($key);
        if (!empty ($gcsel)) {
            $_SESSION['calendarua'][$ssid][] = Calics(__DIR__ . "/php/bd/$val");
            //$Calinfo ="test calendarua 0 $val";
        } else {
            $_SESSION['calendarua'][$ssid][] = Calics(__DIR__ . "/php/bd/$key.ics");
            //$Calinfo ="test calendarua 1";
        }
    }
    if ($lang == 'ru')
        $capname = "Танго календарь Украины ";
    if ($lang == 'ua')
        $capname = "Танго календар Україні ";
    if ($lang == 'en')
        $capname = "Tango Calendar Ukraine ";
}

$calendars = $_SESSION['calendarua'][$ssid];
//pre($ssid);


//unset($_SESSION['calendarbd']);
if (empty ($_SESSION['calendarbd'])) {
//получаем список календарей в базе данных
//pre("<p class='infog'>получаем список календарей из базы</p>");
    $tabnamesql = 'GoogleCal';
    $sql = "SELECT * FROM $tabnamesql";
    $result = arraymsql($sql);
//pre($result); 
    foreach ($result as $key => $value) {
        unset($xc);
        $xc = explode(' ', $value['Cantry city']);
        $gcantry = $xc[0];
        unset($xc[0]);
        $gcalcity = implode(' ', $xc);
        $gcaltype = $value['type calendar'];
        $gcallink = $value['ics link'];
        $gcalfname = "$gcantry" . "_$gcalcity" . "_$gcaltype" . "_$key.ics";
        $gcalfname = translit($gcalfname);
        //  проверка файла

        if ((file_exists(__DIR__ . "/php/bd/$gcalfname")) && (empty ($calreload))) {
            //echo "<p>  файл календаря $gcalfname есть </p>";
        } else {

            $result = getSslPage($gcallink);
//echo "<p> пишем файл $gcalfname </p>";
            $f = fopen(__DIR__ . "/php/bd/$gcalfname", "w+");
            fwrite($f, "$result");
            fclose($f);
            if (file_exists(__DIR__ . "/php/bd/$gcalfname")) {
//echo "<p>  файл календаря $gcalfname создан </p>";
            }


        }


        $gcal[$gcantry][$gcalcity][$gcaltype][] = $gcalfname;

        $gcalsel[$gcalfname] = $gcallink;


        if ($lang != 'en') {
//$Calinfo='база обновлена '.$Calinfo;
        }

    }


    if (!empty ($calreload)) {
        $result = getSslPage('https://calendar.google.com/calendar/ical/p263ecltmp6v2komsnjas1d6q4%40group.calendar.google.com/public/basic.ics');
//echo "<p> пишем файл $gcalfname </p>";
        $f = fopen(__DIR__ . "/php/bd/tango_event_in_ua_fest.ics", "w+");
        fwrite($f, "$result");
        fclose($f);
        $result = getSslPage('https://calendar.google.com/calendar/ical/55ddnvrlvbto13pq0t82ps9hek%40group.calendar.google.com/public/basic.ics');
//echo "<p> пишем файл $gcalfname </p>";
        $f = fopen(__DIR__ . "/php/bd/tango_event_in_ua_mclass.ics", "w+");
        fwrite($f, "$result");
        fclose($f);
    }


    $_SESSION['calendarbd'] = $gcal;
    $_SESSION['gcalsel'] = $gcalsel;
    info('заносим список календарей в сессию ');

}


$calendarbd = $_SESSION['calendarbd'];
//pre($calendarbd);

//#############################
if ($lang == 'ru')
    $title = 'Танго календарь';
if ($lang == 'ua')
    $title = 'Танго календар';
if ($lang == 'en')
    $title = 'Tango Calendar Ua';
include_once(__DIR__ . '/php/headhtml.php');

echo "
<!DOCTYPE HTML>
<HTML >
<body>
";


if ((empty ($_SESSION['caltypes'])) || (!empty ($calreload))) {

    unset($_SESSION['caltypes']);
    foreach ($calendarbd as $country => $value) {
        foreach ($value as $city => $value) {
            foreach ($value as $type => $value) {
                foreach ($value as $key => $val) {
                    $fsize = filesize(__DIR__ . "/php/bd/$val"); //размер файла
                    // если это файл не нулевого размера
                    if ($fsize > 0) {

                        $res = Calics(__DIR__ . "/php/bd/$val");
                        //pre($res);
                        $k = key($res);
                        $calname = $res[$k]['calname'];
//$calcity[$cantry][$city][$type][]=$res; 

                        $caltypes[$country][$type][$city][][$calname] = $val;
                    }
                }
            }
        }
    }
    $_SESSION['caltypes'] = $caltypes;

//pre($caltypes);
}
unset($city);
$caltypes = $_SESSION['caltypes'];


if (empty ($ssid))
    $check = 'checked';
else $check = '';


if ((!empty ($_SESSION[acc][$ssid])) && (empty ($acc))) {
    $acc = $_SESSION[acc][$ssid];

}

if (!empty ($acc)) {
    //unset($_SESSION[acc][$ssid]);
    //$_SESSION[acc][$ssid]=$acc;
    foreach ($acc as $k => $v)
        $acheck[$k] = 'checked';
}

//pre($acc); 
if (empty ($acc)) {
    $acheck[1] = 'checked';
    $_SESSION[acc][$ssid][1] = 1;
}

$x = $x + 1;
if (!empty ($gcsel[$x]))
    $check = 'checked';
else {
    $check = '';
}


if ($lang == 'ru') {
    $seldivname = 'Календари';
    $seldivname2 = 'Фестивали в Украине ';
    $seldivname3 = 'Мастер-классы в Украине ';
}
if ($lang == 'ua') {
    $seldivname = 'Календарі';
    $seldivname2 = 'Фестивалі в Україні ';
    $seldivname3 = 'Майстер-класи в Україні ';
}

$calfestval2 = 'https://calendar.google.com/calendar/ical/p263ecltmp6v2komsnjas1d6q4%40group.calendar.google.com/public/basic.ics';

if ($lang == 'en') {
    $seldivname = 'Calendars';
    $seldivname2 = 'Festival in Ukraine ';

    $calfestval2 = 'https://calendar.google.com/calendar/ical/ubao5j8a7p293kiii70dapo4bc%40group.calendar.google.com/public/basic.ics';

    $seldivname3 = 'Master-class in Ukraine ';
}


////////////////////////////////////////////////////////
//$ctest="дата файла $dfile ";
$caption = "<font color='#00ffff'>$capname </font><font color='red'>$Calinfo </font> $ctest ";
$cl = 'cal';

echo "<div class='calendar'>
 
<form action='index.php' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='ssid' value='$ssid' checked />
  <input type='hidden' name='next' value='$next' checked />
  <div class='selectbox'><select name='cmont' clsss='lang' onchange=\"form.submit()\">
";
if (empty ($cmont))
    $cmont = 1;
echo "<span><option value='$cmont' class='lang' >количество мес $cmont </option></span>";
for ($x = 1; $x <= 12; $x++) {
    if ($x != $cmont)
        echo "<option value='$x'> $x мес </option>";
}
echo "</select></div>";
if ($cmont > 1) {
    echo "
<style >
	table.calendar{
	}
</style>";
}
if ($lang == 'en') {
    echo "<button class='lang' name='newlang' value='ua'> укр </button><button  class='lang' name='newlang' value='ru'> рус </button>";
} elseif ($lang == 'ua') {
    echo "<button class='lang'  name='newlang' value='en'> eng </button><button class='lang'  name='newlang' value='ru'> рус </button>";
} else {
    echo "<button class='lang'  name='newlang' value='ua'> укр </button><button class='lang'  name='newlang' value='en'> eng </button>";
}
echo "</form>";


for ($xzx = 1; $xzx <= $cmont; $xzx++) {
    if ($xzx == 1)
        $fixnext = $calnext;
    include_once('calendarua.php');
    $next = $calnext;
    unset($caption);
}
$next = $fixmext;

if ($lang != 'en') {
    echo "<div class='help' > 
	<p> Если вы хотите добавить интересующий вас календарь в календарь на своем устройстве то воспользуйтесь 
<a class='infog' href='help.php' target='blank'> инструкцией по добавлению календаря </a>
$br
Также вы можете на той же странице добавить свой календарь для общего пользования (актуально для преподавателей и организаторов) или получить доступ на создание мероприятий в уже существующих календарях 
</p>
</div>
</div>";
}

echo "
  <form id='form' action='webpost/test.php' method='post'>
  <input type='hidden' name='ssid' value='$ssid' checked />
  <input type='hidden' name='next' value='$next' checked />
<input type='hidden' name='cmont' value='$cmont' checked />
  <script>
//путь от корня сайта к текущей странице
var act=window.location.pathname;
//alert(act);
var f=this.form;
f.setAttribute('action', act);
</script >";


$x = 0;
$x = $x + 1;
if (!empty ($gcsel[$x]))
    $check = 'checked';
else {
    $check = '';
}
echo "
<div class='calendars'>
<div class='accordion' >
  <input name='acc[1]' value='1' class='toggle-box' id='block-1' type='checkbox' $acheck[1]  >
    <label for='block-1' onclick=\"ajax({
url:'get_ajax.php',
statbox:'status',
method:'POST',
data: {
'acc': '1', 
'ssid': '$ssid'
},
success:function(data){document.getElementById('status').innerHTML=data;}
})\"
> $seldivname </label>
  <div class='box'>
<p>
<input type='checkbox' name='gcsel[$x]' value='tango_event_in_ua_fest.ics' $check 
onclick=\"form.submit()\" /> $seldivname2
<a name=' ' style='float: right;' id='asel$x' onclick=\"Asel('asel$x',  '$x', '$calfestval2');\"><big> ••• </big></a>
</p>";

$x = $x + 1;
if (!empty ($gcsel[$x]))
    $check = 'checked';
else {
    $check = '';
}

echo "
<p>
<input type='checkbox' name='gcsel[$x]' value='tango_event_in_ua_mclass.ics' $check 
onclick=\"form.submit()\" /> $seldivname3
<a name=' ' style='float: right;' id='asel$x' onclick=\"Asel('asel$x',  '$x', 'https://calendar.google.com/calendar/ical/55ddnvrlvbto13pq0t82ps9hek%40group.calendar.google.com/public/basic.ics');\"><big> ••• </big></a>
</p>";


$country = 'Украина';
$kx = $x;
foreach ($calselics as $key => $val) {

    $kx = $kx + 1;
    if (empty ($acc)) {
        $acheck[$kx] = 'checked';
        $_SESSION[acc][$ssid][$kx] = 1;
    }

    if (!empty ($caltypes[$country][$val])) {
        $typv = $calselval[$lang][$val];
        echo "<div class='accordion2'> <input name='acc[$kx]' value='1' class='toggle-box' id='block-$kx' type='checkbox' $acheck[$kx] >    <label for='block-$kx' onclick=\"ajax({
url:'get_ajax.php',
statbox:'status',
method:'POST',
data: {
'acc': '$kx', 
'ssid': '$ssid'
},
success:function(data){document.getElementById('status').innerHTML=data;}
})\"
> $typv </label> <div class='box'>";
        $typ = $val;

        if ('milonga' != $val) {
            foreach ($caltypes[$country][$val] as $key => $val) {
                $kx = $kx + 1;
                if (empty ($acc)) {
                    $acheck[$kx] = 'checked';
                    $_SESSION[acc][$ssid][$kx] = 1;
                }
                if ($lang == 'en')
                    $keyv = translitcity($key);
                else $keyv = $key;
                echo "<div class='accordion2'> <input name='acc[$kx]' value='1' class='toggle-box' id='block-$kx' type='checkbox' $acheck[$kx] >    <label for='block-$kx' onclick=\"ajax({
url:'get_ajax.php',
statbox:'status',
method:'POST',
data: {
'acc': '$kx', 
'ssid': '$ssid'
},
success:function(data){document.getElementById('status').innerHTML=data;}
})\"
> $keyv </label> <div class='box'>";
                //echo "$key <br>";
                foreach ($caltypes[$country][$typ][$key] as $k => $v) {

                    $x = $x + 1;
                    $k = key($v);
                    $v = $v[$k];
                    if (!empty ($gcsel[$x]))
                        $check = 'checked';
                    else {
                        $check = '';
                    }
                    if ($lang == 'en')
                        $k = translitcity($k);
                    else $k = $k;
                    $icslink = $_SESSION['gcalsel'][$v];
                    echo "<input type='checkbox' name='gcsel[$x]' value='$v' $check onclick=\"form.submit()\" style='clear: right;'/> $k 
<a name=' ' style='float: right;' id='asel$x' onclick=\"Asel('asel$x',  '$x', '$icslink');\"> <big><sub> ••• </sub></big></a> 
<hr style='clear: right;'>";
                }
                echo "  </div></div>";
            }
        } else {
            foreach ($caltypes[$country][$val] as $key => $val) {
                $x = $x + 1;
                foreach ($val as $k => $v)
                    $t = implode('|', $v);

                if (!empty ($gcsel[$x]))
                    $check = 'checked';
                else $check = '';
                if ($lang == 'en')
                    $key = translitcity($key);
                $icslink = $_SESSION['gcalsel'][$t];
                echo "<input type='checkbox' name='gcsel[$x]' value='$t' $check onclick=\"form.submit()\" style='clear: right;'/> $key <a name=' ' style='float: right;' id='asel$x' onclick=\"Asel('asel$x',  '$x', '$icslink');\"><big><sub> ••• </sub></big></a> 
<hr>";
            }
        }
        echo " </div></div>";
    }
}
//pre($gcsel); 

echo "  
	</div>
	<br>&nbsp<br>
	<button class='send' type='submit' name='recalreload' value='1' > обновить </button >
	<br>&nbsp<br>
</div>
<script >
	
var lang='$lang'; 
	
function Asel(asel, x, icslink) {
	document.getElementById('modaldiv').innerHTML= xdmv;
	//alert(icslink);
var xlinck=icslink.replace('https://calendar.google.com/calendar/ical/', '');

xlinck=xlinck.replace('/public/basic.ics', '');
	//alert(xlinck);
var httplinck='https://calendar.google.com/calendar/embed?src=' + xlinck + '&ctz=Europe%2FKiev'; 

var freim=\"<iframe src='\" + httplinck + \"' style='border: 0' width='800' height='600' frameborder='0' scrolling='no'></iframe>\";
	
	
	

var divnp=\"$hr <div id='dsel1' > <a name='' onclick=\" + '\"' + \"Seldiv('mics', 'asel');\" + '\"' + \"><span class='infob'> получить доступ к созданию событий в календаре </span></a></div><div id='mics' style='display: none; overlow: scroll; height: 100%; font-size: 0.8em;' >  <form action='index.php' method='post'><textarea name='acclink' hidden >\" +  icslink + \"</textarea><p class='inforg'> Для подачи заявки заполните поля ниже </p> email  — <span clasd='inforg'> почтовый ящик google аккаунта (без него доступ к редактированию календаря не открывается)</span><br><input type='text' id='input' name='mail' style='zoom: 1.9; '/> <span color='#f00' > * </span><br>&nbsp<br> facebook  —  <span clasd='infog'> ссылка на вашу страницу чтобы я мог убедиться что вы не робот  ))) </span>$br <input type='text' id='input' name='fbacc' style='zoom: 1.9; '/> <span color='#f00'> * </span><br>&nbsp<br> &nbsp &nbsp <input class='send' type='submit'  name='newacc' value='отправить' style='zoom: 1.9; '></form></div>\";
	
	
	document.getElementById('modal_val').innerHTML= \"<div id='asel'><p>Для добавления этого календаря в ваш Google calendar следуйте<a href='help.php' target='blank' > инструкции </a> $br скопируйте текст в поле </p><textarea id='tails' cols='75' rows='2' readonly onfocus=\" + '\"' + \"this.select()\" + '\"' + \">\" +  icslink + \"</textarea><p> ссылка на <a href='\" + httplinck + \"' target='blank'> календарь в google </a> </p><p> HTML код для вставки на сайт </p><textarea cols='75' rows='4' readonly onfocus=\" + '\"' + \"this.select()\" + '\"' + \">\" +  freim + \"</textarea> </div>\" + divnp;
	document.getElementById('modal-00').checked = true;
	
}
		
function XmlHttp()
{
var xmlhttp;
try{xmlhttp = new ActiveXObject(\"Msxml2.XMLHTTP\");}
catch(e)
{
 try {xmlhttp = new ActiveXObject(\"Microsoft.XMLHTTP\");} 
 catch (E) {xmlhttp = false;}
}
if (!xmlhttp && typeof XMLHttpRequest!='undefined')
{
 xmlhttp = new XMLHttpRequest();
}
  return xmlhttp;
}
 
function ajax(param)
{
                if (window.XMLHttpRequest) req = new XmlHttp();
                method=(!param.method ? \"POST\" : param.method.toUpperCase());
 
                if(method==\"GET\")
                {
                               send=null;
                               param.url=param.url+\"&ajax=true\";
                }
                else
                {
                               send=\"\";
                               for (var i in param.data) send+= i+\"=\"+param.data[i]+\"&\";
                               send=send+\"ajax=true\";
                }
 
                req.open(method, param.url, true);
                if(param.statbox)document.getElementById(param.statbox).innerHTML = '<img src=\"images/wait.gif\">';
                req.setRequestHeader(\"Content-Type\", \"application/x-www-form-urlencoded\");
                req.send(send);
                req.onreadystatechange = function()
                {
                               if (req.readyState == 4 && req.status == 200) //если ответ положительный
                               {
                                               if(param.success)param.success(req.responseText);
                               }
                }
}


function Seldiv(id, id2) {
	//alert('test ' + id); 
		 document.getElementById(id).style.display = 'block';
		 document.getElementById(id2).style.display = 'none';
		 document.getElementById('dsel1').style.display = 'none';
	}
	
function Seldiv2(id) {
//alert('test ' + id); 
document.getElementById(id).select();
	}
</script>
<div id='status' style='clear: left' >

</div>
</div>
</form>";


//logtest($_COOKIE);
$test = 0;
if ((file_exists("local.ini")) || (!empty($test))) {
    echo "
<div class='tdebug' style='display: block; ' >";

//pre($calendars); 
    pre($_COOKIE);
    pre($hr);
    if (!empty ($_SESSION['calendarUA']['log']))
        foreach ($_SESSION['calendarUA']['log'] as $key => $val) {
            pre($val);
        }

    echo "</div>";
}

function send_email_requet($emailadmin, $tmpl,$lang,$acclink, $mail, $fbacc)
{

    echo "<div class='modal'>
    <input class='modal-open' id='modal-newacc' type='checkbox'  checked hidden >
    <div class='modal-wrap' aria-hidden='true' role='dialog'>
        <label class='modal-overlay' for='modal-newacc'>
        </label><div class='modal-dialog'>
            <div class='modal-header' ></div><div class='modal-body' >";
    if ((!empty ($mail)) && (!empty ($fbacc))) {
        $mail = clean($mail);
        $fbacc = clean($fbacc);
        $acclink = clean($acclink);
        $msg = sprintf($tmpl[$lang]["email_msg"], $acclink, $mail, $fbacc);
        if (mail("$emailadmin", $tmpl[$lang]["email_subj"], "$msg")) {

            echo "<p class='infog' >
                    Ваша заявка отправлена, спасибо </p>";
        } else {
            echo "<p class='infor' >
                    ошибка отправки заявки, попробуйте еще </p> ";
        }
    } else {
        echo "<p class='infor' >
                    Не все поля заполнены </p>";
    }
    echo "</div><div class='modal-footer'><label class='btn btn-primary' for='modal-newacc'> Закрыть!</label><br> &nbsp <br></div></div></div></div>";


}

?>

</HTML>

