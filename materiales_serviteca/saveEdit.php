<?php
require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$numero=$_POST['numero'];
$peso=$_POST['peso'];
$precio=$_POST['precio'];

$sql_upd=mysqli_query($enlaceCon,"update $table set nombre='$nombre', numero='$numero', peso='$peso', precio='$precio' where codigo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlList2';
			</script>";
?>