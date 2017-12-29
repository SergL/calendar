<?

//подключаем файл транслитерации 
include_once("php/translit.fn");


if (!empty ($debug))
{
	$lokfail=(__FILE__);
//  преобразования путей в массивы
$faillocal=explode("/", "$lokfail");
		$name=end($faillocal);
		echo " <p> имя текущего скрипта <br> $name </p>";
}
//резервная копия файла
$lokfail=(__FILE__);
//  преобразования путей в массивы
$faillocal=explode("/", "$lokfail");
$name=end($faillocal);
// если получилось сделать резервную копию
$file= "$name";
$newfile = "copy_$name";
copy($file, $newfile);

 
echo " <input type='hidden' name='metrolbd' value='$metrolbd'>";
 echo " <h2> работа с базами данных msql</h2>";


//-------------------------------------------------------------------------------------
// добавляем строку в базу
if (!empty ($newstrtable))
{
	//проверка уникальности для таблицы пользователей 
	if ($tableid=='users')
	{
		$newuser=$newstr['login'];
		foreach ($_SESSION[metrology][$operator][table][tbody] as $key=>$val)
		{
			if ($newuser==$val['login'])
			{
				info('такой логин уже есть ');
				exit;
			}
		}
	}

	
foreach ($newstr as $key =>$val)
{
	if (($str[$key]=='not') && (empty ($val)))
	{
		info("заполните поле $key");
		exit; 
	}
	$key=ftrim($key);
	$val=ftrim($val);
	$vkey[]="`$key`";
	$strval[]="'$val'";
}
$keystr=implode(", ", $vkey);
$valstr=implode(", ", $strval);

$sql = "INSERT INTO `$tableid` ($keystr) VALUES ($valstr);";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// переименовать столбец
if (!empty ($rencol))
{
	if (!empty ($rencolcoment[$rencol]))
	{
		$rencolcoment=ftrim($rencolcoment[$rencol]);
		$comment="COMMENT '$rencolcoment'";
	}
$sql = "ALTER TABLE `$tableid` CHANGE `$rencol` `$rencols[$rencol]` $coltype[$rencol] NULL DEFAULT NULL $comment;";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// создать столбец (поле)
if (!empty ($newcoltable))
{
	
$sql = "ALTER TABLE `$tableid` ADD `$newcolname` $newcoltype $colnull COMMENT '$colcoment' AFTER `$colafter`;";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// удалить столбец (поле)
if (!empty ($delcol))
{
	
$sql = "ALTER TABLE `$tableid` DROP `$delcol`;";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// удалить строку 
if (!empty ($delstr))
{
	
$sql = "DELETE FROM `$tableid` WHERE $idkey ='$delstr';";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// переписать ячейку в строке 
if (!empty ($redtd))
{
	$idstr=key($redtd);
	$val=$redtd[$idstr];
	$td=$valtd[$idstr][$val];
$sql = "UPDATE `$tableid` SET `$redtd[$idstr]` = '$td' WHERE $idkey = '$idstr';";
//echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// изменить заголовок таблицы
if (!empty ($redcaption))
{
$sql = "ALTER TABLE `$tableid` COMMENT '$newcaption';";
echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$tableid; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// изменить имя таблицы
if (!empty ($redtabname))
{
	$newname =translit($newtabname);
$sql = "RENAME TABLE $tableid TO $newname;";
echo $sql;
//делаем запрос к базе
querymsql($sql);
$tabnamesql=$newname; 
} 

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// изменить индекс паспорта в таблице
if (!empty ($redstrpas))
{
	//получаем массив паспорт и id строки 
	$tbody =$_SESSION[metrology][$operator][table][tbody]; 
	$size=sizeof($tbody);
	info(" количество строк $size");
	$test=array_slice($tbody, 0, 5);
	foreach ($tbody as $key=>$value )
	{
		
	$idstr=$value[id];
	$pkey=explode("-", $value['Паспорт']);
	$k1=$pkey[1];
	$val="$replstr"."-$k1";
$sql = "UPDATE `$tableid` SET `Паспорт` = '$val' WHERE $idkey = '$idstr';";
//делаем запрос к базе
querymsql($sql);
}

$tabnamesql=$newname; 
} 

//-------------------------------------------------------------------------------------




$tabnamesql='GoogleCal';

//выводим данные таблицы

if (!empty ($tabnamesql))
{
	unset($_SESSION[metrology][$operator][table]);
	$sql = "SHOW COLUMNS FROM $tabnamesql";
	$result=arraymsql($sql);
	//pre($sql);
	//pre($result);
	foreach ($result as $key =>$value)
	{
		$val=$value['Field'];
		$htable[]=$val;
		$htype[$val]=$value['Type'];
		$hnotnull[$val]=$value['Null'];
		
	}
	//pre($hnotnull);
//получаем комментарий к таблице. 
$sql = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = 'metrology' AND table_name = '$tabnamesql';";

$result=arraymsql($sql);

//делаем из комментария заголовок
if (!empty ($result))
{
foreach ($result as $key=>$val)
{
	foreach ($val as $key=>$val)
	{
		$caption =$val; 
	}
}
}
else {
	//$caption =retranslit($tabnamesql);
	}
	
//получаем информацию о таблице
$sql="SHOW CREATE TABLE `$tabnamesql`;";
//echo $sql; 
$result=arraymsql($sql);
//pre($result);
foreach ($result as $key=>$val)
{
	$teststr=explode("\n", $val['Create Table']);
}
$size=sizeof($htable)-1;
$teststr=array_slice($teststr, 2, $size);
foreach ($teststr as $key =>$val)
{
	if (preg_match("/COMMENT/i", $val))
	{
	   $hcom[]=explode("` ", $val);
	}
}
//Получаем комментарии к столбцам
if (!empty ($hcom))
{
foreach ($hcom as $key=>$val)
{
   $hcomkey=explode("`", $val[0]);
   $hcomkey =$hcomkey[1];
   //pre($hcomkey);
   $hcom=explode("COMMENT", $val[1]);
   $hcom=explode("'", $hcom[1]);
   $hcom=$hcom[1];
   $hcomment[$hcomkey]=$hcom;
  }
	//pre($hcomment);
}
	
	$sql = "SELECT * FROM $tabnamesql";
	$tbody=arraymsql($sql);
	//pre($tbody);
	$tableid=$tabnamesql;
	
$_SESSION[metrology][$operator][table][caption]=$caption; 
$_SESSION[metrology][$operator][table][htable]=$htable; 
$_SESSION[metrology][$operator][table][hcomment]=$hcomment; 
$_SESSION[metrology][$operator][table][hnotnull]=$hnotnull; 
$_SESSION[metrology][$operator][table][htype]=$htype; 
$_SESSION[metrology][$operator][table][tbody]=$tbody; 

}

if (!empty ($_SESSION[metrology][$operator][table][htable]))
{
$caption=$_SESSION[metrology][$operator][table][caption]; 
$htable =$_SESSION[metrology][$operator][table][htable]; 
$hcomment=$_SESSION[metrology][$operator][table][hcomment]; 
$hnotnull =$_SESSION[metrology][$operator][table][hnotnull];
$htype=$_SESSION[metrology][$operator][table][htype];
$tbody =$_SESSION[metrology][$operator][table][tbody]; 
}



if (!empty ($sortbu)) 
{
	$sortch[$sortbu]='checked'; 
}

//сортировка таблицы 
echo " <p> параметры сортировки </p>";
if (empty($sortcol))
$sortcol='Наименование СИТ';
echo " <select name='sortcol' size='1'>";
if (!empty ($sortcol)) 
echo "<option value='$sortcol'>$sortcol</option>";
foreach ($htable as $key=>$value) 
{
	if ($value!=$sortcol) 
	echo "<option value='$value'>$value</option>";
}

echo "</select>
<style>
	.rd {
		zoom: 4;
		}
</style>
<input class='rd' type='radio' name='sortbu' value='S' checked>по возрастанию 
<input class='rd' type='radio' name='sortbu' value='R' $sortch[R] >по убыванию 
<br>&nbsp<br>
<input formaction='metrolab/metrolog.php#table'  class='send' type='submit' name='sorttable' value='сортировать'>";


	//pre($tbody[2]);
	foreach($tbody as $key=>$value)
	{
		foreach($value as $k=>$val)
		{
			if ($k==$sortcol)
			{
				$sortarray[]=$val;
			}
		}
	}

array_multisort($sortarray, SORT_STRING, $tbody);
if ($sortbu=='R')
{
  $tbody=array_reverse($tbody);  
}

if (!empty ($htable))
{
//строим таблицу
echo " <input type='hidden' name='tableid' value='$tableid'>";
echo " <input type='hidden' name='idkey' value='$htable[0]'>";
$rttablname=retranslit($tableid);
	echo "$br $br 
	<p>строка замены для паспорта </p>
	 <input type ='text' size='4' name ='replstr' value='0'></input>
		<button class ='inlab' name='redstrpas' value='redacted'> изменить индекс паспорта </button >
		
	<h3>  <!-- Кнопка активации -->
<label class='btn$vpov' for='modal-name' > <font class ='inlab' > $rttablname </font> </label>
<!-- Модальное окно -->
<div class='modal'>
  <input class='modal-open' id='modal-name' type='checkbox' hidden>
  <div class='modal-wrap' aria-hidden='true' role='dialog'>
    <label class='modal-overlay' for='modal-name></label>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <h2>$rettablname </h2>
        
        <label class='btn-close' for='modal-name' aria-hidden='true'>×</label>
      </div>
      <div class='modal-body'>$br
<p>имя таблицы</p>
<div class='textarea'>
 <textarea class='modal' cols='25' rows='5' name='newtabname' >$rttablname</textarea ></div>

        	
      <br> &nbsp <br> 
<div class ='leftstr'> 
test
</div> 
 <div class ='rightstr'> 
<button class ='inlab' name='redtabname' value='redacted'> изменить имя таблицы </button >
</div>
<div style='clear: left'><hr></div>

      </div>
      <div class='modal-footer'>
        <label class='btn btn-primary' for='modal-name'> Закрыть!</label>
      </div>
    </div>
  </div>
</h3>
	
	
	
	
	<a name='table'></a>
<table class ='bd'><caption > <!-- Кнопка активации -->
<label class='btn$vpov' for='modal-capt' > <font class ='inlab' > $caption </font> </label>
<!-- Модальное окно -->
<div class='modal'>
  <input class='modal-open' id='modal-capt' type='checkbox' hidden>
  <div class='modal-wrap' aria-hidden='true' role='dialog'>
    <label class='modal-overlay' for='modal-capt'></label>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <h2>$tableid</h2>
        
        <label class='btn-close' for='modal-capt' aria-hidden='true'>×</label>
      </div>
      <div class='modal-body'>$br
<p>заголовок </p>
<div class='textarea'>
 <textarea class='modal' cols='25' rows='5' name='newcaption' >$caption</textarea ></div>

        	
      <br> &nbsp <br> 
<div class ='leftstr'> 

</div> 
 <div class ='rightstr'> 
<button class ='inlab' name='redcaption' value='redacted'> изменить заголовок </button >
</div><div style='clear: left'><hr></div>

      </div>
      <div class='modal-footer'>
        <label class='btn btn-primary' for='modal-capt'> Закрыть!</label>
      </div>
    </div>
  </div>
</div></caption >";
echo "<tr>";
foreach ($htable as $key =>$val)
{
	if (!preg_match("/id/i", $val))
	{
		$kp=$val; 
	echo "<th>  <!-- Кнопка активации -->
<label class='btn$vpov' for='modal-$kp' > <font class ='reremont' > $val </font> </label>
<!-- Модальное окно -->
<div class='modal'>
  <input class='modal-open' id='modal-$kp' type='checkbox' hidden>
  <div class='modal-wrap' aria-hidden='true' role='dialog'>
    <label class='modal-overlay' for='modal-$kp'></label>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <h2> изменить $val </h2>
        $hcomment[$val]
        <label class='btn-close' for='modal-$kp' aria-hidden='true'>×</label>
      </div>
      <div class='modal-body'>$br
        <font class ='leftstr'> имя </font> 
        <font class ='rightstr'> 
        <input type ='text' size='12' name ='rencols[$val]' value='$val'></font><div style='clear: left'><hr></div>
        	
        <font class ='leftstr'> тип </font> <font class ='rightstr'>
 <select name='coltype[$val]' size='1'>";
if (preg_match("/int/i", $htype[$val]))
{
	echo "
        <option value='INT'> число
        <option value='TEXT CHARACTER SET utf8 COLLATE utf8_general_ci'> текст";
        }
       else {
	echo "
        <option value='TEXT CHARACTER SET utf8 COLLATE utf8_general_ci'> текст
        <option value='INT'> число";
        }
    echo "   
 </select >
</font><div style='clear: left'><hr></div>

     <font class ='leftstr'> комментарий </font> 
     <font class ='rightstr'> 
     <input type ='text' size='12' name ='rencolcoment[$val]' value='$hcomment[$val]'></font><div style='clear: left'><hr></

      <br>&nbsp<br> 
<div class ='leftstr'> 
<button class ='remont' name='delcol' value='$val'> <font size ='150%'> удалить </font></button >
</div> 
 <div class ='rightstr'> 
<button class ='inlab' name='rencol' value='$val'><font size ='300%'>  сохранить </font ></button >
</div><div style='clear: left'><hr></div>

      </div>
      <div class='modal-footer'>
        <label class='btn btn-primary' for='modal-$kp'> Закрыть!</label>
      </div>
    </div>
  </div>
</div></th>";
	}
}
echo "</tr>";

if (!empty ($tbody))
{
if (empty($ax))
{
	$ax=0;
	$sax=20;
}
$tbodyslice=array_slice($tbody, $ax, $sax);
	
foreach ($tbodyslice as $key =>$val)
{
echo "<tr>";
unset($kkey); 
	foreach ($val as $key =>$val)
	{
	if (preg_match("/id/i", $key))
	{
		$strid=$val; 
	}
	else {
		$kkey[]=$key; 
		$fkey=$kkey[0];
		if (($fkey==$key) && ($val!='admin'))
		{
			$kp=$strid; 
			echo "<td> <!-- Кнопка активации -->
<label class='btn$vpov' for='modal-$kp' > <font class ='reremont' > $val </font> </label>
<!-- Модальное окно -->
<div class='modal'>
  <input class='modal-open' id='modal-$kp' type='checkbox' hidden>
  <div class='modal-wrap' aria-hidden='true' role='dialog'>
    <label class='modal-overlay' for='modal-$kp'></label>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <h2>id $strid - $val </h2>
        
        <label class='btn-close' for='modal-$kp' aria-hidden='true'>×</label>
      </div>
      <div class='modal-body'>$br
<font class ='leftstr'> значение </font> 
        <font class ='rightstr'> 
        <input type ='text' size='12' name ='valtd[$strid][$key]' value ='$val'></font><div style='clear: left'><hr></div>
        	
      <br>&nbsp<br> 
<div class ='leftstr'> 
<button class ='remont' name='delstr' value='$strid'> <font size ='150%'> удалить строку </font></button > 
<input type ='hidden' name ='idstr' value ='$strid'>
</div> 
 <div class ='rightstr'> 
<button class ='inlab' name='redtd[$strid]' value='$key'><font size ='300%'> изменить ячейку </font ></button >
</div><div style='clear: left'><hr></div>

      </div>
      <div class='modal-footer'>
        <label class='btn btn-primary' for='modal-$kp'> Закрыть!</label>
      </div>
    </div>
  </div>
</div> </td>";
		}
		elseif ($val!='admin')
		{
			if (empty ($val))
			{
				$val="добавить $key";
			}
			$kp="$strid.$val"; 
			echo "<td> <!-- Кнопка активации -->
<label class='btn$vpov' for='modal-$kp' > <div class ='inlab' > $val </div> </label>
<!-- Модальное окно -->
<div class='modal'>
  <input class='modal-open' id='modal-$kp' type='checkbox' hidden>
  <div class='modal-wrap' aria-hidden='true' role='dialog'>
    <label class='modal-overlay' for='modal-$kp'></label>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <h2>id $strid - $val </h2>
        
        <label class='btn-close' for='modal-$kp' aria-hidden='true'>×</label>
      </div>
      <div class='modal-body'>$br
<font class ='leftstr'> значение </font> 
        <font class ='rightstr'> 
        <input type ='text' size='12' name ='valtd[$strid][$key]' value ='$val'></font><div style='clear: left'><hr></div>
        	
      <br>&nbsp<br> 
<div class ='leftstr'> 
<input type ='hidden' name ='idstr' value ='$strid'>
</div> 
 <div class ='rightstr'> 
<button class ='inlab' name='redtd[$strid]' value='$key'><font size ='300%'> изменить ячейку </font ></button >
</div><div style='clear: left'><hr></div>

      </div>
      <div class='modal-footer'>
        <label class='btn btn-primary' for='modal-$kp'> Закрыть!</label>
      </div>
    </div>
  </div>
</div> </td>";
		}
		else {
			echo "<td> $val </td>";
			}
		}
	}
	echo "</tr>";
}
}

echo "</table >
тест";


$axn=$ax-$sax;
if ($axn<0) $axn=0;
$ay=$ax+$sax;
$ayen=sizeof($tbody)-$sax;

  echo " <input type='hidden' name='sax' value='$sax'>
<h2 align='left'> кол-во строк ";

if (empty($stabcol))
$stabcol=20;
echo " <select name='stabcol' size='1'>";
if (!empty ($stabcol)) 
echo "<option value='$stabcol'>$stabcol</option>";
for ($x=1; $x<=10; $x++) 
{
	$y=$y+10;
	if ($y!=$stabcol) 
	echo "<option value='$y'>$y</option>";
}

echo "</select>
	
	
	
<button class='send' type='submit' name='ax' value='0'> __ << 1 __ </button>     
<button class='send' type='submit' name='ax' value='$axn'> __ < $axn __ </button>     <button class='send' type='submit' name='ax' value='$ay'> __ $ay > __ </button>     <button class='send' type='submit' name='ax' value='$ayen'> __ $ayen >> __ </button>
</h2>";





if ($tableid=='users')
{
	$rowval='создать нового пользователя';
}
else 
{
	$rowval='добавить строку';
}
echo "$br $br

	<div class='accordion'>
  <input class='toggle-box' id='blockstr-1' type='checkbox'>
    <label for='blockstr-1'> $rowval </label>
  <div class='box'>$br";
  foreach ($htable as $key =>$val)
{
	if (!preg_match("/id/i", $val))
	{
		$notnull='';
		$not='';
		if ($hnotnull[$val]=='NO')
		{
			$notnull="<font color='#ff0000' ><b><big>*</big></b></font>";
			$not='not';
		}
		echo " <input type='hidden' name='str[$val]' value='$not'>";
		if (($tableid=='users') && ($val=='Доступ'))
		{
			
	echo " <font class ='leftstr'> $val </font> <font class ='rightstr'> <select size='1' name ='newstr[$val]'>
<option value='user'> просмотр
<option value='holder'> список инструмента 
<option value='metrolog'> результаты поверки 
</select >
$notnull</font><div style='clear: left'><hr></div> ";
		}
		else {
	echo " <font class ='leftstr'> $val </font> <font class ='rightstr'> <input type ='text' size='12' name ='newstr[$val]'></input >$notnull</font><div style='clear: left'><hr></div> ";
	}
	}
	else {
		$keyid=$val; 
		echo " <input type='hidden' name='keyid' value='$keyid'>";
		}
}
echo "
	<br>&nbsp<br>
<input class='send' type='submit' name='newstrtable' value='создать строку'>";
echo "
  </div>
</div>";

//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
echo "$br $br
	<div class='accordion'>
  <input class='toggle-box' id='bloccols-1' type='checkbox'>
    <label for='bloccols-1'> создать столбец </label>
  <div class='box'>$br 
          <font class ='leftstr'> имя </font> 
        <font class ='rightstr'> 
        <input type ='text' size='12' name ='newcolname'></font><div style='clear: left'><hr></div>
        	
        <font class ='leftstr'> тип </font> <font class ='rightstr'>
 <select name='newcoltype' size='1'>
        <option value='TEXT CHARACTER SET utf8 COLLATE utf8_general_ci'> текст
        <option value='INT'> число
 </select >
</font><div style='clear: left'><hr></div>
        <font class ='leftstr'> вставить после :</font> <font class ='rightstr'>
 <select name='colafter' size='1'>";
 $hval=$htable;
 $hlast=array_pop($hval);
 echo "<option value='$hlast'> $hlast";
  foreach ($hval as $key =>$val)
{
	if (!preg_match("/id/i", $val))
	{
		echo "<option value='$val'> $val";
	}
	else {
		$first=$val; 
		}
		
}
echo " <option value='$first' checked > в начале таблицы
 </select >
</font><div style='clear: left'><hr></div>
      <font class ='leftstr'> обязательное поле </font> 
      <font class ='rightstr'> 
        <input class ='checkbox' type ='radio'  name ='colnull' value ='NOT NULL'>да 
        <input class ='checkbox' type ='radio'  name ='colnull' value ='NULL' checked >нет</font><div style='clear: left'><hr></div>
          <font class ='leftstr'> комментарий </font> 
        <font class ='rightstr'> 
        <input type ='text' size='12' name ='colcoment'></font><div style='clear: left'><hr></div>
	<br>&nbsp<br>
<input class='send' type='submit' name='newcoltable' value='создать столбец '>";
echo "
  </div>
</div>";

}


echo " <input type='hidden' name='metrolbd' value='$metrolbd'>";


?>