
<?php
require("funciones.php");
require("estilos_almacenesAjax.php");

$nroSalida=$_GET["nroSalida"];

require("conexion.inc");
$cadRespuesta="";
$consulta="select s.`cod_salida_almacenes` from `salida_almacenes` s 
		where s.`nro_correlativo`=$nroSalida and s.`cod_almacen`=$global_almacen and s.`cod_tiposalida`='1002'";
$rs=mysql_query($consulta);
$registro=mysql_fetch_array($rs);
$cadRespuesta=$registro[0];
if($cadRespuesta=="")
{   $cadRespuesta=0;
}
echo "<input type='hidden' name='codSalida' id='codSalida' value='$cadRespuesta'>";
echo "<a href='navegador_detallesalidamateriales.php?codigo_salida=$cadRespuesta' target='_BLANK'>Detalle</a>";

?>
