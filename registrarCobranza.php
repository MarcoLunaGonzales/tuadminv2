<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type='text/javascript' language='javascript'>
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
function ajaxCargarDeudas(){
	var contenedor;
	contenedor = document.getElementById('divDetalle');

	var codCliente = document.getElementById('cliente').value;

	ajax=nuevoAjax();

	ajax.open("GET", "ajaxCargarDeudas.php?codCliente="+codCliente,true);

	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}else{
			contenedor.innerHTML = "Cargando...";
		}
	}
	ajax.send(null)
}


function validar(f)
{   
	var codCliente=document.getElementById("cliente").value;
	var numRegistros=document.getElementById("nroFilas").value;
	var monto;
	var nroDoc;
	if(codCliente==0){
		alert("Debe seleccionar un Cliente");
	}else{
		if(numRegistros>0){
			for(var i=1; i<=numRegistros; i++){
				monto=parseFloat(document.getElementById("montoPago"+i).value);
				nroDoc=parseFloat(document.getElementById("nroDoc"+i).value);
				//if(monto==0 || nroDoc==0 || monto=="NaN" || nroDoc=="NaN"){
					//alert("Monto de Pago, Nro. Doc. no pueden estar vacios. Fila: "+i);
					//return(false);
				//}
				f.submit();
			}
		}
		
	}	
}

function solonumeros(e)
{
	var key;
	if(window.event) {// IE
		key = e.keyCode;
	}else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}
	if (key < 46 || key > 57) 
	{
	  return false;
	}
	return true;
}



	</script>
<?php

require("conexion.inc");

require("estilos_almacenes.inc");
?>
<body>
<form action='guardarCobranza.php' method='post' name='form1'>
<h3 align="center">Registrar Pagos</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Cliente</th><th>Fecha Pago</th><th>Observaciones</th></tr>
<?php
$sql1="select cod_cliente, nombre_cliente from clientes order by 2";
$resp1=mysql_query($sql1);
?>
<tr>
<td align='center'>
<select name='cliente' id='cliente' class='texto' onChange="ajaxCargarDeudas();">
	<option value="0">Seleccione una opcion</option>
<?php
while($dat1=mysql_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
?>
	<option value="<?php echo $codigo; ?>"><?php echo $nombre; ?></option>
<?php	
}
$fecha=date("d/m/Y");
?>
</select>
</td>
<td>
<input type='text' class='texto' value='<?php echo $fecha; ?>' id='fecha' size='10' name='fecha'>
<img id='imagenFecha' src='imagenes/fecha.bmp'>
</td>
<td>
<input type='text' class='texto' value="" id='observaciones' size='40' name='observaciones'>
</td>


</tr>
</table>

</br>

<div id="divDetalle">
	<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
		<tr><th>Nro. OC</th><th>Fecha OC</th><th>Monto OC</th><th>A Cuenta</th><th>Saldo OC</th><th>Monto a Pagar</th><th>Nro. Doc. Pago</th></tr>
	</table>
</div>


<table align='center'>
	<tr>
	<td><a href='navegador_pagos.php'><img  border='0'src='imagenes/back.png' width='25' height='25'>Volver Atras</a>
	</td>
	</tr>
</table>


<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>
<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>

</form>
</body>