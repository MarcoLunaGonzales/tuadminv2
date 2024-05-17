<?php
require("conexion.inc");
require("funcionRecalculoCostos.php");

function descontar_inventarios($enlaceCon, $cod_salida, $cod_almacen, $cod_material, $cantidad, $precio, $descuento, $montoparcial, $orden){
	
	//echo $cod_salida." ".$cod_almacen." ".$cod_material." ".$cantidad;
	$cantidadPivote=$cantidad;
	
	$banderaError=1;
	
	$sqlExistencias="select id.cod_material, id.cantidad_restante, id.lote, id.fecha_vencimiento, id.cod_ingreso_almacen 
		from ingreso_almacenes i, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$cod_almacen' and i.ingreso_anulado=0 
		and id.cod_material='$cod_material' and id.cantidad_restante>0 order by id.lote, id.fecha_vencimiento asc";
	
	//echo $sqlExistencias;
	//AQUI SE DEBE CORREGIR EL DATO DE CANTIDAD RESTANTE >0 OJO
	
	//echo $sqlExistencias."<br>";
	$respExistencias=mysqli_query($enlaceCon,$sqlExistencias);
	while($datExistencias=mysqli_fetch_array($respExistencias)){
		if($cantidadPivote>0){
			$codMaterial=$datExistencias[0];
			$cantidadRestante=$datExistencias[1];
			$loteProducto=$datExistencias[2];
			$fechaVencProducto=$datExistencias[3];
			$codIngreso=$datExistencias[4];
			
			echo $codMaterial." ".$cantidadRestante." ".$loteProducto." ".$fechaVencProducto."<br>";
			
			if($cantidadPivote<=$cantidadRestante){
				$cantidadInsert=$cantidadPivote;
				$cantidadPivote=0;
			}else{
				$cantidadPivote=$cantidadPivote-$cantidadRestante;
				$cantidadInsert=$cantidadRestante;
			}
			$montoparcial=$cantidadInsert*$precio;
			
			$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
			descuento_unitario, monto_unitario, cod_ingreso_almacen, orden_detalle) values ('$cod_salida', '$codMaterial', '$cantidadInsert', '$loteProducto', '$fechaVencProducto',
			'$precio','$descuento','$montoparcial','$codIngreso','$orden')";
			$respInsert=mysqli_query($enlaceCon,$sqlInsert);
			
			//echo $sqlInsert;
			//AQUI DAMOS DE BAJA EL DESCUENTO POR SI HUBIERAN DOS REGISTROS O MAS
			$descuento=0;

			
			if($respInsert!=1){
				$banderaError=2;
			}
			
			$sqlUpd="update ingreso_detalle_almacenes set cantidad_restante=cantidad_restante-$cantidadInsert where 
			cod_ingreso_almacen='$codIngreso' and lote='$loteProducto' and cod_material='$codMaterial'";
			$respUpd=mysqli_query($enlaceCon,$sqlUpd);
			
			//echo $sqlUpd;

			if($respUpd!=1){
				$banderaError=3;
			}
		}
	}
	//recalculaCostos($codMaterial, $cod_almacen);
	return($banderaError);
}

function insertar_detalleSalidaVenta($enlaceCon,$cod_salida, $cod_almacen, $cod_material, $cantidad, $precio, $descuento, $montoparcial, $banderaStock, $orden){
	
	//la $banderaStock es 1 cuando se validan stocks y 0 cuando no se validan los stocks
	//echo $cod_salida." ".$cod_almacen." ".$cod_material." ".$cantidad;
	$cantidadPivote=$cantidad;
	
	$banderaError=1;
	
	$sqlInsert="insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, lote, fecha_vencimiento, precio_unitario,
	descuento_unitario, monto_unitario, orden_detalle) values ('$cod_salida', '$cod_material', '$cantidad', '0', '0000-00-00',
	'$precio','$descuento','$montoparcial','$orden')";
	
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	if($respInsert!=1){
		$banderaError=2;
	}
	return($banderaError);
}

/**
 * Proceso de registro de:
 * * SALIDA DE MATERIAL
 * * INGRESO DE MATERIAL
 */

 function registroMaterialTraspaso($enlaceCon, $almacenOrigen, $almacenDestino, $cod_material, $cantidad_unitaria){
	// * SALIDA
	$sql  	= "SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
	$resp 	= mysqli_query($enlaceCon,$sql);
	$cod_salida_almacenes = mysqli_result($resp,0,0);

	$tipoSalida = 1000; // TRASPASO
	$tipoDoc 	= 3; 	// NOTA DE SALIDA/TRASPASO
	$fecha 		= date('Y-m-d');
	$hora 		= date('H:i:s');
	$createdDate = date('Y-m-d H:i:s');
	$observaciones = 'SALIDA AUTOMÁTICA PARA VENTA';
	
	$vectorNroCorrelativo 	= numeroCorrelativo($tipoDoc);
	$nro_correlativo_salida	= $vectorNroCorrelativo[0];
	
	$totalVenta 	 = 0;
	$descuentoVenta  = 0;
	$totalFinal 	 = 0;
	$razonSocial 	 ='';
	$nitCliente 	 ='';
	$usuarioVendedor = $_COOKIE['global_usuario'];
	
	$vehiculo 			= 0;
	$cod_dosificacion 	= 0;
	$tipoPago 			= 0;
	$idTransaccion_siat = 0;
	$nroTarjeta 		= 0;
	$tipoVenta 			= 0;

	$orden = 1;

	$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
			`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
			`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
			`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion, cod_tipopago, idTransaccion_siat, nro_tarjeta, cod_tipoventa)
			values ('$cod_salida_almacenes', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
			'$observaciones', '1', '$nro_correlativo_salida', 0, '', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial','$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoPago','$idTransaccion_siat','$nroTarjeta','$tipoVenta')";
	//echo $sql_inserta;

	$sql_inserta=mysqli_query($enlaceCon, $sql_inserta);
	

	$codMaterial		= $cod_material;
	$cantidadUnitaria	= $cantidad_unitaria;
	$precioUnitario		= 0;
	$descuentoProducto	= 0;
	$montoMaterial		= 0;

	$respuesta = descontar_inventarios($enlaceCon, $cod_salida_almacenes, $almacenOrigen, $codMaterial, $cantidadUnitaria, $precioUnitario, $descuentoProducto, $montoMaterial, $orden);
	
	// * INGRESO
	$sql  = "SELECT IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
	$resp = mysqli_query($enlaceCon,$sql);
	$cod_ingreso_almacen = mysqli_result($resp,0,0);
	$sql = "SELECT IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$almacenDestino' order by cod_ingreso_almacen desc";
	$resp = mysqli_query($enlaceCon,$sql);
	$nro_correlativo_ingreso = mysqli_result($resp,0,0);
	
	$tipo_ingreso 	  = 1002;
	$observaciones 	  = 'INGRESO AUTOMÁTICO PARA VENTA';
	$codSalida 		  = $cod_salida_almacenes;
	$nota_entrega 	  = '';
	$nro_factura 	  = 0;
	$proveedor 		  = 0;
	$createdBy 		  = $_COOKIE['global_usuario'];
	$cod_tipopago 	  = 0;
	$dias_credito 	  = 0;
	$monto_ingreso 	  = 0;
	$monto_cancelado  = 0;
	$fecha_factura_proveedor = '';
	$estadoSalida	  = 4;//recepcionado
	$sqlCambiaEstado  = "UPDATE salida_almacenes set estado_salida='$estadoSalida' where cod_salida_almacenes = '$codSalida'";
	$respCambiaEstado = mysqli_query($enlaceCon,$sqlCambiaEstado);
	
	$consulta="INSERT INTO ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
	nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
	cod_proveedor,created_by,modified_by,created_date,modified_date,cod_tipopago,dias_credito,monto_ingreso,monto_cancelado,fecha_factura_proveedor) 
	values($cod_ingreso_almacen,$almacenDestino,$tipo_ingreso,'$fecha','$hora','$observaciones','$codSalida','$nota_entrega','$nro_correlativo_ingreso',0,0,0,'$nro_factura',0,0,'$proveedor','$createdBy','0','$createdDate','', '$cod_tipopago','$dias_credito','$monto_ingreso','$monto_cancelado','$fecha_factura_proveedor')";
	$sql_inserta = mysqli_query($enlaceCon,$consulta);
	
	$cantidad 	 	  = $cantidad_unitaria;
	$precioBruto 	  = 0;
	$lote 		 	  = '';
	$fechaVencimiento = '1900-01-01';
	$precioUnitario   = 0;
	$costo 			  = 0;
	
	$consulta = "INSERT INTO ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
	precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
	values('$cod_ingreso_almacen','$cod_material','$cantidad_unitaria','$cantidad','$lote','$fechaVencimiento','$precioUnitario','$precioUnitario','$costo','$costo','$costo','$costo')";
	$sql_inserta2 = mysqli_query($enlaceCon,$consulta);
	return true;
}

function insertar_detalleCotizacion($enlaceCon,$cod_cotizacion, $cod_material, $cantidad_unitaria, $precio_unitario, $descuento_unitario, $monto_unitario, $orden){
	$banderaError=1;	
	$sqlInsert="INSERT INTO cotizaciones_detalle (cod_cotizacion, cod_material, cantidad_unitaria, precio_unitario,
	descuento_unitario, monto_unitario, orden_detalle) VALUES ('$cod_cotizacion', '$cod_material', '$cantidad_unitaria',
	'$precio_unitario','$descuento_unitario','$monto_unitario','$orden')";	
	$respInsert=mysqli_query($enlaceCon,$sqlInsert);
	if($respInsert!=1){
		$banderaError=2;
	}
	return($banderaError);
}
?>