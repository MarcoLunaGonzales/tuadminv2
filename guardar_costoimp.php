<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$nombre_costoimp=$_POST["nombre_costoimp"];


$globalCiudad=$_COOKIE["global_agencia"];


$sql="SELECT cod_costoimp FROM costos_importacion ORDER BY cod_costoimp DESC";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
	$codigo++;
}
$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");


$sql_inserta="INSERT INTO costos_importacion (cod_costoimp, nombre_costoimp,  estado, created_by,created_date )
		values ('".$codigo."','".$nombre_costoimp."',1, '".$createdBy."', '".$createdDate."')";
$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);
?>
<script type='text/javascript' language='javascript'>
  alert('Los datos fueron insertados correctamente.');
  location.href='navegador_costosimp.php';
</script>





