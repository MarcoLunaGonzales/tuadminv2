<?php
require("conexion.inc");
require("estilos.inc");

$configuracion=$_POST["configuraciones"];
$sql_inserta=mysqli_query($enlaceCon,"UPDATE configuraciones set valor_configuracion='$configuracion' where id_configuracion='6'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados!!!');
			location.href='configuraciones_sistema.php';
			</script>";
?>