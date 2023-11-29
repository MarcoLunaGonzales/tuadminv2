<?php
function saca_nombre_muestra($codigo)
{	require 'conexionmysqli.inc';
	$sql="select descripcion from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}
function nombreProducto($codigo)
{	require 'conexionmysqli.inc';
	$sql="select concat(descripcion, ' ',presentacion) from muestras_medicas where codigo='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$dat=mysqli_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}

function nombreGestion($codigo)
{	require 'conexionmysqli.inc';
	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreLinea($codigo)
{	require 'conexionmysqli.inc';
	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreVisitador($codigo)
{	require 'conexionmysqli.inc';
	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreTerritorio($codigo)
{	require 'conexionmysqli.inc';
	$sql="SELECT descripcion from ciudades where cod_ciudad='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	if($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	return($nombre);
}

function nombreMedico($codigo)
{	require 'conexionmysqli.inc';
	$sql="select concat(ap_pat_med,' ', nom_med) from Clientes where cod_med='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreDia($codigo)
{	require 'conexionmysqli.inc';
	$sql="select dia_contacto from orden_dias where id='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreCliente($codigo)
{	require 'conexionmysqli.inc';
	$sql="select nombre_cliente from clientes where cod_cliente='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	if($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	return($nombre);
}

function nombreProveedor($codigo){
	require 'conexionmysqli.inc';
	$sql="select nombre_proveedor from proveedores where cod_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	return($nombre);
}

function nombreAlmacen($codigo){
	require 'conexionmysqli.inc';
	$sql="select nombre_almacen from almacenes where cod_almacen='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	$numFilas=mysqli_num_rows($resp);
	if($dat=mysqli_fetch_array($resp)){
		$nombre=$dat[0];
	}
	return($nombre);
}

function nombreGrupo($codigo){
	require 'conexionmysqli.inc';
	$sql="select nombre_grupo from grupos where cod_grupo in ($codigo)";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[0]."-";
	}
	$nombre=substr($nombre,0,100);
	$nombre=$nombre."...";
	return($nombre);
}
function nombreLineaProveedor($codigo){
	require 'conexionmysqli.inc';
	$sql="select nombre_linea_proveedor from proveedores_lineas where cod_linea_proveedor='$codigo'";
	$resp=mysqli_query($enlaceCon, $sql);
	$dat=mysqli_fetch_array($resp);
	$nombre=$dat[0];
	//$nombre=mysqli_result($resp,0,0);
	return($nombre);
}

function nombreTipoPago($codigo){
	require 'conexionmysqli.inc';
	$sql="select t.cod_tipopago, t.abreviatura from tipos_pago t where t.cod_tipopago in ($codigo)";
	$resp=mysqli_query($enlaceCon, $sql);
	$nombre="";
	while($dat=mysqli_fetch_array($resp)){
		$nombre.=$dat[1]."-";
	}
	return($nombre);
}

?>