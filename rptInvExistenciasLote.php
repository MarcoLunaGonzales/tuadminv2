<?php

//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');

$rpt_territorio=$_POST["rpt_territorio"];
$rpt_almacen=$_POST["rpt_almacen"];
$rpt_productos=$_POST["rpt_productos"];

$rptProductos=implode(", ", $rpt_productos);

$rpt_fecha=cambia_formatofecha($rpt_fecha);
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysql_query($sql_nombre_territorio);
	$datos_nombre_territorio=mysql_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysql_query($sql_nombre_almacen);
	$datos_nombre_almacen=mysql_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];


		echo "<h1>Reporte Existencias por Lote<br>Territorio: <strong>$nombre_territorio</strong> Nombre Almacen: <strong>$nombre_almacen</strong></h1>";
		//desde esta parte viene el reporte en si
		
		$sql_item="SELECT i.nro_correlativo, i.fecha, m.codigo_material, m.descripcion_material, id.lote, id.cantidad_unitaria, 
			id.cod_ingreso_almacen from ingreso_almacenes i
			INNER JOIN ingreso_detalle_almacenes id ON id.cod_ingreso_almacen=i.cod_ingreso_almacen
			INNER JOIN material_apoyo m ON m.codigo_material=id.cod_material
			where i.cod_almacen=1000 and i.ingreso_anulado=0 and id.cod_material in ($rptProductos) and id.lote not in ('','0','00')
			ORDER BY m.descripcion_material, i.fecha";

		//echo $sql_item;

		$resp_item=mysql_query($sql_item);
		
		echo "<br><table border=0 align='center' class='textomediano' width='70%'>
		<thead>
			<tr>
				<th>-</th>
				<th>Fecha</th>
				<th>Nro.Ingreso</th>
				<th>Cod.Producto</th>
				<th>Producto</th>
				<th>Lote</th>
				<th>Cantidad Ingreso</th>
				<th>Cantidad Salida</th>
				<th>Saldo</th>
			</tr>
		</thead>";
		
		$indice=1;
		while($datos_item=mysql_fetch_array($resp_item))
		{	$nroIngreso=$datos_item[0];
			$fecha=$datos_item[1];
			$codProducto=$datos_item[2];
			$nombreProducto=$datos_item[3];
			$loteProducto=$datos_item[4];
			$cantidadIngreso=$datos_item[5];
			$codIngresoAlmacen=$datos_item[6];

			$sql_salidas="select IFNULL(sum(sd.cantidad_unitaria),0) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen'
			and sd.cod_material='$codProducto' and s.salida_anulada=0 and sd.cod_ingreso_almacen='$codIngresoAlmacen' and sd.lote='$loteProducto'";

			//echo $sql_salidas;
			
			$resp_salidas=mysql_query($sql_salidas);
			$cantidadSalida=0;
			if($dat_salidas=mysql_fetch_array($resp_salidas)){
				$cantidadSalida=$dat_salidas[0];
			}


			$saldoProducto=$cantidadIngreso - $cantidadSalida;
			
			echo "<tr>
				<td>-</td>
				<td>$fecha</td>
				<td>$nroIngreso</td>
				<td>$codProducto</td>
				<td>$nombreProducto</td>
				<td>$loteProducto</td>
				<td>$cantidadIngreso</td>
				<td>$cantidadSalida</td>
				<td>$saldoProducto</td>
			</tr>";				
			

		}
		$cadena_mostrar.="</tbody>";
		echo "</table>";
		
?>