<?php

ini_set('post_max_size','100M');

require("conexion.inc");
require("estilos.inc");


foreach($_POST as $nombre_campo => $valor)
{
  echo $nombre_campo ." ". $valor . "<br/>"; 
}
/*
$sql="select codigo_material from material_apoyo";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){

	$codigoMat=$dat[0];
	$precio1=$_POST[$codigoMat.'|1'];
	$precio2=$_POST[$codigoMat.'|2'];
	$precio3=$_POST[$codigoMat.'|3'];
	$precio4=$_POST[$codigoMat.'|4'];
	
	
	$sqlDel="delete from precios where codigo_material=$codigoMat";
	$respDel=mysqli_query($enlaceCon,$sqlDel);
	
	$sqlInsert="insert into precios values($codigoMat, 1,$precio1)";
	echo "$sqlInsert<br>";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 2,$precio2)";
	echo "$sqlInsert<br>";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 3,$precio3)";
	echo "$sqlInsert<br>";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
	$sqlInsert="insert into precios values($codigoMat, 4,$precio4)";
	echo "$sqlInsert<br>";
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	
}*/

/*echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_precios.php';
			</script>";
*/

echo "termino";

?>