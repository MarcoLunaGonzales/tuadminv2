<?php
function saca_nombre_muestra($codigo)
{	$sql="select descripcion from muestras_medicas where codigo='$codigo'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}
function nombreProducto($codigo)
{	$sql="select concat(descripcion, ' ',presentacion) from muestras_medicas where codigo='$codigo'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}

function nombreGestion($codigo)
{	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreLinea($codigo)
{	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreVisitador($codigo)
{	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreTerritorio($codigo)
{	$sql="select descripcion from ciudades where cod_ciudad='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreMedico($codigo)
{	$sql="select concat(ap_pat_med,' ', nom_med) from Clientes where cod_med='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreDia($codigo)
{	$sql="select dia_contacto from orden_dias where id='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}


function nombreRutero($codigo)
{	$sql="select nombre_rutero from rutero_maestro_cab where cod_rutero='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreZona($codigo)
{	$sql="select zona from zonas where cod_zona='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreCategoria($codigo, $link)
{	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";
	$resp=mysql_query($sql, $link);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreCliente($codigo)
{	$sql="select nombre_cliente from clientes where cod_cliente='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreProveedor($codigo){
	$sql="select nombre_proveedor from proveedores where cod_proveedor='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

function nombreAlmacen($codigo){
	$sql="select nombre_almacen from almacenes where cod_almacen='$codigo'";
	$resp=mysql_query($sql);
	$numFilas=mysql_num_rows($resp);
	if($numFilas>0){
		$nombre=mysql_result($resp,0,0);		
	}else{
		$nombre="-";
	}
	return($nombre);
}

function nombreGrupo($codigo){
	$sql="select nombre_grupo from grupos where cod_grupo in ($codigo)";
	$resp=mysql_query($sql);
	$nombre="";
	while($dat=mysql_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	$nombre=substr($nombre,0,100);
	$nombre=$nombre."...";
	return($nombre);
}
function nombreLineaProveedor($codigo){
	$sql="select nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor='$codigo'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysql_result($resp,0,0);
	return($nombre);
}

?>