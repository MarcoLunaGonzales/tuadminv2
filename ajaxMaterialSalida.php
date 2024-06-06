<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqlipdf.inc");
	$num=$_GET['codigo'];

	$globalAlmacen 	 = $_COOKIE['global_almacen'];
	$globalAlmacenNombre 	 = $_COOKIE['global_almacen_nombre'];
?>

<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
	<a href="javascript:encontrarMaterial(<?php echo $num;?>)" class="btn btn-primary btn-sm btn-fab"><i class='material-icons float-left' title="Ver en otras Sucursales">place</i></a>
</td>

<td width="40%" align="center">
	<!-- Codigo de Material -->
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<!-- Codigo de Sucursal -->
	<input type="hidden" name="cod_sucursales<?php echo $num;?>" id="cod_sucursales<?php echo $num;?>" value="<?=$globalAlmacen?>">
	<strong id="nombreSucursal<?php echo $num;?>"><?=$globalAlmacenNombre?></strong>
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="20%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value=''>
	</div>
</td>

<td align="center" width="20%">
	<input class="inputnumber" type="number" value="" min="0.01" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" required> 
</td>


<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>