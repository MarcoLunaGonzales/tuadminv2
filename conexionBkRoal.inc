<?php

//header('Content-Type: text/html; charset=UTF-8'); 

if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

//$conexion=mysql_connect("www.minkasoftware.com","minkauserbd","4868422Marco$");
//$bd=mysql_select_db("tuadmintecniplast");



$conexion=mysql_connect("localhost","root","123456789");
$bd=mysql_select_db("tecniplast");

//$conexion=mysql_connect("localhost","root","123456789");
//$bd=mysql_select_db("tecniplast");
//$bd=mysql_select_db("tiendatest");

?>