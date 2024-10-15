<html>
<body>
<table align='center' class="texto">
<tr>
<th>Codigo</th><th>Producto</th><th>Stock</th></tr>
<?php
require("conexion.inc");
$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codInterno=$_GET['codInterno'];

$codProveedor=$_GET['cod_proveedor'] ?? '';

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];
//$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$itemsNoUtilizar="0";

	$sql="SELECT m.codigo_material, m.descripcion_material, m.cantidad_presentacion, m.codigo_anterior 
			FROM material_apoyo m 
			where estado = 1 and 
			m.codigo_material not in ($itemsNoUtilizar)";
	if(!empty($codProveedor)){
		$sql=$sql. " AND cod_linea_proveedor = '$codProveedor'";
	}

	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and cod_linea_proveedor = '$codTipo' ";
	}
	if($codInterno!=""){
		$sql=$sql. " and m.codigo_anterior like '%$codInterno%' ";
	}
	$sql=$sql." order by 2";
	$resp=mysqli_query($enlaceCon,$sql);

	$numFilas=mysqli_num_rows($resp);
	if($numFilas>0){
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$cantidadPresentacion=$dat[2];
			$codigoInterno=$dat[3];
			
			//SACAMOS EL PRECIO
			$sqlUltimoCosto="select id.precio_bruto from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
			id.cod_material='$codigo' and i.cod_almacen='$globalAlmacen' ORDER BY i.cod_ingreso_almacen desc limit 0,1";
			$respUltimoCosto=mysqli_query($enlaceCon,$sqlUltimoCosto);
			$numFilas=mysqli_num_rows($respUltimoCosto);
			$costoItem=0;
			if($numFilas>0){
				$costoItem=mysqli_result($respUltimoCosto,0,0);
			}else{
				//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
				$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='0' 
				and cod_ciudad='$globalAgencia'";
				$respCosto=mysqli_query($enlaceCon,$sqlCosto);
				$numFilas2=mysqli_num_rows($respCosto);
				if($numFilas2>0){
					$costoItem=mysqli_result($respCosto,0,0);
				}
			}
			
			echo "<tr><td align='center'><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre\", $cantidadPresentacion, $costoItem)'>$codigo</a></div></td>
			<td><div class='textograndenegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombre ($codigo)\", $cantidadPresentacion, $costoItem)'>$nombre</a></div></td>
			<td><div class='textograndenegro'>-</a></div></td></tr>";
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}

?>
</table>

</body>
</html>