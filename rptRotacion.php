<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('function_formatofecha.php');
require('funciones.php');
require('conexionmysqli.php');
require('estilos_almacenes.inc');

 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$rpt_almacen=$_POST['rpt_almacen'];
$rptGrupo=$_POST["rpt_grupo"];
$rptGrupo=implode(",",$rptGrupo);

$rpt_fecha=$_POST["rpt_fecha"];

$rptRotacionIni=$_POST['rpt_rotacionini'];
$rptRotacionFin=$_POST['rpt_rotacionfin'];

$rptGlobalAgencia=obtenerCodigoSucursal($rpt_almacen);


$fecha_reporte=date("d/m/Y");

$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
		echo "<h1>Reporte Rotacion de Productos<br>Almacen: <strong>$nombre_almacen</strong>
		<br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</h1>";
		//desde esta parte viene el reporte en si
		
			$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
			(select p.nombre_linea_proveedor from proveedores_lineas p where p.cod_linea_proveedor=ma.cod_linea_proveedor)as nombreproveedor, ma.peso, ma.codigo_anterior
			from material_apoyo ma
			where ma.codigo_material<>0 and ma.estado='1' and ma.cod_linea_proveedor in ($rptGrupo) order by ma.descripcion_material";
		//echo $sql_item;
		
		$resp_item=mysqli_query($enlaceCon,$sql_item);
		

		echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Grupo</th><th>Material</th><th>Stock</th><th>Ventas[u]</th></tr>
				</thead>";				
			
		$cadena_mostrar="";
		$indice=0;
		while($datos_item=mysqli_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreLinea=$datos_item[3];
			$pesoItem=$datos_item[4];
			$codigoInterno=$datos_item[5];
			
			$cadena_mostrar="<tbody>";
			$cadena_mostrar.="<tr><td>$indice</td><td>$codigoInterno</td><td>$nombreLinea</td><td>$nombre_item</td>";			
			
			$stock2=stockProductoAFecha($rpt_almacen, $codigo_item, $rpt_fecha);

			$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
			$resp_stock=mysqli_query($enlaceCon,$sql_stock);
			$dat_stock=mysqli_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			

			$cantidadVenta=0;
			if($stock2<0)
			{	$cadena_mostrar.="<td align='center'>0</td><td align='center'>0</td>";
			}
			else{	
				$cantidadVenta=obtenerCantidadVentasGeneradas($rptRotacionIni,$rptRotacionFin,$rptGlobalAgencia,$codigo_item);
				$cadena_mostrar.="<td align='center'>$stock2</td><td align='center'>$cantidadVenta</td>";
			}
			
			$cadena_mostrar.="</tr>";
			
			if($stock2>0){	
				echo $cadena_mostrar;
				$indice++;
			}
		}
		$cadena_mostrar.="</tbody>";
		echo "</table>";
		
?>