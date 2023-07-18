<?php

require("conexion.inc");
require('function_formatofecha.php');
require('funciones.php');

$codGasto=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE gastos set estado=2 where cod_gasto='$codGasto'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script>
	alert('Se anulo el gasto.');
	location.href='navegador_gastos.php';
</script>";
?>

