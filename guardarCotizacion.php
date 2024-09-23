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

$almacenOrigen = $_COOKIE['global_almacen'];

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



//$fecha=date("Y-m-d");
$created_at = date("Y-m-d H:i:s");

$sqlNroCorrelativo = "SELECT IFNULL(max(nro_correlativo)+1,1) as correlativo FROM cotizaciones c";
$respNroCorrelativo = mysqli_query($enlaceCon,$sqlNroCorrelativo);
$dataNroCorrelativo	= mysqli_fetch_array($respNroCorrelativo);
$nro_correlativo = empty($dataNroCorrelativo['correlativo'])?'':$dataNroCorrelativo['correlativo'];

$sql_inserta = "INSERT INTO cotizaciones(cod_almacen, cod_tiposalida, cod_tipo_doc, observaciones, nro_correlativo, cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, monto_cancelado, cod_tipopago, nro_tarjeta, cod_tipoventa, created_by, created_at)
		VALUES ('$almacenOrigen', '$tipoSalida', '$tipoDoc', '$observaciones', '$nro_correlativo', '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial','$nitCliente', '$usuarioVendedor', 0, '$tipoPago', '$nroTarjeta', '$tipoVenta', '$globalUsuario', '$created_at')";
$sql_inserta = mysqli_query($enlaceCon,$sql_inserta);
$last_id 	 = mysqli_insert_id($enlaceCon);

//echo $sql_inserta;

if($sql_inserta==1){
	$montoTotalVentaDetalle = 0;
	for($i = 1; $i <= $cantidad_material; $i++){   	
		$codMaterial = $_POST["materiales$i"];
		if($codMaterial!=0){
			$cantidadUnitaria	= empty($_POST["cantidad_unitaria$i"]) ? 0 : $_POST["cantidad_unitaria$i"];
			$precioUnitario		= empty($_POST["precio_unitario$i"]) ? 0 : $_POST["precio_unitario$i"];
			$descuentoProducto	= empty($_POST["descuentoProducto$i"]) ? 0 : $_POST["descuentoProducto$i"];
			$montoMaterial		= empty($_POST["montoMaterial$i"]) ? 0 : $_POST["montoMaterial$i"];
			// Sucursal Seleccionada
			$cod_almacen		= empty($_POST["cod_sucursales$i"]) ? '' : $_POST["cod_sucursales$i"];

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterialConDescuento = ($precioUnitario * $cantidadUnitaria) - $descuentoProducto;

			$montoTotalVentaDetalle = $montoTotalVentaDetalle + $montoMaterialConDescuento;

			$respuesta = insertar_detalleCotizacion($enlaceCon, $last_id, $codMaterial, $cantidadUnitaria, $precioUnitario, $descuentoProducto, $montoMaterialConDescuento, $i, $cod_almacen);

			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
	echo "<script type='text/javascript' language='javascript'>
		location.href='navegadorCotizaciones.php';
	</script>";
}else{
		echo "<script type='text/javascript' language='javascript'>
		alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
		location.href='navegadorVentas.php';
		</script>";
}

?>