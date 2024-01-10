<?php
require('function_formatofecha.php');
require('funciones.php');

require("conexionmysqli.php");
require("estilos_almacenes.inc");

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$rptAlmacenOrigen=$_POST["rpt_almacen"];
$rptFechaInicio=$_POST["fecha_inicio"];
$rptFechaFinal=$_POST["fecha_final"];
$tiposIngreso=$_POST["tipos_ingreso"];
$tiposSalida=$_POST["tipos_salida"];

$tiposIngresoString = implode(",", $tiposIngreso); 
$tiposSalidaString = implode(",", $tiposSalida); 


$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

$nombre_tiporeporte="Reporte Ingresos Vs. Salidas";

echo "<h1>$nombre_tiporeporte <br> Fecha inicio: <strong>$rptFechaInicio</strong>Fecha final: <strong>$rptFechaFinal</strong> <br>$txt_reporte</th></tr></table>";

$fecha_iniconsulta=$rptFechaInicio;
$fecha_finconsulta=$rptFechaFinal;

echo "<center><br><table class='texto'>";
echo "<tr><th>Fecha</th><th>Detalle de Ingresos</th><th>Detalle de Salidas</th></tr>";


$sqlFechas="SELECT distinct(i.fecha)as fecha from ingreso_almacenes i where i.ingreso_anulado=0 and i.cod_tipoingreso in 
	($tiposIngresoString) and i.cod_almacen='$rptAlmacenOrigen' and i.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' union 
	SELECT distinct(s.fecha)as fecha from salida_almacenes s where s.salida_anulada=0 and s.cod_tiposalida in ($tiposSalidaString)
	and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' order by fecha";
	//echo $sqlFechas;
$respFechas=mysqli_query($enlaceCon, $sqlFechas);
while($datFechas=mysqli_fetch_array($respFechas)){
	$fechaTransaccion=$datFechas[0];

	$sqlIngreso="SELECT i.hora_ingreso, id.cod_material, ma.descripcion_material, id.cantidad_unitaria, 
	(select t.nombre_tipoingreso from tipos_ingreso t where t.cod_tipoingreso=i.cod_tipoingreso), i.observaciones from ingreso_almacenes i, ingreso_detalle_almacenes id, material_apoyo ma where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material =ma.codigo_material and i.fecha='$fechaTransaccion' and i.cod_tipoingreso in ($tiposIngresoString) ";
	//echo $sqlIngreso;
	$respIngreso=mysqli_query($enlaceCon, $sqlIngreso);
	$cantidadIngreso=0;
	$fechaIngreso="";
	$txtDetalleIngreso="<table class='textomini'><tr><th>Tipo</th><th>Hora</th><th>Obs.</th><th>Producto</th><th>Cantidad</th></tr>";
	while($datIngreso=mysqli_fetch_array($respIngreso)){
		$horaIngreso=$datIngreso[0];
		$codProductoIngreso=$datIngreso[1];
		$nombreProductoIngreso=$datIngreso[2];
		$cantidadUnitIngreso=$datIngreso[3];
		$cantidadUnitIngresoF=formatNumberInt($cantidadUnitIngreso);
		$nombreTipoIngreso=$datIngreso[4];
		$obsIngreso=$datIngreso[5];

		$txtDetalleIngreso.="<tr><td>$nombreTipoIngreso</td><td>$horaIngreso</td><td>$obsIngreso</td><td>$nombreProductoIngreso</td><td>$cantidadUnitIngresoF</td></tr>";
	}
	$txtDetalleIngreso.="</table>";

	$sqlSalidas="SELECT s.hora_salida, s.nro_correlativo, 
	(select a.nombre_almacen from almacenes a where a.cod_almacen=s.almacen_destino)as almacen_destino, 
	(select e.nombre_estado from estados_salida e where e.cod_estado=s.estado_salida) as estado, s.observaciones, sd.cod_material, m.codigo_anterior, m.descripcion_material, sd.cantidad_unitaria, s.cod_salida_almacenes,
	(select ts.nombre_tiposalida from tipos_salida ts where ts.cod_tiposalida=s.cod_tiposalida)
	 from salida_almacenes s, salida_detalle_almacenes sd, material_apoyo m 
	 where s.cod_salida_almacenes=sd.cod_salida_almacen and sd.cod_material=m.codigo_material and s.cod_tiposalida in ($tiposSalidaString) and s.cod_almacen='$rptAlmacenOrigen' and s.salida_anulada=0 and s.fecha='$fechaTransaccion' ";
	//echo $sqlSalidas;
	$respSalidas=mysqli_query($enlaceCon, $sqlSalidas);
	$txtDetalleSalida="<table class='textomini'><tr><th>Tipo</th><th>Hora</th><th>Obs.</th><th>Producto</th><th>Cantidad</th></tr>";
	while($datSalidas=mysqli_fetch_array($respSalidas))
	{
		$horaSalida=$datSalidas[0];
		$nro_correlativo=$datSalidas[1];
		$nombreAlmacenDestino=$datSalidas[2];
		$nombreEstadoSalida=$datSalidas[3];
		$obs_salida=$datSalidas[4];		
		$codProducto=$datSalidas[5];
		$codInterno=$datSalidas[6];
		$nombreProducto=$datSalidas[7];
		$cantidadProducto=$datSalidas[8];
		$cantidadProductoF=formatNumberInt($cantidadProducto);
		$tipoSalida=$datSalidas[10];

		$txtDetalleSalida.="<tr><td>$tipoSalida</td><td>$horaSalida</td><td>$obs_salida</td><td>$nombreProducto</td><td>$cantidadProductoF</td></tr>";
	}
	$txtDetalleSalida.="</table>";


	echo "<tr><td>$fechaTransaccion</td><td>$txtDetalleIngreso</td><td>$txtDetalleSalida</td></tr>";
}
echo "</table></center><br>";

?>