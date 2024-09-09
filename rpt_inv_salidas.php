<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funciones.php');
require('conexion.inc');
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_tipo_salida="select nombre_tiposalida from tipos_salida where cod_tiposalida='$tipo_salida'";
$resp_tipo_salida=mysqli_query($enlaceCon,$sql_tipo_salida);
$datos_tipo_salida=mysqli_fetch_array($resp_tipo_salida);
$nombre_tiposalida=$datos_tipo_salida[0];

	if($tipo_salida!="")
	{	$nombre_tiposalidamostrar="Tipo de Salida: <strong>$nombre_tiposalida</strong>";
	}
	else
	{	$nombre_tiposalidamostrar="Todos los tipos de Salida";
	}
	if($tipo_reporte==0)
	{	$nombre_tiporeporte="Reporte x Nro. de Salida";
	}
	else
	{	$nombre_tiporeporte="Reporte x Producto";
	}
	echo "<h1>Reporte Salidas Almacen</h1>
	<h1>$nombre_tiporeporte<br>$nombre_tiposalidamostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> <br>$txt_reporte</th></tr></table>";
if($tipo_reporte==0)
{

	//desde esta parte viene el reporte en si
	// $fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	// $fecha_finconsulta=cambia_formatofecha($fecha_fin);
	$fecha_iniconsulta=$fecha_ini;
	$fecha_finconsulta=$fecha_fin;


	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, 
	(select c.descripcion from ciudades c where c.cod_ciudad=s.territorio_destino)territorio_destino, 
	a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada,
	(select es.nombre_estado from estados_salida es where es.cod_estado=s.estado_salida)as estado
	FROM salida_almacenes s, tipos_salida ts, almacenes a
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' 
	and a.cod_almacen=s.cod_almacen and 
	s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' 
	and s.cod_tiposalida='$tipo_salida' and s.salida_anulada=0 order by s.nro_correlativo";
	if($tipo_salida=="")
	{	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, 
		(select c.descripcion from ciudades c where c.cod_ciudad=s.territorio_destino)territorio_destino, 
		a.nombre_almacen, 
		s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada,
		(select es.nombre_estado from estados_salida es where es.cod_estado=s.estado_salida)as estado
		FROM salida_almacenes s, tipos_salida ts, almacenes a
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' 
		and a.cod_almacen=s.cod_almacen and 
		s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' and s.salida_anulada=0 
		order by s.nro_correlativo";
	}
//	echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<center><br><table class='texto'>";
	echo "<tr><th>Nro.</th><th>Fecha</th><th>Tipo de Salida</th><th>Almacen Destino</th><th>Observaciones</th><th>Estado</th><th>Detalle</th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$nombre_tiposalida=$dat[2];
		$nombre_ciudad=$dat[3];
		$nombre_almacen=$dat[4];
		$obs_salida=$dat[5];
		$estado_almacen=$dat[6];
		$nro_correlativo=$dat[7];
		$salida_anulada=$dat[8];
		$nombreEstadoSalida=$dat[9];
		echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";

		//aqui sacamos el detalle
		$detalle_salida="";
		$detalle_salida.="<table border='0' class='textomini' cellspacing='0' width='100%' align='center'>";
		$detalle_salida.="<tr><th>&nbsp;</th><th width='80%'>Material</th><th width='20%'>Cantidad</th></tr>";
		$sql_detalle="select s.cod_material, s.cantidad_unitaria from salida_detalle_almacenes s
		where s.cod_salida_almacen='$codigo'";
		$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
		$indice=1;
		$bandera=0;
		while($dat_detalle=mysqli_fetch_array($resp_detalle))
		{	$cod_material=$dat_detalle[0];
			$cantidad_unitaria=$dat_detalle[1];
			$cantidad_unitaria=redondear2($cantidad_unitaria);
			if($tipo_item==1)
			{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
			}
			else
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			}
			$resp_nombre_material=mysqli_query($enlaceCon,$sql_nombre_material);
			$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
			$nombre_material=$dat_nombre_material[0];
			$presentacion_material=$dat_nombre_material[1];
			if($rpt_linea!=0 and $num_filas!=0)
			{	$detalle_salida.="<tr><td align='center'>$indice</td><td width='80%'>$nombre_material $presentacion_material</td><td width='20%' align='center'>$cantidad_unitaria</td></tr>";
			}
			if($rpt_linea==0)
			{	$detalle_salida.="<tr><td align='center'>$indice</td><td width='80%'>$nombre_material $presentacion_material</td><td width='20%' align='center'>$cantidad_unitaria</td></tr>";
			}
			$indice++;
		}
		$detalle_salida.="</table>";
		//fin detalle
		if($rpt_linea==0)
		{	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$obs_salida</td><td>&nbsp;$nombreEstadoSalida</td><td>&nbsp;$detalle_salida</td></tr>";
		}
		if($rpt_linea!=0 and $bandera==1)
		{	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$obs_salida</td><td>&nbsp;$nombreEstadoSalida</td><td>&nbsp;$detalle_salida</td></tr>";
		}

	}
	echo "</table></center><br>";
}

/*if($tipo_reporte==1)
{	
	$sql_producto="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";

	$resp_producto=mysqli_query($enlaceCon,$sql_producto);
	echo "<table class='texto' width='70%' align='center'>";

	echo "<tr><th>Material</th><th>Salidas</th></tr>";
	
	while($dat_producto=mysqli_fetch_array($resp_producto))
	{	$codigo_producto=$dat_producto[0];
		$nombre_producto="$dat_producto[1] $dat_producto[2]";
		$sql="select * from ciudades order by descripcion";
		$resp=mysqli_query($enlaceCon,$sql);
		$cadena_ciudades="<table cellspacing='0' class='textomini' width='100%'>
		<tr><th>Almacen Destino</th><th>Cantidad</th></tr>";
		$bandera_producto=0;
		$suma_salida_producto=0;
		while($dat=mysqli_fetch_array($resp))
		{	$cod_ciudad=$dat[0];
			$nombre_ciudad=$dat[1];
			$sql_almacen="select cod_almacen from almacenes where cod_ciudad='$cod_ciudad'";
			$resp_almacen=mysqli_query($enlaceCon,$sql_almacen);
			$dat_almacen=mysqli_fetch_array($resp_almacen);
			$cod_almacen=$dat_almacen[0];

			$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
			$fecha_finconsulta=cambia_formatofecha($fecha_fin);

			$sql_salidas="select sum(sd.cantidad_unitaria), s.cod_salida_almacenes, sd.lote
				from salida_almacenes s, salida_detalle_almacenes sd 
				where sd.cod_salida_almacen=s.cod_salida_almacenes
				and sd.cod_material='$codigo_producto' and  
				s.salida_anulada<>1 and s.cod_almacen='$global_almacen'
				and s.almacen_destino='$cod_almacen' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta'
				group by (sd.lote)";
			//echo $sql_salidas;
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$cantidad_salida=0;
			$cadena_lotes="<table border='1' cellspacing='0' width='100%' class='textomini'><tr><th>Cantidad</th></tr>";
			while($dat_salidas=mysqli_fetch_array($resp_salidas))
			{	$cant_salida=$dat_salidas[0];
				$cod_salida_almacen=$dat_salidas[1];
				$nro_lote=$dat_salidas[2];
				$cantidad_salida=$cant_salida+$cantidad_salida;
				$cadena_lotes.="<tr><td align='right'>$cant_salida</td></tr>";
				
			}
			$cadena_lotes.="</table>";
			if($cantidad_salida>0)
			{	$bandera_producto=1;
//				echo $sql_salidas;
				$suma_salida_producto=$suma_salida_producto+$cantidad_salida;
				$cadena_ciudades.="<tr><td>$nombre_ciudad</td><td align='right'>$cantidad_salida</td></tr>";
			}
		}
		$cadena_ciudades.="<tr><th>Total:</th><th align='right'>$suma_salida_producto</th></tr>";
		$cadena_ciudades.="</table>";
		if($bandera_producto==1)
		{	echo "<tr><td>$nombre_producto</td><td>$cadena_ciudades</td></tr>";
		}
	}
	echo "</table>";
}*/
?>