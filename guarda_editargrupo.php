<?php
require("conexion.inc");
require("estilos.inc");
$codigo			  = $_POST['codigo'];
$nombreGrupo	  = $_POST['nombre_grupo'];
$estado			  = $_POST['estado'];
$cod_tipomaterial = $_POST['cod_tipomaterial'];

$sql_upd=mysqli_query($enlaceCon,"UPDATE grupos set nombre_grupo='$nombreGrupo', 
estado='$estado', cod_tipomaterial = '$cod_tipomaterial' where cod_grupo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_grupos.php';
			</script>";
?>