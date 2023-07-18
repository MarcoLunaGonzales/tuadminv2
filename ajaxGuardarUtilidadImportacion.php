<?php
require('conexion.inc');
require('funciones.php');
$item=$_GET['item'];
$codIngresoAlmacen=$_GET['cod_ingreso_almacen'];
$margenmayor=$_GET['margenmayor'];
$margenmenor=$_GET['margenmenor'];
$costobase=$_GET['costobase'];

$globalAgencia=$_COOKIE['global_agencia'];

$precioMargenMayor=$costobase+($costobase*($margenmayor/100));
$precioMargenMenor=$costobase+($costobase*($margenmenor/100));

$sqlUpd="update ingreso_detalle_almacenes set margen_xmayor='$margenmayor', margen_xmenor='$margenmenor',
precio_clientexmayor='$precioMargenMayor', precio_clientexmenor='$precioMargenMenor' where cod_ingreso_almacen='$codIngresoAlmacen' and cod_material='$item'";
//echo $sqlUpd;
$respUpd=mysqli_query($enlaceCon,$sqlUpd);

$precioMargenMayorF=formatonumeroDec($precioMargenMayor);
$precioMargenMenorF=formatonumeroDec($precioMargenMenor);
echo "Precios Guardados!<br>
Precio x Mayor: $precioMargenMayorF<br>
Precio x Menor: $precioMargenMenorF";
?>