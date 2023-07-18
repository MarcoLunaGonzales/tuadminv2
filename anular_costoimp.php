<?php

require("conexion.inc");
require('function_formatofecha.php');
require('funciones.php');

$codCostoImp=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE costos_importacion set estado=0 where cod_costoimp='".$codCostoImp."'";
$resp=mysqli_query($enlaceCon,$sql);


?>
echo "<script>
	alert('Se anulo el Item de Importacion.');
	location.href='navegador_costosimp.php';
</script>";
