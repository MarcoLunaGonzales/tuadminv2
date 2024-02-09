<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");
ob_clean();

error_reporting(E_ALL);
ini_set('display_errors', '1');

$usuarioVendedor = empty($_POST['cod_vendedor']) ? '' : $_POST['cod_vendedor'];
$globalUsuario 	 = $_COOKIE['global_usuario'];

$usuarioVendedor = empty($usuarioVendedor) ? $globalUsuario : $usuarioVendedor;

$globalSucursal=$_COOKIE['global_agencia'];
$globalEmpresa=$_COOKIE['global_cod_empresa'];

$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=$_POST['tipoDoc'];
$almacenDestino=empty($_POST['almacen'])?'':$_POST['almacen'];
$codCliente=empty($_POST['cliente'])?'':$_POST['cliente'];

$tipoPrecio=empty($_POST['tipoPrecio'])?'':$_POST['tipoPrecio'];
$razonSocial=strtoupper((empty($_POST['razonSocial'])?'':$_POST['razonSocial']));
$nitCliente=empty($_POST['nitCliente'])?'':$_POST['nitCliente'];
$complemento=empty($_POST["complemento"])?'':$_POST["complemento"];
$tipoDocumento=empty($_POST["tipo_documento"])?'':$_POST["tipo_documento"];

$observaciones=empty($_POST["observaciones"])?'':$_POST["observaciones"];

$tipoVenta = empty($_POST["tipo"])?'':$_POST["tipo"]; 			// Tipo de Venta
$tipoPago  = empty($_POST["tipoVenta"])?'':$_POST["tipoVenta"]; // Tipo de Pago

$almacenOrigen=$global_almacen;

$totalVenta=empty($_POST["totalVenta"])?'':$_POST["totalVenta"];
$descuentoVenta=empty($_POST["descuentoVenta"])?'':$_POST["descuentoVenta"];
$totalFinal=empty($_POST["totalFinal"])?'':$_POST["totalFinal"];

$totalFinalRedondeado=round($totalFinal);

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];

/*ENVIAMOS 0 EN NRO CORRELATIVO PORQUE LO RECIBIMOS DESDE EL SIAT*/
$nroCorrelativo=0;

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$vehiculo="";
//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$tipoDocDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$clienteDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$facturacionActivada=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA LA VALIDACION DE STOCKS
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];

$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysqli_query($enlaceCon,$sql);
$codigo=mysqli_result($resp,0,0);


/*VALIDACION MANUAL CASOS ESPECIALES*/
if((int)$nitCliente=='99001' || (int)$nitCliente=='99002' || (int)$nitCliente=='99003'){
	$tipoDocumento=5;//nit
}
// Cliente
$sqlCliente="SELECT CONCAT(c.nombre_cliente, ' ', c.paterno) as cliente, c.email_cliente, c.cod_cliente
			FROM clientes c
			WHERE c.cod_cliente = '$codCliente'
			LIMIT 1";
$respCliente = mysqli_query($enlaceCon,$sqlCliente);
$datCliente	 = mysqli_fetch_array($respCliente);
$nombreCliente = empty($datCliente['cliente'])?'':$datCliente['cliente'];
$emailCliente  = empty($datCliente['email_cliente'])?'':$datCliente['email_cliente'];
// Vendedor (Usuario)
$sqlConf="SELECT CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as usuario
			FROM funcionarios f
			WHERE f.codigo_funcionario = '$usuarioVendedor'
			LIMIT 1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf	 = mysqli_fetch_array($respConf);
$nombreVendedor = $datConf['usuario'];

/**************************
 * Verificar datos Cliente
 **************************/
$cod_cliente = empty($_POST['cliente'])?'':$_POST['cliente'];
$sql = "SELECT CONCAT(c.nombre_cliente, ' ',c.paterno) as cliente, c.email_cliente
		FROM clientes c
		WHERE c.cod_cliente = '$cod_cliente'
		LIMIT 1";
$resp 	= mysqli_query($enlaceCon,$sql);
$data	= mysqli_fetch_array($resp);
$nombre_cliente = empty($data['cliente'])?'':$data['cliente'];
$email_cliente  = empty($data['email_cliente'])?'':$data['email_cliente'];


$idTransaccion_siat = 0;
$nro_factura_siat   = 0;

$input_nro_tarjeta  = empty($_POST['nroTarjeta_form']) ? '' : $_POST['nroTarjeta_form'];
$nroTarjeta 		= str_replace('*', '0', $input_nro_tarjeta);
//====================================================//
$url_config = valorConfig(7);


// Tipo de emisión de factura = 2
if($tipoDoc == 1){ // Tipo de Emisión Factura
	/**
	 * PREPARACIÓN DE DATOS SIAT
	 */
	$array_siat = array(
		"sIdentificador" 	=> "MinkaSw123*",
		"sKey" 				=> "rrf656nb2396k6g6x44434h56jzx5g6",
		"accion" 			=> "generarFacturaElectronica",
		"idEmpresa" 		=> $globalEmpresa,
		"sucursal" 			=> $globalSucursal,
		"idRecibo"			=> $nroCorrelativo,
		"fecha" 			=> $fecha,
		"idPersona" 		=> $codCliente,
		"monto_total" 		=> $totalVenta,
		"descuento" 		=> $descuentoVenta,
		"monto_final" 		=> $totalFinalRedondeado,
		"id_usuario" 		=> $usuarioVendedor,
		"usuario" 			=> $nombreVendedor,
		"nitCliente" 		=> $nitCliente,
		"nombreFactura" 	=> $razonSocial,
		"tipoPago" 			=> $tipoPago,
		"nroTarjeta" 		=> $nroTarjeta,
		"tipoDocumento" 	   => $tipoDocumento,
		"complementoDocumento" => $complemento,
		"correo" 			   => $email_cliente
	);
	// Detalle de ITEMS
	$detalleItems = [];
	for ($i = 1; $i <= $cantidad_material; $i++) {
		$codMaterial = $_POST["materiales$i"];
		if ($codMaterial != 0) {
			$cantidadUnitaria 	= $_POST["cantidad_unitaria$i"];
			$precioUnitario 	= $_POST["precio_unitario$i"];
			$descuentoProducto 	= $_POST["descuentoProducto$i"];
			$montoMaterial 		= $_POST["montoMaterial$i"];
			// Buscar Item
			$sqlConf="SELECT ma.codigo_material, ma.descripcion_material
					FROM material_apoyo ma
					WHERE ma.codigo_material = '$codMaterial'
					LIMIT 1";
			$respConf 	= mysqli_query($enlaceCon,$sqlConf);
			$datConf	= mysqli_fetch_array($respConf);
			$nombreItem = $datConf['descripcion_material'];
			// Crear objeto de detalle
			$detalle = array(
				"codDetalle" 		=> $codMaterial,
				"cantidad" 			=> $cantidadUnitaria,
				"precioUnitario" 	=> $precioUnitario,
				"descuentoProducto" => $descuentoProducto,
				"detalle" 			=> $nombreItem
			);

			// Agregar detalle al array de items
			$detalleItems[] = $detalle;
		}
	}
	// Establecer la cabecera de respuesta para indicar que es JSON
	// header('Content-Type: application/json');
	$array_siat['items'] = $detalleItems;
	//====================================================//
	// var_dump($array_siat);
	// exit;

	// URL del servidor MINKA_SIAT
	$url_siat = $url_config.'wsminka/ws_generarFactura.php';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_siat);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array_siat));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec ($ch);
	// Verificar si hubo algún error en la solicitud
	if (curl_errno($ch)) {
		curl_close($ch);
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		location.href='navegador_salidamateriales.php';
		</script>";
		exit;
	}
	curl_close($ch);
	// Encontrar el índice de inicio y fin del JSON
	$inicioJSON = strpos($response, '{');
	$finJSON = strrpos($response, '}');
	// Extraer el JSON y decodificarlo
	$jsonData = substr($response, $inicioJSON, $finJSON - $inicioJSON + 1);
	$decodedData = json_decode($jsonData, true);

	// Verificamos respuesta
	if($decodedData['estado'] != 1){
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Error: ".$decodedData['mensaje']."');
		location.href='navegador_salidamateriales.php';
		</script>";
		exit;
	}

	$idTransaccion_siat = $decodedData['idTransaccion'];
	$nro_factura_siat   = $decodedData['nroFactura'];

	/* actualizamos el campo que sirve para la facturacion */
	$nro_correlativo=$nro_factura_siat;
	
	//print_r($decodedData); // Visualización de datos SIAT
	//exit;
}

//$fecha=date("Y-m-d");
$hora=date("H:i:s");

$cod_dosificacion=0;
if($tipoDoc <> 1){
	$vectorNroCorrelativo=numeroCorrelativo($tipoDoc);
	$nro_correlativo=$vectorNroCorrelativo[0];
	$cod_dosificacion=$vectorNroCorrelativo[2];
}
$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion, cod_tipopago, idTransaccion_siat, nro_tarjeta, cod_tipoventa)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial','$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoPago','$idTransaccion_siat','$nroTarjeta','$tipoVenta')";

//echo $sql_inserta;

$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);

if($sql_inserta==1){
	
	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0){
			$cantidadUnitaria	= empty($_POST["cantidad_unitaria$i"]) ? 0 : $_POST["cantidad_unitaria$i"];
			$precioUnitario		= empty($_POST["precio_unitario$i"]) ? 0 : $_POST["precio_unitario$i"];
			$descuentoProducto	= empty($_POST["descuentoProducto$i"]) ? 0 : $_POST["descuentoProducto$i"];
			$montoMaterial		= empty($_POST["montoMaterial$i"]) ? 0 : $_POST["montoMaterial$i"];

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;

			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			if($banderaValidacionStock==1){
				$respuesta=descontar_inventarios($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
			}

			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
	$montoTotalConDescuento=$montoTotalVentaDetalle-$descuentoVenta;
	//ACTUALIZAMOS EL PRECIO CON EL DETALLE
	$sqlUpdMonto="update salida_almacenes set monto_total='$montoTotalVentaDetalle', monto_final='$montoTotalConDescuento' 
				where cod_salida_almacenes='$codigo'";
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	if($facturacionActivada==1){
		$sqlUpdMonto="update facturas_venta set importe='$montoTotalConDescuento' 
					where cod_venta='$codigo'";
		$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	}
	if($tipoSalida==1001){
		/*if($tipoDoc==1){
			echo "<script type='text/javascript' language='javascript'>	
			location.href='formatoFactura.php?codVenta=$codigo';
			</script>";	
			//window.open('formatoFactura.php?codVenta=$codigo','','scrollbars=yes,width=1000,height=800');	
		}
		if($tipoDoc==2){
			echo "<script type='text/javascript' language='javascript'>
			location.href='formatoNotaRemision.php?codVenta=$codigo';
			</script>";	
		}*/		
		echo "<script type='text/javascript' language='javascript'>
			location.href='navegadorVentas.php';
			</script>";
	}else{
		/*echo "<script type='text/javascript' language='javascript'>
		location.href='navegador_detallesalidamateriales.php?codigo_salida=$codigo';
		</script>";*/
		echo "<script type='text/javascript' language='javascript'>
			location.href='navegador_salidamateriales.php';
			</script>";
	}

	
}else{
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		location.href='navegador_salidamateriales.php';
		</script>";
}

?>



