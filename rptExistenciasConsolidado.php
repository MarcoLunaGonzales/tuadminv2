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
$rpt_almacen=implode(",",$rpt_almacen);

$rptOrdenar=$_POST["rpt_ordenar"];

$rptGrupo=$_POST["rpt_grupo"];
$rptGrupo=implode(",",$rptGrupo);

$rpt_fecha=$_POST["rpt_fecha"];

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

		echo "<h1>Reporte Existencias Almacen
		<br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</h1>";
		//desde esta parte viene el reporte en si
		
		if($rptOrdenar==1){
			$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
			(select p.nombre_linea_proveedor from proveedores_lineas p where p.cod_linea_proveedor=ma.cod_linea_proveedor)as nombreproveedor, ma.peso, ma.codigo_anterior
			from material_apoyo ma
			where ma.codigo_material<>0 and ma.estado='1' and ma.cod_linea_proveedor in ($rptGrupo) order by ma.descripcion_material";
		}else{
			$sql_item="select ma.codigo_material,
			ma.descripcion_material, ma.cantidad_presentacion, p.nombre_linea_proveedor, ma.peso, ma.codigo_anterior
			 from proveedores_lineas p, 
			material_apoyo ma where p.cod_linea_proveedor=ma.cod_linea_proveedor and ma.estado='1' 
			and ma.cod_linea_proveedor in ($rptGrupo) order by 4,2";
		}
		
		//echo $sql_item;
		
		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		if($rptOrdenar==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th>";
		}else{
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Grupo</th><th>Producto</th>";				
		}
		$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a where a.cod_almacen in ($rpt_almacen)";
		$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
		while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
			$codAlmacenX=$datAlmacenes[0];
			$nombreAlmacenX=$datAlmacenes[1];
			echo "<th>$nombreAlmacenX</th>";
		}
		echo "<th>Total Producto</th></tr>
				</thead>";
		$cadena_mostrar="";
		$indice=0;
		while($datos_item=mysqli_fetch_array($resp_item)){	

			$totalProducto=0;

			$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreLinea=$datos_item[3];
			$pesoItem=$datos_item[4];
			$codigoInterno=$datos_item[5];
			
			$cadena_mostrar="<tbody>";
			if($rptOrdenar==1){
				$cadena_mostrar.="<tr><td>$indice</td><td>$codigoInterno</td><td>$nombre_item</td>";
			}else{
				$cadena_mostrar.="<tr><td>$indice</td><td>$codigoInterno</td><td>$nombreLinea</td><td>$nombre_item</td>";	
			}
			
			$sqlAlmacenes="SELECT a.cod_almacen, a.nombre_almacen from almacenes a where a.cod_almacen in ($rpt_almacen)";
			$respAlmacenes=mysqli_query($enlaceCon, $sqlAlmacenes);
			while($datAlmacenes=mysqli_fetch_array($respAlmacenes)){
				$codAlmacenX=$datAlmacenes[0];
				$stock2=stockProductoAFecha($codAlmacenX, $codigo_item, $rpt_fecha);
				$totalProducto+=$stock2;

				if($stock2<0){	
					$stock2=0;	
					$stock2F="-";
				}else{
					$stock2F=$stock2;
				}
				$cadena_mostrar.="<td align='center'>$stock2</td>";
			}
			
			$cadena_mostrar.="<td align='center'>$totalProducto</td></tr>";
			
			if($rpt_ver==1){	
				echo $cadena_mostrar;
			}
			if($rpt_ver==2 and $totalProducto>0){	
				echo $cadena_mostrar;
			}
			if($rpt_ver==3 and $totalProducto==0){	
				echo $cadena_mostrar;
			}
			$indice++;
		}

		$cadena_mostrar.="</tbody>";
		echo "</table>";
		
?>