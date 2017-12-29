<?

echo "
<font color='red' bgcolor='blue'>R</font>
<input type ='text' size='3' name ='tr' value='$tr' >
	<font color='greenyellow' >G</font>
	<input type ='text' size='3' name ='tg' value='$tg' >
		<font color='lightskyblue' >B</font>
<input type ='text' size='3' name ='tb' value='$tb' >
 &nbsp  &nbsp ";
 
 
 $fontfamily['Arial, Helvetica, sans-serif']='Arial (serif)';
 $fontfamily['Arial Black, Gadget, sans-serif']='Arial Black (sans-serif)';
 $fontfamily['Comic Sans MS, cursive']='Comic Sans MS (cursive)';
 $fontfamily['Courier New, Courier, monospace']='Courier New (monospace)';
 $fontfamily['Lucida Console, Monaco, monospace']='Lucida Console (monospace)';
 $fontfamily['Lucida Sans Unicode, Lucida Grande, sans-serif']='Lucida Sans Unicode (sans-serif)';
 $fontfamily['Palatino Linotype, Book Antiqua, Palatino, serif']='Palatino Linotype (serif)';
 $fontfamily['Tahoma, Geneva, sans-serif']='Tahoma (sans-serif)';
 $fontfamily['Times New Roman, Times, serif']='Times New Roma (serif)';
 $fontfamily['Trebuchet MS, Helvetica, sans-serif']='Trebuchet MS (sans-serif)';
 $fontfamily['Verdana, Geneva, sans-serif']='Verdana (sans-serif)';
 $fontfamily['MS Sans Serif, Geneva, sans-serif']='MS Sans Serif (sans-serif)';
 $fontfamily['MS Serif, New York, serif']='MS Serif (serif)';

echo "выбрать шрифт <select name='font'>";

if (!empty ($bodyfontface))
{
	echo "<option value='$bodyfontface'> $fontfamily[$bodyfontface]"; 
}

foreach ($fontfamily as $key=>$val)
{
	$face=explode(",", $key);
	$face=$face[0];
	echo "<option value='$key'> <font face='$face'>$val</font>"; 
}
 echo "</select >";
 
 
if ($txti=='italic')
{
	 echo "  &nbsp <input type='checkbox' name='txti' value='italic' checked > <span class='italic'> курсивом </span>";
}
else 
{
	  echo "  &nbsp <input type='checkbox' name='txti' value='italic' ><span class='italic'> курсивом</span> ";
}

if ($txtc=='small-caps')
{
	 echo "  &nbsp <input type='checkbox' name='txtc' value='small-caps' checked > <span class='smcaps'>капитель</span>  ";
}
else 
{
	  echo "  &nbsp <input type='checkbox' name='txtc' value='small-caps' > <span class='smcaps'>капитель </span>";
}

 
 
$boldarray[normal]='нормально';
$boldarray[bold]='жирный';
$boldarray[bolder]='очень жирный';

echo " $br $br &nbsp  &nbsp жирность <select name='bold'>";

if (!empty ($bold))
{
	echo "<option value='$bold'> $boldarray[$bold]"; 
}

foreach ($boldarray as $key=>$val)
{
	if ($key!=$bold)
	{
	echo "<option value='$key'> $val "; 
	}
}
 echo "</select >";
 
 echo "&nbsp  &nbsp размер <select name='sizefont'>";

if (!empty ($sizefont))
{
	echo "<option value='$sizefont'> $sizefont (em)"; 
}

$b=0.5;
for ($a=0; $b<3; $a++)
{
	$b=$b+0.1;
	echo "<option value='$b'> $b (em)"; 
}
 echo "</select > ";

if ($shadowtxt=='1')
{
	$txtshadow='checked'; 
	$swblock='block';
}
else
{
	$swblock='none';
}


$xsw=$xsw+1;
echo "
<p>
    <input type='checkbox' id='shadow$xsw' name='shadowtxt' value='1' $txtshadow ><span>Включить тень  </span> (параметры тени )</p>";
 
 
	echo "<div id='Label$xsw' style='display: $swblock;'>
&nbsp отступ от текста 
<input type ='text' size='1' name ='swtxt' value='$swtxt' ><span> px </span>
&nbsp 
&nbsp размытие  
<input type ='text' size='2' name ='swbltxt' value='$swbltxt' ><span> px </span>
&nbsp 
&nbsp цвет тени 
<input type ='text' size='11' name ='swrgbtxt' value='$swrgbtxt' > rgb
&nbsp 
</div>
<script>
	document.getElementById('shadow$xsw').onclick = function() {
    if ( this.checked ) {
        document.getElementById('Label$xsw').style.display='block';
    } 
else {
        document.getElementById('Label$xsw').style.display='none';
  }
}  
  </script>
";

$checkaligntxt[$aligntxt]='checked'; 

echo "
	<br>&nbsp<br>
	  Выравнивание текста :<br>
              <input type='radio' name='aligntxt' value='left' $checkaligntxt[left]> <span>слева</span><br>
             <input type='radio' name='aligntxt' value='right' $checkaligntxt[right]><span> справа </span><br>
              <input type='radio' name='aligntxt' value='center' $checkaligntxt[center]>  <span>по центру</span><br>
              <input type='radio' name='aligntxt' value='justify' $checkaligntxt[justify]>  <span>равномерно </span>
              $br
              $br ";
 ?>