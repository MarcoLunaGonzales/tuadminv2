<?php

set_time_limit(0);

require('conexionmysqli2.inc');
include 'controlcode/sin/ControlCode.php';

//$nitNuevo="4868422016";
//$nitNuevo="120361029";
$nitNuevo="5353259017";


//$razonSocialNuevo="Marco Luna";
$razonSocialNuevo="Guillermo Suarez Balcazar";
$montoCliente=30000;

$sql="select s.cod_salida_almacenes, s.cod_almacen, 
(select a.nombre_almacen from almacenes a where a.cod_almacen=s.cod_almacen), s.monto_final, s.fecha, fv.cod_dosificacion, fv.nro_factura from salida_almacenes s, facturas_venta fv  where fv.cod_venta=s.cod_salida_almacenes and s.fecha BETWEEN '2022-02-01' and '2022-02-28' and s.cod_tiposalida=1001 and s.nit=0 and s.salida_anulada=0 and s.monto_final>100 and s.razon_social in ('SN','1207SN') order by s.monto_final desc";
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

	$sumaMontos=$sumaMontos+$montoFinal;
	if($sumaMontos<=$montoCliente){
		$bandera=1;
		//echo $codSalida." ".$codAlmacen." ".$nombreAlmacen." ".$montoFinal." ".$fecha." ".$sumaMontos."<br>";

		$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
		from dosificaciones d where d.cod_dosificacion='$codDosificacion'";
		$respDatosDosif=mysqli_query($enlaceCon,$sqlDatosDosif);
		$nroAutorizacion=mysqli_result($respDatosDosif,0,0);
		$llaveDosificacion=mysqli_result($respDatosDosif,0,1);
		
		$controlCode = new ControlCode();
		$code = $controlCode->generate($nroAutorizacion,//Numero de autorizacion
								   $nroFactura,//Numero de factura
								   $nitNuevo,//Número de Identificación Tributaria o Carnet de Identidad
								   str_replace('-','',$fecha),//fecha de transaccion de la forma AAAAMMDD
								   $totalFinalRedondeado,//Monto de la transacción
								   $llaveDosificacion//Llave de dosificación
								   );

		$sqlUpd="update salida_almacenes set nit='$nitNuevo', razon_social='$razonSocialNuevo' where cod_salida_almacenes='$codSalida';";
		echo $sqlUpd."<br>";
		//$respUpd=mysqli_query($enlaceCon,$sqlUpd);

		$sqlUpd1="update facturas_venta set nit='$nitNuevo', razon_social='$razonSocialNuevo', codigo_control='$code' where cod_venta='$codSalida';";
		echo $sqlUpd1."<br>";
		//$respUpd=mysqli_query($enlaceCon,$sqlUpd1);
		

		$txtDetalle=$txtDetalle." ".$codSalida." NroFac:".$nroFactura." ".$nombreAlmacen." ".$totalFinalRedondeado." ".$fecha." ".$sumaMontos." ".$code."<br>";

	}
}

echo $txtDetalle;


?>




