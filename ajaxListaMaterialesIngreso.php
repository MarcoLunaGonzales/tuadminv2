<html>
<body>
<table align='center' class="texto">
<tr>
<th>CodInterno</th><th>Producto</th><th>Manejo</th><th>Stock</th></tr>
<?php
require("conexion.inc");
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codInterno=$_GET['codInterno'];

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];
//$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$itemsNoUtilizar="0";

	$sql="SELECT m.codigo_material, m.descripcion_material, m.cantidad_presentacion, m.codigo_anterior,
		(SELECT t.nombre from tipos_material_manejo t where t.cod_tipomanejo=m.cod_tipomanejo)
	 	from material_apoyo m where estado=1 
		and m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and cod_grupo = '$codTipo' ";
	}
	if($codInterno!=""){
		$sql=$sql. " and m.codigo_anterior like '%$codInterno%' ";
	}
	$sql=$sql." order by 2";
	$resp=mysql_query($sql);

	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];
			$codigoInterno=$dat[3];
			$tipoManejo=$dat[4];
			
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
			
			echo "<tr>
				<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre\", $cantidadPresentacion, $costoItem)'>$codigoInterno</a></div></td>
				<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre ($codigoInterno)\", $cantidadPresentacion, $costoItem)'>$nombre</a></div></td>
				<td><div class='textograndenegro'>$tipoManejo</a></div></td>
				<td><div class='textograndenegro'>-</a></div></td>
				</tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}

?>
</table>

</body>
</html>