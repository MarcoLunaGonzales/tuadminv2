<?php

require_once 'conexionmysqli2.inc';
require_once 'funcionRecalculoCostosOficial.php';

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

set_time_limit(0);

$codAlmacen=$_GET["cod_almacen"];

$sqlProductos="SELECT distinct(sd.cod_material) from salida_almacenes s, salida_detalle_almacenes sd
where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$codAlmacen' and s.fecha BETWEEN '2023-01-01' and '2023-12-31' and s.salida_anulada=0";
$respProductos=mysqli_query($enlaceCon,$sqlProductos);

while($datProductos=mysqli_fetch_array($respProductos)){
  $codigoProducto=$datProductos[0];
  echo "Codigo Producto Procesado: ".$codigoProducto."<br>";

  $banderaRecalculo=recalculaCostos($codigoProducto, $codAlmacen);
}

echo "Proceso terminado satisfactoriamente!";

?>
