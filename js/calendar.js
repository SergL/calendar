
if (lang=='en') 
{
	var modal1='close'; 
}
else {
	var modal1='Закрыть'; 
}
	var kp='00';
var xdmv="<div class='modal'><input class='modal-open' id='modal-" + kp + "' type='checkbox' hidden ><div class='modal-wrap' aria-hidden='true' role='dialog'><label class='modal-overlay' for='modal-" + kp + "'></label><div class='modal-dialog'><div class='modal-header' id='modal_header' ></div><div class='modal-body' id='modal_val' ></div><div class='modal-footer'><label class='btn btn-primary' for='modal-" + kp + "'> " + modal1 + "!</label><br> &nbsp <br></div></div></div></div>";

var kp='01';
var xdmv2="<div class='modal'><input class='modal-open' id='modal-" + kp + "' type='checkbox' hidden ><div class='modal-wrap' aria-hidden='true' role='dialog'><label class='modal-overlay' for='modal-" + kp + "'></label><div class='modal-dialog'><div class='modal-header' id='modal_header2' ><label class='btn-close' for='modal-" + kp + "' aria-hidden='true'>×</label></div><div class='modal-body' id='modal_val2' > </div><div class='modal-footer'><label class='btn btn-primary' for='modal-" + kp + "'> " + modal1 + "!</label><br> &nbsp <br></div></div></div></div>";

function Modal(text, xd) { 

	document.getElementById('modaldiv').innerHTML= xdmv + xdmv2;
var vname='';
 var x=0;
	var val=text.split('|');
	for (var key in val) {
 var v=val[key].split('_');
 var caln=v[0];
 var ckey=v[1];
 //alert("'" + caln + "'" + ' ' + ckey);
 //x=jscalendar[caln][ckey]['name'];
 //alert(x);
 if (lang=='en') 
 {
 	 var cname="<div class='modal-note'><font color='#fff'> " + caln + "</font> <br>"+ jscalendar[caln][ckey]['name'] + ' - ' + jscalendar[caln][ckey]['time'] + ' <br> ' + jscalendar[caln][ckey]['location'] + " <span class='right'><button> in detail </button></span><br> </div>";
 }
 else {
 var cname="<div class='modal-note' ><font color='#fff'> " + caln + "</font> <br>"+ jscalendar[caln][ckey]['name'] + ' - ' + jscalendar[caln][ckey]['time'] + ' <br> ' + jscalendar[caln][ckey]['location'] + " <span class='right'><button > подробно </button></span> <br></div>";
 }
// alert(cname);
cname="<span onclick=" + '"' + "Modalinfo('" + caln + "', '" + ckey + "');" + '"' + ">" + cname + '</span>';
vname=cname + "" + vname;
}
	document.getElementById('modal_val').innerHTML= vname;
if (lang=='en') 
 {
	document.getElementById('modal_header').innerHTML="<p> Date  : " + xd + "</p>      <label class='btn-close' for='modal-00' aria-hidden='true'>×</label>";
}
else {
	document.getElementById('modal_header').innerHTML="<p> Дата  : " + xd + "</p>      <label class='btn-close' for='modal-00' aria-hidden='true'>×</label>";
}
	document.getElementById('modal-00').checked = true;
	
}

function Modalinfo(text, xd) { 

var nstr='';
var str=jscalendar[text][xd]['desc'];
//alert(str);
var valstr=str.split(' ');
for (var key in valstr) {
var v=valstr[key]
if(v.match(/http/)) 
{
	if(v.match(/facebook/)) 
{
	v=v.replace("://m.", "://");
	var tlinck='facebook'; 
	//alert(v);
}
else {
	var tlinck='ссылка'; 
}
	vnstr=" <a target='blank' href='" + v + "' > " + tlinck + "</a> "; 
	//alert(nstr);
}
else {
	vnstr=' ' + v;
}
nstr=nstr + vnstr;
}
if (lang=='en') 
 {
var vname="<div class='modal-note2'>" + '<font color=blue> event: </font>' + jscalendar[text][xd]['name'] + '<br>' + '<font color=blue> time: </font>' + jscalendar[text][xd]['time'] + '<br>' + '<font color=blue> location: </font>' + jscalendar[text][xd]['location'] + '<br>' + '<font color=blue> the details: </font>' + nstr + "</div>";
	document.getElementById('modal_header2').innerHTML="<p> Calendar  : " + text + "</p>      <label class='btn-close' for='modal-01' aria-hidden='true'>×</label>";
}
else {
	var vname="<div class='modal-note2'>" + '<font color=blue> событие: </font>' + jscalendar[text][xd]['name'] + '<br>' + '<font color=blue> время: </font>' + jscalendar[text][xd]['time'] + '<br>' + '<font color=blue> место: </font>' + jscalendar[text][xd]['location'] + '<br>' + '<font color=blue> детали: </font>' + nstr + "</div>";
	document.getElementById('modal_header2').innerHTML="<p> Календарь  : " + text + "</p>      <label class='btn-close' for='modal-01' aria-hidden='true'>×</label>";
}
	document.getElementById('modal_val2').innerHTML=vname;
	document.getElementById('modal-01').checked = true;
}
