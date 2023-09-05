<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funciones.php');
require('conexion.inc');

$fecha_ini=$_POST["exafinicial"];
$fecha_fin=$_POST["exaffinal"];
$global_almacen=$_POST['rpt_almacen'];
$tipo_salida_x=$_POST["tipo_salida"];

$tipo_salida=implode(",",$tipo_salida_x);

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_tipo_salida="select nombre_tiposalida from tipos_salida where cod_tiposalida='$tipo_salida'";
$resp_tipo_salida=mysql_query($sql_tipo_salida);
$datos_tipo_salida=mysql_fetch_array($resp_tipo_salida);
$nombre_tiposalida=$datos_tipo_salida[0];

	if($tipo_salida!="")
	{	$nombre_tiposalidamostrar="Tipo de Salida: <strong>$nombre_tiposalida</strong>";
	}
	else
	{	$nombre_tiposalidamostrar="Todos los tipos de Salida";
	}
	
	$nombre_tiporeporte="Reporte x Nro. de Salida";

	echo "<h1>Reporte Salidas Almacen</h1>
	<h1>$nombre_tiporeporte<br>$nombre_tiposalidamostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> <br>$txt_reporte</th></tr></table>";


	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=$fecha_ini;
	$fecha_finconsulta=$fecha_fin;

	$sql="SELECT s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, 
	(select c.descripcion from ciudades c where c.cod_ciudad=s.territorio_destino)territorio_destino, 
	a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, ma.codigo_material, ma.descripcion_material, sd.cantidad_unitaria
	FROM salida_almacenes s, tipos_salida ts, almacenes a, salida_detalle_almacenes sd, material_apoyo ma
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_salida_almacenes=sd.cod_salida_almacen and sd.cod_material=ma.codigo_material and s.cod_almacen='$global_almacen' 
	and a.cod_almacen=s.cod_almacen and 
	s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' and s.salida_anulada=0
	and s.cod_tiposalida in ($tipo_salida) order by s.nro_correlativo";

	//echo $sql;

	$resp=mysql_query($sql);
	echo "<center><br><table class='texto'>";
	echo "<tr><th>Nro.</th><th>Fecha</th><th>Tipo de Salida</th><th>Territorio<br>Destino</th><th>Almacen Destino</th><th>Cliente</th><th>Observaciones</th><th>Estado</th><th>Cod.Producto</th><th>Producto</th><th>Cantidad</th></tr>";
	while($dat=mysql_fetch_array($resp))
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

		$codigoProductoX=$dat[9];
		$nombreProductoX=$dat[10];
		$cantidadUnitariaSalida=$dat[11];

		$cantidadUnitariaSalidaF=formatonumeroDec($cantidadUnitariaSalida);

		echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
		if($estado_almacen==0)
		{	$estado_salida='';
		}
		//salida despachada
		if($estado_almacen==1)
		{	$estado_salida='Despachada';
		}
		//salida recepcionada
		if($estado_almacen==2)
		{	$estado_salida='Recepcionada';
		}
		if($salida_anulada==1)
		{	$estado_salida='Anulada';
		}

		
		echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>&nbsp;$estado_salida</td><td>$codigoProductoX</td><td>$nombreProductoX</td><td>$cantidadUnitariaSalidaF</td></tr>";

	}
	echo "</table></center><br>";


?>