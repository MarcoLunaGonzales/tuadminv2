<?php
require("conexion.inc");
require("estilos.inc");
require('funciones.php');

$upd_cod_material = $_GET['upd_cod_material'];
$upd_nombre 	  = $_GET['upd_nombre'];

$sqlUpd="UPDATE material_apoyo 
		SET descripcion_material = '$upd_nombre'
		WHERE codigo_material = '$upd_cod_material'";
$respUpd = mysqli_query($enlaceCon, $sqlUpd);

if($respUpd){
		echo "<script language='Javascript'>
			alert('Los datos fueron actualizados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	
?>