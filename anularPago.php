<html>
    <head>

    </head>
    <body>
<?php

require("conexion.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
require('home_almacen.php');
require('funciones.php');

$codPago=$_GET['codigo_registro'];

//anulamos el registro

$sql="UPDATE cobros_cab set cod_estado=2 where cod_cobro='$codPago' ";
//echo $sql;
$resp=mysql_query($sql);


$sqlDet="SELECT cod_venta, monto_detalle from cobros_detalle where cod_cobro='$codPago' ";
$respDet=mysql_query($sqlDet);

while($datDet=mysql_fetch_array($respDet)){
	$codVenta=$datDet[0];
	$montoDet=$datDet[1];

	$sqlUpd="UPDATE salida_almacenes set monto_cancelado=monto_cancelado-$montoDet where cod_salida_almacenes=$codVenta ";
	//echo $sqlUpd;
	$respUpd=mysql_query($sqlUpd);

}

?>

<script>
	alert('Se anulo el cobro.');
	location.href='navegadorCobranzas.php';
</script

    </body>
</html>
