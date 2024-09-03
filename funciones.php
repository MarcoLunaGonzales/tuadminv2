<?php
date_default_timezone_set('America/La_Paz');
require_once 'conexionmysqlipdf.inc';

function obtenerValorConfiguracion($id){
	$estilosVenta=1;
	require("conexionmysqlipdf.inc");
	$sql = "SELECT valor_configuracion from configuraciones c where id_configuracion=$id";
	$resp=mysqli_query($enlaceCon,$sql);
	$codigo=0;
	while ($dat = mysqli_fetch_array($resp)) {
	  $codigo=$dat['valor_configuracion'];
	}
	return($codigo);
}
function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formatNumberInt($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumero($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumeroDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function UltimoDiaMes($cadena_fecha)
{	
	list($anioX, $mesX, $diaX)=explode("-",$cadena_fecha);
	$fechaNuevaX=$anioX."-".$mesX."-01";
	
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'+1 month'));
	$fechaNuevaX=date('Y-m-d',strtotime($fechaNuevaX.'-1 day'));

	return($fechaNuevaX);
}

function obtenerCodigo($sql)
{	require("conexionmysqlipdf.inc");
	$resp=mysqli_query($enlaceCon,$sql);
	$nro_filas_sql = mysqli_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysqli_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}

function stockProducto($almacen, $item){
	//
	require("conexionmysqlipdf.inc");
	$fechaActual=date("Y-m-d");

	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0";
			$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
			$dat_ingresos=mysqli_fetch_array($resp_ingresos);
			$cant_ingresos=$dat_ingresos[0];
			$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$fechaActual' and s.cod_almacen='$almacen'
			and sd.cod_material='$item' and s.salida_anulada=0";
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cant_salidas=$dat_salidas[0];
			$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}

function stockProductoAFecha($almacen, $item, $fechaActual){
	//
	require("conexionmysqlipdf.inc");
	//$fechaActual=date("Y-m-d");

	$sql_ingresos="select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$fechaActual' and i.cod_almacen='$almacen'
			and id.cod_material='$item' and i.ingreso_anulado=0";
	//echo $sql_ingresos."<br>";
	$resp_ingresos=mysqli_query($enlaceCon,$sql_ingresos);
	$dat_ingresos=mysqli_fetch_array($resp_ingresos);
	$cant_ingresos=$dat_ingresos[0];
	$sql_salidas="select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$fechaActual' and s.cod_almacen='$almacen'
	and sd.cod_material='$item' and s.salida_anulada=0";
	//echo $sql_salidas;
	$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
	$dat_salidas=mysqli_fetch_array($resp_salidas);
	$cant_salidas=$dat_salidas[0];
	$stock2=$cant_ingresos-$cant_salidas;
	return($stock2);
}

function stockMaterialesEdit($almacen, $item, $cantidad){
	//
	require("conexionmysqlipdf.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($codigo_registro){
	require("conexionmysqlipdf.inc");
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysqli_query($enlaceCon,$sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysqli_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysqli_query($enlaceCon,$sql_actualiza);			
	}
	return(1);
}
function numeroCorrelativo($tipoDoc){
	require("conexionmysqlipdf.inc");
	$banderaErrorFacturacion=0;
	//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
	$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
	$respConf=mysqli_query($enlaceCon,$sqlConf);
	$facturacionActivada=mysqli_result($respConf,0,0);

	$fechaActual=date("Y-m-d");
	$globalAgencia=$_COOKIE['global_agencia'];
	$globalAlmacen=$_COOKIE['global_almacen'];
	
	if($facturacionActivada==1 && $tipoDoc==1){
		//VALIDAMOS QUE LA DOSIFICACION ESTE ACTIVA
		$sqlValidar="select count(*) from dosificaciones d 
		where d.cod_sucursal='$globalAgencia' and d.cod_estado=1 and d.fecha_limite_emision>='$fechaActual'";
		$respValidar=mysqli_query($enlaceCon,$sqlValidar);
		$numFilasValidar=mysqli_result($respValidar,0,0);
		
		if($numFilasValidar==1){
			$sqlCodDosi="select cod_dosificacion from dosificaciones d 
			where d.cod_sucursal='$globalAgencia' and d.cod_estado=1";
			$respCodDosi=mysqli_query($enlaceCon,$sqlCodDosi);
			$codigoDosificacion=mysqli_result($respCodDosi,0,0);
		
			if($tipoDoc==1){//validamos la factura para que trabaje con la dosificacion
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' 
				and cod_dosificacion='$codigoDosificacion' and cod_almacen='$globalAlmacen'";	
			}else{
				$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
			}
			//echo $sql;
			$resp=mysqli_query($enlaceCon,$sql);
			$codigo=mysqli_result($resp,0,0);
			
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,$codigoDosificacion);
			return $vectorCodigo;
		}else{
			$banderaErrorFacturacion=1;
			$vectorCodigo = array("DOSIFICACION INCORRECTA O VENCIDA",$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
	if( ( $facturacionActivada==1 && ($tipoDoc==2 || $tipoDoc==3) ) || $facturacionActivada!=1){
		$sql="select IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='$tipoDoc' and cod_almacen='$globalAlmacen'";
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
			return $vectorCodigo;
		}
	}
}
function numeroCorrelativoTraspaso($globalAlmacen){
	require("conexionmysqlipdf.inc");

	$sql="SELECT IFNULL(max(nro_correlativo)+1,1) from salida_almacenes where cod_tipo_doc='3' and cod_almacen='$globalAlmacen'";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$codigo=$dat[0];
		$vectorCodigo = array($codigo,$banderaErrorFacturacion,0);
		return $vectorCodigo;
	}
}

function unidadMedida($codigo){
	require("conexionmysqlipdf.inc");	
	$consulta="select u.abreviatura from material_apoyo m, unidades_medida u
		where m.cod_unidad=u.codigo and m.codigo_material='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$unidadMedida=$registro[0];

	return $unidadMedida;
}


function nombreTipoDoc($codigo){
	require "conexionmysqlipdf.inc";
	$consulta="select u.abreviatura from tipos_docs u where u.codigo='$codigo'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$nombre=$registro[0];

	return $nombre;
}

function precioVenta($codigo,$agencia){
	require("conexionmysqlipdf.inc");
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and p.cod_ciudad='$agencia'";
	//echo $consulta;
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}
function precioVentaN($enlaceCon,$codigo,$agencia){
	$agenciaDefecto=1;
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and p.cod_ciudad='$agenciaDefecto'";
	//echo $consulta;
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}

function precioVentaMayorContado($enlaceCon,$codigo,$agencia){
	$agenciaDefecto=1;
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='2' and p.cod_ciudad='$agenciaDefecto'";
	//echo $consulta;
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}

function precioVentaMayorCredito($enlaceCon,$codigo,$agencia){
	$agenciaDefecto=1;
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='3' and p.cod_ciudad='$agenciaDefecto'";
	//echo $consulta;
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}

function precioVentaCosto($codigo,$agencia){
	require("conexionmysqlipdf.inc");
	$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' and p.cod_ciudad='$agencia'";
	//echo $consulta;
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$precioVenta=$registro[0];
	if($precioVenta=="")
	{   $precioVenta=0;
	}

	$precioVenta=redondear2($precioVenta);
	return $precioVenta;
}
//COSTO 
function costoVentaFalse($codigo,$agencia){	
	require("conexionmysqlipdf.inc");
	$consulta="select sd.costo_almacen from salida_almacenes s, salida_detalle_almacenes sd where 
		s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen in  
		(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and s.salida_anulada=0 and 
		sd.cod_material='$codigo' limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}

function costoVenta($codigo,$agencia){	
	require("conexionmysqlipdf.inc");

	$consulta="select id.costo_almacen from ingreso_almacenes i, ingreso_detalle_almacenes id where 
	i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen in  
			(select a.cod_almacen from almacenes a where a.cod_ciudad='$agencia') and i.ingreso_anulado=0 
	and id.cod_material='$codigo' order by i.cod_ingreso_almacen desc limit 0,1";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$costoVenta=$registro[0];
	if($costoVenta=="")
	{   $costoVenta=0;
	}

	$costoVenta=redondear2($costoVenta);
	return $costoVenta;
}

function obtenerSaldoKardexAnterior($rpt_almacen, $rpt_item, $fecha){
	require("conexionmysqlipdf.inc");
	//desde esta parte viene el reporte en si
	$costoInicialKardex=0;
	$fecha_iniconsulta=$fecha;
	//aqui sacamos las existencias a una fecha
	$sql="select sum(id.cantidad_unitaria), sum(id.cantidad_unitaria*id.costo_almacen)as costo FROM ingreso_almacenes i, ingreso_detalle_almacenes id
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and
	i.ingreso_anulado=0 and id.cod_material='$rpt_item' and i.fecha<'$fecha_iniconsulta'";
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	$dat_existencias_afecha=mysqli_fetch_array($resp);
	$cantidad_ingresada_afecha=$dat_existencias_afecha[0];
	$costoIngresosAnterior=$dat_existencias_afecha[1];
	
	$sql_salidas_afecha="select sum(sd.cantidad_unitaria), sum(sd.cantidad_unitaria*sd.costo_almacen)as costo from salida_almacenes s, salida_detalle_almacenes sd
	where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and
	s.salida_anulada=0 and sd.cod_material='$rpt_item' and s.fecha<'$fecha_iniconsulta'";
	//echo $sql_salidas_afecha;
	$resp_salidas_afecha=mysqli_query($enlaceCon,$sql_salidas_afecha);
	$dat_salidas_afecha=mysqli_fetch_array($resp_salidas_afecha);
	$cantidad_sacada_afecha=$dat_salidas_afecha[0];
	$costoSalidasAnterior=$dat_salidas_afecha[1];
	$cantidad_inicial_kardex=$cantidad_ingresada_afecha-$cantidad_sacada_afecha;
	$costoInicialKardex=$costoIngresosAnterior-$costoSalidasAnterior;

	$vectorKardexAnterior=array($cantidad_inicial_kardex,$costoInicialKardex);
	return($vectorKardexAnterior);
}

function obtenerCodigoSucursal($cod_almacen){
	require("conexionmysqlipdf.inc");
	$cod_respuesta=0;
	$consulta="select cod_ciudad from almacenes where cod_almacen='$cod_almacen'";
	$rs=mysqli_query($enlaceCon,$consulta);
	$registro=mysqli_fetch_array($rs);
	$cod_respuesta=$registro[0];
	if($cod_respuesta=="")
	{   $cod_respuesta=0;
	}
	return $cod_respuesta;
}
function montoVentaDocumento($codVenta){
	require("conexionmysqlipdf.inc");
	$sql="select (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.cod_salida_almacenes=$codVenta";
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	$totalVenta=0;
	while($datos=mysqli_fetch_array($resp)){	
		
		$montoVenta=$datos[0];
		$cantidad=$datos[1];

		$descuentoVenta=$datos[2];
		$montoNota=$datos[3];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		$totalVenta=$totalVenta+$montoVenta;
	}
	return($totalVenta);	
}

/**
 * Valor de Configuración
 */
function valorConfig($id_configuracion){
	require("conexionmysqlipdf.inc");
	$sqlConf = "SELECT valor_configuracion FROM configuraciones where id_configuracion='$id_configuracion'";
	$respConf = mysqli_query($enlaceCon,$sqlConf);
	$response = mysqli_result($respConf,0,0);
	return $response;
}


//funcion nueva obtener tipo cambio monedas
function obtenerValorTipoCambio($codigo,$fecha){
	require("conexionmysqlipdf.inc");
	$valor=0;
	$sql="SELECT valor from tipo_cambiomonedas where cod_moneda=$codigo and fecha = '$fecha'";
	$resp = mysqli_query($enlaceCon, $sql);
	if ($data = mysqli_fetch_array($resp)) {
		$valor = $data['valor'];
	} 
	return $valor;
}
 
//funcion nueva obtener moneda
function obtenerMoneda($codigo){
	require("conexionmysqlipdf.inc");
	$sql="";
	$sql="SELECT m.codigo,m.nombre,m.estado,m.abreviatura from monedas m where m.estado=1 and m.codigo='$codigo'";
	return mysqli_query($enlaceCon, $sql);
}
 
//funcion nueva obtener tipo cambio monedas
function obtenerTipoCambio($codigo,$fi,$fa){
	require("conexionmysqlipdf.inc");
	$sql="";
	$sql="SELECT id,cod_moneda,fecha,valor from tipo_cambiomonedas where cod_moneda=$codigo and fecha between '$fi' and '$fa' order by fecha";
	return mysqli_query($enlaceCon, $sql);
}

/**
 * Verifica si existe valor de Conversión de la moneda configruada
 * ? De acuerdo a la fecha actual
 * ! Es importante tomar en cuenta la Moneda de cambio ACTUAL
 */
function obtieneValorConversionActual($cod_moneda_actual){
	require("conexionmysqlipdf.inc");
	setlocale(LC_TIME, "Spanish");

	$fecha_actual = date("Y-m-d");

	$sql="SELECT valor 
		FROM tipo_cambiomonedas 
		WHERE cod_moneda = '$cod_moneda_actual' 
		AND fecha = '$fecha_actual'";
	$resp = mysqli_query($enlaceCon, $sql);

	$fila  = 0;
	$valor = '';
	if ($data = mysqli_fetch_array($resp)) {
		$fila  = 1;
		$valor = $data['valor'];
	} 
	return [$fila, $valor];
}
