<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select max(codigo_motivo) from motivos_baja";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);
$codigo++;

$sql_inserta=mysqli_query($enlaceCon,"insert into motivos_baja values($codigo,'$tipoBaja','$baja')");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_tiposbaja.php';
			</script>";
?>