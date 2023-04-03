<script>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function number_format(amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto
    decimals = decimals || 0; // por si la variable no fue fue pasada
    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);
    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);
    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;
    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
    return amount_parts.join('.');
}

function calculaMargen1(porcentaje, index){
	porcentajeMargen=parseFloat(porcentaje.value);
	var costobase=parseFloat(document.getElementById('costobase'+index).value);	
	console.log("margenNuevo: "+porcentajeMargen);
	console.log("costobase: "+costobase);
	var precioNuevo=costobase+(costobase*(porcentajeMargen/100));	
	console.log("nuevoPrecioCliente: "+precioNuevo);
	var precioNuevoF=number_format(precioNuevo,2);
	document.getElementById('divprecio_mayor'+index).innerHTML=precioNuevoF;
}	
function calculaMargen2(porcentaje, index){
	porcentajeMargen=parseFloat(porcentaje.value);
	var costobase=parseFloat(document.getElementById('costobase'+index).value);	
	console.log("margenNuevo: "+porcentajeMargen);
	console.log("costobase: "+costobase);
	var precioNuevo=costobase+(costobase*(porcentajeMargen/100));	
	console.log("nuevoPrecioCliente: "+precioNuevo);
	var precioNuevoF=number_format(precioNuevo,2);
	document.getElementById('divprecio_menor'+index).innerHTML=precioNuevoF;
}	
function modifPreciosAjax(indice){
	var item=indice;
	var cod_ingreso_almacen=document.getElementById('cod_ingreso_almacen').value;
	var costobase=parseFloat(document.getElementById('costobase'+indice).value);	

	var margenmayor=document.getElementById('margenmayor|'+indice).value;
	var margenmenor=document.getElementById('margenmenor|'+indice).value;
	contenedor = document.getElementById('contenedorguardado'+indice);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxGuardarUtilidadImportacion.php?costobase="+costobase+"&cod_ingreso_almacen="+cod_ingreso_almacen+"&item="+item+"&margenmayor="+margenmayor+"&margenmenor="+margenmenor,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML="Guardando...";
		}
	}
	ajax.send(null)
	
}
</script>
<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	$global_almacen=$_COOKIE['global_almacen'];
	$codIngreso=$_GET['codigo_ingreso'];
?>	
<form method='post' action=''>
<?php
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='".$global_almacen."' and i.cod_ingreso_almacen='".$codIngreso."'";
	
	//echo $sql;
	
	$resp=mysql_query($sql);
?>	
	<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso, Costos de Importacion y Definicion de Margenes</th></tr></table></center><br>
	
	<table border='0' class='texto' align='center'>
	<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>
<?php	
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
?>	
	<tr><td align='center'><?=$nro_correlativo;?></td><td align='center'><?=$fecha_ingreso_mostrar;?></td><td><?=$nombre_tipoingreso;?></td><td>&nbsp;<?=$obs_ingreso;?></td></tr>
	</table>

<input type="hidden" name="cod_ingreso_almacen" id="cod_ingreso_almacen" value="<?=$codigo;?>">
	
<?php	

	$sql2="select count(*) nroItems, sum(i.cantidad_unitaria*i.costo_almacen)total, sum(metros_cubicos)m3 from ingreso_detalle_almacenes i where i.cod_ingreso_almacen='".$codIngreso."'"; 
	//echo $sql2;
	$resp2=mysql_query($sql2);
	$nroItems=0;
	$total=0;
	$totalCubicaje=0;
	while($dat2=mysql_fetch_array($resp2)){
		$nroItems=$dat2[0];
		$total=$dat2[1];
		$totalCubicaje=$dat2[2];
	}

	$sql_detalle="select m.codigo_anterior, i.cantidad_unitaria, i.costo_almacen, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'),
	m.descripcion_material, m.codigo_material, i.metros_cubicos, IFNULL(i.margen_xmayor,0)margen_xmayor, IFNULL(i.margen_xmenor,0)margen_xmenor, IFNULL(i.precio_clientexmayor,0)precio_clientexmayor, IFNULL(i.precio_clientexmenor,0)precio_clientexmenor, (select pl.nombre_linea_proveedor from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor)as linea_proveedor, (select pl.margen_precio from proveedores_lineas pl where pl.cod_linea_proveedor=m.cod_linea_proveedor)as margen_linea, m.codigo_material
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='".$codIngreso."' 
	and m.codigo_material=i.cod_material order by linea_proveedor, m.descripcion_material";
	$resp_detalle=mysql_query($sql_detalle);
?>
	<br><table border=0 class='texto' align='center'>
	<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>MetrosCubicos/<br>Peso[Kg]</th><th>Precio<br>Compra U.(Bs.)</th><th>Precio<br>Compra(Bs.)</th>
	<!--th>Participacion %</th-->
<?php	
	$sqlCii="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
	left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
	where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
	order by ci.nombre_costoimp";
	$respCii=mysql_query($sqlCii);
	$tipoCalculo="";
	$montoCiiFormato=0;
	while($datCii=mysql_fetch_array($respCii)){
		if($datCii['tipo_calculo']==1){
			$tipoCalculo="Nro de Items";
		}
		if($datCii['tipo_calculo']==2){
			$tipoCalculo="Participacion";
		}
		if($datCii['tipo_calculo']==3){
			$tipoCalculo="Cubicaje";
		}
		$montoCiiFormato=redondear2($datCii['monto'],2);
		
?>		
		<!--th><?=$datCii['nombre_costoimp']?><br>M:<?=$montoCiiFormato?>[Bs]<br>(<?=$tipoCalculo?>)</th-->
		
<?php
	}
	
	?>
	<th>Costo Total<br>Adicional</th>
	<th>Costo Total</th>
	<th>Costo Total<br>Unitario</th>
	<th>Margen por Mayor</th>
	<th>Margen por Menor</th>
	<th>-</th>
	<th>-</th>
	</tr>
<?php	
	$indice=1;
	$costoImpProd=0;
	$totalCostoImpProd=0;
	$totalMetrosCubicos=0;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$nombre_material=$dat_detalle[5];
		$metrosCubicos=$dat_detalle[7];

		$margenXMayor=$dat_detalle[8];
		$margenXMenor=$dat_detalle[9];
		$precioClientexMayor=$dat_detalle[10];
		$precioClientexMenor=$dat_detalle[11];

		$nombreLineaProveedorX=$dat_detalle[12];
		$margenPrecioProveedorX=$dat_detalle[13];

		$codigoProductoX=$dat_detalle[14];
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		$totalMetrosCubicos=$totalMetrosCubicos+$metrosCubicos;
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$particionProd=0;
		$particionProdFormato=0;
		$participacionCubicaje=0;
		if($total!=0){
			$particionProd=($totalValorItem*100)/$total;
			$particionProdFormato=redondear2($particionProd);
		}
		if($totalCubicaje>0){
			$participacionCubicaje=($metrosCubicos*100)/$totalCubicaje;
		}
		
?>
<tr>
<td align='center'><?=$indice;?></td>
	<td><?=$cod_material;?></td>
	<td><?=$nombre_material;?>-<span class="textopequenorojo"><small><?=$nombreLineaProveedorX;?></small><span></td>
	<td align='center'><?=$cantidad_unitaria;?></td>
	<td align='center'><?=$metrosCubicos;?></td>
	<td align='right'><?=$precioNeto;?></td>
	<td align="right"><?=$totalValorItem;?></td>
	<!--td align="right"><?=$particionProdFormato?></td-->
<?php		
		$sqlCii2="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
		left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
		where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
		order by ci.nombre_costoimp";
		$respCii2=mysql_query($sqlCii2);	
		$tipo_calculo=0;
		$montoCii=0;
		$costoImpProd=0;
		$costoProdTotal=0;
		$costoUnitarioTotal=0;
		while($datCii2=mysql_fetch_array($respCii2)){
			$tipo_calculo=$datCii2['tipo_calculo'];
			$montoCii=$datCii2['monto'];

			if($tipo_calculo==1){
				$auxCostoImpProd= $montoCii/$nroItems;
			}
			if($tipo_calculo==2){
				$auxCostoImpProd=($particionProdFormato*$montoCii)/100;
			}
			if($tipo_calculo==3){
				$auxCostoImpProd=($participacionCubicaje*$montoCii)/100;
			}
			$costoImpProd=$costoImpProd+$auxCostoImpProd;
			$costoProdTotal=$costoImpProd+$totalValorItem;
			$costoUnitarioTotal=$costoProdTotal/$cantidad_unitaria;

			$auxCostoImpProdF=formatonumeroDec($auxCostoImpProd);

?>
			<!--td align="right"><?=$auxCostoImpProdF?></td-->
<?php			
		}
		$totalCostoImpProd=$totalCostoImpProd+$costoImpProd;

		$costoImpProdF=formatonumeroDec($costoImpProd);
		$costoProdTotalF=formatonumeroDec($costoProdTotal);
		$costoUnitarioTotalF=formatonumeroDec($costoUnitarioTotal);

		if($margenXMayor==0 || $margenXMayor=="" || $margenXMayor==null || $margenXMayor=="null"){
			$margenXMayor=$margenPrecioProveedorX;
			$margenXMenor=$margenPrecioProveedorX;
			$precioClientexMayor=$costoUnitarioTotal+($costoUnitarioTotal*($margenXMayor/100));
			$precioClientexMenor=$costoUnitarioTotal+($costoUnitarioTotal*($margenXMenor/100));
		}

		$precioClientexMayorF=formatonumeroDec($precioClientexMayor);
		$precioClientexMenorF=formatonumeroDec($precioClientexMenor);
		
?>		
			<td align="right"><?=$costoImpProdF;?></td>
			<td align="right"><?=$costoProdTotalF;?></td>
			<td align="right"><b><?=$costoUnitarioTotalF;?></b><input type="hidden" name="costobase<?=$codigoProductoX;?>" id="costobase<?=$codigoProductoX;?>" value="<?=$costoUnitarioTotal;?>"></td>
			<td align="right"><input type='number' class="inputnumber" step="0.01" name="margenmayor|<?=$codigoProductoX;?>" id="margenmayor|<?=$codigoProductoX;?>" value="<?=$margenXMayor;?>" onKeyUp='calculaMargen1(this,<?php echo $codigoProductoX;?>);'>
			<div id='divprecio_mayor<?php echo $codigoProductoX;?>' class='textomedianorojo'><?=$precioClientexMayorF;?></div></td>
			<td align="right"><input type='number' class="inputnumber" step="0.01" name="margenmenor|<?=$codigoProductoX;?>" id="margenmenor|<?=$codigoProductoX;?>"  value="<?=$margenXMenor;?>" onKeyUp='calculaMargen2(this,<?php echo $codigoProductoX;?>);'>
			<div id='divprecio_menor<?php echo $codigoProductoX;?>' class='textomedianorojo'><?=$precioClientexMenorF;?></div></td>
			<td align="right"><a href='javascript:modifPreciosAjax(<?=$codigoProductoX;?>)'>
			<img src='imagenes/save4.png' title='Guardar.' width='30'></a></td>
			
			<td><div id='contenedorguardado<?=$codigoProductoX;?>'></div></td>
		</tr>
<?php		
		$indice++;
	}
	$totalFormato=redondear2($total);
	$totalCostoImpProdFormato=redondear2($totalCostoImpProd);
?>	
<tr>
<td colspan="4">&nbsp;</td>
<td align="right" ><?=$totalMetrosCubicos;?></td>
<td align="right" >&nbsp;</td>
<td align="right" ><?=$totalFormato;?></td>
<?php
$sqlCii="select cii.cod_costoimp,ci.nombre_costoimp,tipo_calculo,monto from costos_importacion_ingreso  cii
	left join costos_importacion ci on( cii.cod_costoimp=ci.cod_costoimp)
	where cii.cod_almacen='".$global_almacen."' and cii.cod_ingreso_almacen='".$codIngreso."'
	order by ci.nombre_costoimp";
	$respCii=mysql_query($sqlCii);
	$tipoCalculo="";
	$montoCiiFormato=0;
	while($datCii=mysql_fetch_array($respCii)){
		if($datCii['tipo_calculo']==1){
			$tipoCalculo="Nro de Items";
		}
		if($datCii['tipo_calculo']==2){
			$tipoCalculo="Participacion";
		}
		if($datCii['tipo_calculo']==3){
			$tipoCalculo="Cubicaje";
		}
		$montoCiiFormato=redondear2($datCii['monto']);
		
?>
<!--td align='right'><?=$montoCiiFormato?></td-->
<?php
	}
?>
<td align='right'><?=$totalCostoImpProdFormato;?></td>
</tr>
	</table>