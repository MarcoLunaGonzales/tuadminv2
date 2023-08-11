<html>
<body>
<table align='center' class="texto">
<tr>
<th><input type='checkbox' id='selecTodo'  onchange="marcarDesmarcar(form1,this)"></th><th>Grupo</th><th>CodInterno</th><th>Producto</th><th>Stock</th><th>Precio</th>
</tr>
<?php
require("conexion.inc");
require("funciones.php");

$codTipo=$_GET['codTipo'];
$nombreItem=$_GET['nombreItem'];
$codInterno=$_GET['codInterno'];
$globalAlmacen=$_COOKIE['global_almacen'];
$itemsNoUtilizar=$_GET['arrayItemsUtilizados'];
$globalAgencia=$_COOKIE["global_agencia"];

$soloStock=0;
if(isset($_GET['soloStock'])){
	$soloStock=$_GET['soloStock'];
}


	// $sql="select m.codigo_material, m.descripcion_material,
	// (select concat(p.nombre_proveedor,' ',pl.abreviatura_linea_proveedor) from proveedores p, proveedores_lineas pl where p.cod_proveedor=pl.cod_proveedor and pl.cod_linea_proveedor=m.cod_linea_proveedor) 
	// , m.codigo_anterior  from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";

	$sql="select m.codigo_material, m.descripcion_material,
	(select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo) 
	, m.codigo_anterior  from material_apoyo m where estado=1 and m.codigo_material not in ($itemsNoUtilizar)";
	if($nombreItem!=""){
		$sql=$sql. " and descripcion_material like '%$nombreItem%'";
	}
	if($codTipo!=0){
		$sql=$sql. " and m.cod_grupo='$codTipo' ";
	}
	if($codInterno!=""){
		$sql=$sql. " and m.codigo_anterior like '%$codInterno%' ";
	}
	$sql=$sql." order by 2";
	//echo $sql;
	$resp=mysql_query($sql);

	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		while($dat=mysql_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			$nombre=addslashes($nombre);
			$linea=$dat[2];
			$codigoInterno=$dat[3];
			
			$nombreCompletoProducto=$linea."-".$nombre."(".$codigoInterno.")";
			$nombreCompletoProducto=substr($nombreCompletoProducto,0,90);

			$stockProducto=stockProducto($globalAlmacen, $codigo);

			$datosProd=$codigo."|".$nombre."|".$linea."|".$stockProducto;
			
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$codigo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			$rs=mysql_query($consulta);
			$registro=mysql_fetch_array($rs);
			$precioProducto=$registro[0];
			if($precioProducto=="")
			{   $precioProducto=0;
			}
			$precioProducto=redondear2($precioProducto);
			
			if( $soloStock==0 || ($soloStock==1 && $stockProducto>0) ){
				echo "<tr>
				<td><input type='checkbox' id='idchk$cont' name='idchk$cont' value='$datosProd' onchange='ver(this)' ></td>
				<td>$linea</td>
				<td>$codigoInterno</td>
				<td><div class='textomedianonegro'><a href='javascript:setMateriales(form1, $codigo, \"$nombreCompletoProducto\")'>$nombre</a></div></td>
				<td>$stockProducto</td>
				<td>$precioProducto</td>
				</tr>";
			}
		}
	}else{
		echo "<tr><td colspan='3'>Sin Resultados en la busqueda.</td></tr>";
	}
	
?>
</table>

</body>
</html>