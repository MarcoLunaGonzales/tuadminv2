<?php
//session_start();
require("../conexionmysqli.php");
require("../funciones.php");
require("../estilos.inc");


$fecha=date("Y-m-d");
$codigo=$_GET["codigo"];

$sql="DELETE from tipo_cambiomonedas where cod_moneda='$codigo' and fecha='$fecha'";
 //echo $sql;
$flagSuccess = mysqli_query($enlaceCon, $sql);

if($flagSuccess==true){
	echo "<script language='Javascript'>
				alert('Los datos fueron insertados correctamente.');
				setTimeout(function() {
					location.href='list.php';
				}, 2000);
				</script>";
}else{
	echo "<script language='Javascript'>
				alert('Hubo un error con el registro.');
				setTimeout(function() {
					location.href='list.php';
				}, 2000);
				</script>";
}


?>
