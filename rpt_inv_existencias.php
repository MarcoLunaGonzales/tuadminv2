
<style>
    thead {
        font-size: 16px;
        background-color: #f8f9fa;
        color: #343a40; 
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #dee2e6;
    }

    tbody {
        font-size: 15px;
        background-color: #ffffff;
        color: #495057;
    }

    tbody tr {
        border-bottom: 1px solid #dee2e6;
    }

    tbody td, thead th {
        padding: 10px;
        text-align: left;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #e9ecef;
    }
</style>
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
$rptOrdenar=$_POST["rpt_ordenar"];
$rptGrupo=$_POST["rpt_grupo"];
$rptGrupo=implode(",",$rptGrupo);

$rptPais=$_POST["rpt_pais"];
$rptPais=implode(",",$rptPais);


$rptFormato=$_POST["rpt_formato"];

$rptFormato=$_POST["rpt_formato"];
$rptFormato=$_POST["rpt_formato"];

$rpt_fecha=$_POST["rpt_fecha"];

$rpt_territorio=obtenerCodigoSucursal($rpt_almacen);

$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";

	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
		echo "<h1>Reporte Existencias Almacen<br>Nombre Almacen: <strong>$nombre_almacen</strong>
		<br>Existencias a Fecha: <strong>$rpt_fecha</strong><br>$txt_reporte</h1>";
		//desde esta parte viene el reporte en si
		
		if($rptOrdenar==1){
			$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
			(select p.nombre_linea_proveedor from proveedores_lineas p where p.cod_linea_proveedor=ma.cod_linea_proveedor)as nombreproveedor, ma.peso, ma.codigo_anterior, pp.nombre
			from material_apoyo ma, pais_procedencia pp
			where ma.codigo_material<>0 and ma.estado='1' and ma.cod_pais_procedencia=pp.codigo and ma.cod_linea_proveedor in ($rptGrupo) and ma.cod_pais_procedencia in ($rptPais) order by ma.descripcion_material";
		}else{
			$sql_item="select ma.codigo_material,
			ma.descripcion_material, ma.cantidad_presentacion, p.nombre_linea_proveedor, ma.peso, ma.codigo_anterior, pp.nombre
			 from proveedores_lineas p, 
			material_apoyo ma, pais_procedencia pp
			where p.cod_linea_proveedor=ma.cod_linea_proveedor and ma.estado='1' 
			and ma.cod_linea_proveedor in ($rptGrupo) and pp.codigo=ma.cod_pais_procedencia and ma.cod_pais_procedencia in ($rptPais) order by 4,2";
		}
		
		//echo $sql_item;
		
		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		if($rptOrdenar==1){
			/*echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Peso[Kg]</th>
				<th>Cantidad</th></tr>
			</thead>";*/
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th></tr>
				</thead>";				
			}
			if($rptFormato==2){//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Producto</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					</tr>
				</thead>";
			}
			if($rptFormato==3){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>PrecioCosto</th><th>Subtotal</th></tr>
				</thead>";				
			}
			if($rptFormato==4){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>PrecioVenta</th><th>Subtotal</th></tr>
				</thead>";				
			}


		}else{
			/*echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>Codigo</th><th>Marca</th><th>Producto</th><th>Peso[Kg]</th>
				<th>Cantidad</th></tr>
			</thead>";*/
			if($rptFormato==1){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Marca</th><th>Pais</th><th>Producto</th><th>Cantidad</th></tr>
				</thead>";				
			}
			if($rptFormato==2){//PARA INVENTARIO
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Marca</th><th>Pais</th><th>Producto</th><th>Cantidad</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th></tr>
				</thead>";
			}
			if($rptFormato==3){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Marca</th><th>Pais</th><th>Producto</th><th>Cantidad</th><th>PrecioCosto</th><th>Subtotal</th></tr>
				</thead>";				
			}
			if($rptFormato==4){
				echo "<br><table border=0 align='center' class='textomediano' width='70%'>
				<thead>
					<tr><th>&nbsp;</th><th>Codigo</th><th>Marca</th><th>Pais</th><th>Producto</th><th>Cantidad</th><th>PrecioVenta</th><th>Subtotal</th></tr>
				</thead>";				
			}
		}
		$cadena_mostrar="";
		$indice=1;
		while($datos_item=mysqli_fetch_array($resp_item))
		{	$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreLinea=$datos_item[3];
			$pesoItem=$datos_item[4];
			$codigoInterno=$codigo_item;
			$paisProcedencia=$datos_item[6];
			
			$cadena_mostrar="<tbody>";
			if($rptOrdenar==1){
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombre_item</td><td>$pesoItem</td>";
				if( $rptFormato==1 || $rptFormato==3 || $rptFormato==4 ){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigoInterno</td><td>$nombre_item</td>";
				}
				if($rptFormato==2){//para inventario
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombre_item</td>";
				}
			}else{
				//$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$nombreLinea</td><td>$nombre_item</td><td>$pesoItem</td>";				
				if($rptFormato==1 || $rptFormato==3 || $rptFormato==4){
					$cadena_mostrar.="<tr><td>$indice</td><td>$codigoInterno</td><td>$nombreLinea</td><td>$paisProcedencia</td><td>$nombre_item</td>";			
				}else{
					$cadena_mostrar.="<tr><td>$indice</td><td>$nombreLinea</td><td>$paisProcedencia</td><td>$nombre_item</td>";				
				}
			}
			
			$stock2=stockProductoAFecha($rpt_almacen, $codigo_item, $rpt_fecha);

			$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";
			$resp_stock=mysqli_query($enlaceCon,$sql_stock);
			$dat_stock=mysqli_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			
			if($stock2<0)
			{	$cadena_mostrar.="<td align='right'>0</td>";
			}
			else{	
				$cadena_mostrar.="<td align='right'>$stock2</td>";
			}
			
			if($rptFormato==2){//para inventario
				$cadena_mostrar.="<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
			}
			
			if($rptFormato==3){
				$cadena_mostrar.="<td>-</td><td>-</td>";	
			}
			if($rptFormato==4){
				$precioVentaX=precioVentaN($enlaceCon, $codigo_item, $rpt_territorio);
				$subtotalPrecioVenta=$precioVentaX*$stock2;
				$subtotalPrecioVentaF=formatonumeroDec($subtotalPrecioVenta);
				$cadena_mostrar.="<td align='right'>$precioVentaX</td><td align='right'>$subtotalPrecioVentaF</td>";	
			}

			$cadena_mostrar.="</tr>";
			
			if($rpt_ver==1)
				{	echo $cadena_mostrar;
					$indice++;
				}
				if($rpt_ver==2 and $stock2>0)
				{	echo $cadena_mostrar;
					$indice++;					
				}
				if($rpt_ver==3 and $stock2==0)
				{	echo $cadena_mostrar;
					$indice++;
				}
			}

		$cadena_mostrar.="</tbody>";
		echo "</table>";
		
?>