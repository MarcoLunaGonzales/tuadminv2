<?php
header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/La_Paz');

require_once 'config.php';


if(!function_exists('register_globals')){
	include('register_globals.php');
	register_globals();
}else{
}

$enlaceCon=mysqli_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);

if (mysqli_connect_errno())
{
	echo "Error en la conexión: " . mysqli_connect_error();
}
mysqli_set_charset($enlaceCon,"utf8");
?>