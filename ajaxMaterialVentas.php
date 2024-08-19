<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexion.inc");
	$num=$_GET['codigo'];
	$cod_precio=0;
	if(isset($_GET["cod_precio"])){
		$cod_precio=$_GET["cod_precio"];
	}

	$globalAlmacen 	 = $_COOKIE['global_almacen'];
	$globalAlmacenNombre 	 = $_COOKIE['global_almacen_nombre'];
?>
<table border="1" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="7%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
	<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>
</td>

<td width="38%" align="center">
	<input type="hidden" class="formIndex" value="<?php echo $num;?>">
	<input type="hidden" name="precioVentaArray<?php echo $num;?>" id="precioVentaArray<?php echo $num;?>" value="[]">
	<!-- Codigo de Producto -->
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<!-- Codigo de Sucursal -->
	<input type="hidden" name="cod_sucursales<?php echo $num;?>" id="cod_sucursales<?php echo $num;?>" value="<?=$globalAlmacen?>">

	<strong id="nombreSucursal<?php echo $num;?>"><?=$globalAlmacenNombre?></strong>
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="10%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<!-- <input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value=''> -->
		<input type='number' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' style="height:20px;font-size:19px;width:80px;color:red;" readonly>
	</div>
</td>

<td align="center" width="10%">
	<!-- <input class="inputnumber cantidad_upd" data-index="<?php echo $num;?>" type="number" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="1" value="1" style="height:20px;font-size:19px;width:100px;color:black;" required>  -->
	<input class="inputnumber cantidad_upd" 
			data-index="<?php echo $num;?>" 
			type="number" 
			min="1" 
			id="cantidad_unitaria<?php echo $num;?>"  
			name="cantidad_unitaria<?php echo $num;?>" 
			oninput='calculaMontoMaterial(<?php echo $num;?>);' 
			onchange='calculaMontoMaterial(<?php echo $num;?>);' 
			step="1" 
			value="1" 
			style="height:20px;font-size:19px;width:100px;color:black;" 
			required> 
</td>


<td align="center" width="10%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber" type="number" min="1" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" style="height:20px;font-size:19px;width:100px;color:blue;" required><br>
		<b id="precio_of<?php echo $num;?>" style="font-size:15px;color:red;">Of.</b>
	</div>
</td>

<td align="center" width="10%">
	<?php
			// $sql1="select codigo, nombre, abreviatura from tipos_precio where estado=1 order by 3";
			// $resp1=mysqli_query($enlaceCon,$sql1);
			// echo "<select name='tipoPrecio' class='texto".$num."' id='tipoPrecio".$num."' style='width:55px !important;float:left;' onchange='ajaxPrecioItem(".$num.")'>";
			// while($dat=mysqli_fetch_array($resp1)){
			// 	$codigo=$dat[0];
			// 	$nombre=$dat[1];
			// 	$abreviatura=$dat[2];
			// 	if($codigo==$cod_precio){
            //      echo "<option value='$codigo' selected>$abreviatura %</option>";					 
			// 	}else{
			// 	echo "<option value='$codigo'>$abreviatura %</option>";					
			// 	}
			// }
			// echo "</select>";
	?>
	<input class="inputnumber" type="number" value="0" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);'  value="0" step="0.01" readonly>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:20px;width:120px;color:red;" required readonly> 
</td>

<td align="center"  width="5%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>