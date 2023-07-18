<?php
require('conexion.inc');
$item=$_GET['item'];
$precio1=$_GET['precio1'];
$precio2=$_GET['precio2'];
$precio3=$_GET['precio3'];
$precio4=$_GET['precio4'];
$costo=$_GET['costo'];

$globalAgencia=$_COOKIE['global_agencia'];

	$sqlDel="delete from precios where codigo_material=$item";
	$respDel=mysqli_query($enlaceCon,$sqlDel);

	$sqlInsert="insert into precios values($item, 0,$costo,'$globalAgencia')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 1,$precio1,'$globalAgencia')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 2,$precio2,'$globalAgencia')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 3,$precio3,'$globalAgencia')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($item, 4,$precio4,'$globalAgencia')";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);

echo "Precio Guardado!";
?>