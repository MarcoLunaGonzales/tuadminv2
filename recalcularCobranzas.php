<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
require('funcion_nombres.php');
require('funciones.php');


$sql="SELECT s.cod_salida_almacenes, s.monto_final from salida_almacenes s where s.cod_almacen=1000 and s.cod_tiposalida=1001 and s.salida_anulada=0";
$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$codSalida=$dat[0];
	$montoFinal=$dat[1];

	echo $codSalida." ".$montoFinal."<br>";

	$sqlVeri="SELECT c.cod_cobro from cobros_detalle c where c.cod_venta=$codSalida";
	$respVeri=mysqli_query($enlaceCon,$sqlVeri);
	$nroFilasVeri=mysqli_num_rows($respVeri);
	if($nroFilasVeri>0){
		$codCobro=mysqli_result($respVeri,0,0);

		mysqli_query($enlaceCon,"UPDATE cobros_detalle set monto_detalle=$montoFinal where cod_cobro=$codCobro ");
		mysqli_query($enlaceCon,"UPDATE cobros_cab set monto_cobro=$montoFinal where cod_cobro=$codCobro ");

		mysqli_query($enlaceCon,"UPDATE salida_almacenes set monto_cancelado=$montoFinal where cod_salida_almacenes=$codSalida");

	}


}

?>