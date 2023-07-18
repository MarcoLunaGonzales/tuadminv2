<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$nombre_costoimp=$_POST["nombre_costoimp"];
$codCostoImpEditar=$_POST["codCostoImpEditar"];



$globalCiudad=$_COOKIE["global_agencia"];



$modifiedBy=$_COOKIE['global_usuario'];
$modifiedDate=date("Y-m-d H:i:s");


$consulta=" update  costos_importacion  set ";
$consulta.=" nombre_costoimp='".$nombre_costoimp."',";
$consulta.=" modified_by='".$modifiedBy."',";
$consulta.=" modified_date='".$modifiedDate."' ";
$consulta.=" where cod_costoimp='".$codCostoImpEditar."'";
mysqli_query($enlaceCon,$consulta);
?>
<script type='text/javascript' language='javascript'>
  alert('Los datos fueron editados correctamente.');
  location.href='navegador_costosimp.php';
</script>





