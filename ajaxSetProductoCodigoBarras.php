<?php
require("conexion.inc");
require("funciones.php");
$codigoItem=$_GET['codigo'];
$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

	$sql="select m.codigo_material, m.descripcion_material, m.cantidad_presentacion, 
		(select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor) from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor)
		from material_apoyo m where estado=1 
		and m.codigo_barras = '$codigoItem'";
	$sql=$sql." limit 1";
	$resp=mysql_query($sql);
	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$lineaProducto=$dat[3];
			$nombre=addslashes($nombre);
			$nombreCompletoProducto=$lineaProducto."-".$nombre;
			$nombreCompletoProducto=substr($nombreCompletoProducto,0,90);
			
			$cantidadPresentacion=$dat[2];			
			//SACAMOS EL PRECIO
			$sqlUltimoCosto="select id.precio_bruto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
			id.cod_material='$codigo' and i.cod_almacen='$globalAlmacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
			$respUltimoCosto=mysql_query($sqlUltimoCosto);
			$numFilas=mysql_num_rows($respUltimoCosto);
			$costoItem=0;
			if($numFilas>0){
				$costoItem=mysql_result($respUltimoCosto,0,0);
			}else{
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysql_query($sqlCosto);
				$numFilas2=mysql_num_rows($respCosto);
				if($numFilas2>0){
					$costoItem=mysql_result($respCosto,0,0);
				}
			}
			echo "1#####".$codigo."#####".$nombreCompletoProducto."#####".$cantidadPresentacion."#####".$costoItem."#####-#####-#####-#####-";
		}
	}else{
		echo "0#####_#####_#####_#####_#####_#####_#####_#####_";
	}

?>