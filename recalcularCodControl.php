<?php

set_time_limit(0);

require('conexionmysqli.inc');
include 'controlcode/sin/ControlCode.php';

$sql="select s.cod_salida_almacenes, s.cod_almacen, 
(select a.nombre_almacen from almacenes a where a.cod_almacen=s.cod_almacen), s.monto_final, s.fecha, fv.cod_dosificacion, fv.nro_factura, fv.nit from salida_almacenes s, facturas_venta fv  where fv.cod_venta=s.cod_salida_almacenes and s.cod_tiposalida=1001 and s.salida_anulada=0 and s.cod_salida_almacenes in (8805, 8806, 8808, 8809) order by s.monto_final desc";

echo $sql;

$resp=mysqli_query($enlaceCon,$sql);

$bandera=0;
$sumaMontos=0;
$txtDetalle="";
while($dat=mysqli_fetch_array($resp)){
	$codSalida=$dat[0];
	$codAlmacen=$dat[1];
	$nombreAlmacen=$dat[2];
	$montoFinal=$dat[3];
	$totalFinalRedondeado=round($montoFinal);
	$fecha=$dat[4];
	$codDosificacion=$dat[5];
	$nroFactura=$dat[6];
	$nit=$dat[7];




	$fechaNuevo="20231101";
	$fechaNuevoF="2023-11-01";




	$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
	from dosificaciones d where d.cod_dosificacion='$codDosificacion'";
	$respDatosDosif=mysqli_query($enlaceCon,$sqlDatosDosif);
	while($datDosif=mysqli_fetch_array($respDatosDosif)){
		$nroAutorizacion=$datDosif[0];
		$llaveDosificacion=$datDosif[1];
	}
	
	$controlCode = new ControlCode();
	$code = $controlCode->generate($nroAutorizacion,//Numero de autorizacion
							   $nroFactura,//Numero de factura
							   $nit,//Número de Identificación Tributaria o Carnet de Identidad
							   $fechaNuevo,//fecha de transaccion de la forma AAAAMMDD
							   $totalFinalRedondeado,//Monto de la transacción
							   $llaveDosificacion//Llave de dosificación
							   );

	/*$sqlUpd="update salida_almacenes set nit='$nit', razon_social='$razonSocialNuevo' where cod_salida_almacenes='$codSalida';";
	echo $sqlUpd."<br>";*/
	//$respUpd=mysqli_query($enlaceCon,$sqlUpd);

	$sqlUpd1="<br>update facturas_venta set fv.fecha='$fechaNuevoF', codigo_control='$code' where cod_venta='$codSalida';";
	echo $sqlUpd1."<br>";
	//$respUpd=mysqli_query($enlaceCon,$sqlUpd1);
	
	$txtDetalle=$txtDetalle." ".$codSalida." NroFac:".$nroFactura." ".$nombreAlmacen." ".$totalFinalRedondeado." ".$fecha." ".$sumaMontos." ".$code."<br>";

}

echo $txtDetalle;


?>




