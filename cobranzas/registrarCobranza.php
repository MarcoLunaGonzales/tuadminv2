<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="../lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <link href="../stilos.css" rel='stylesheet' type='text/css'>
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
	var banderaMontos=0;

	var monto;
	var nroDoc;
	if(codCliente==0){
		alert("Debe seleccionar un Cliente");
		return false;
	}else{
		/******** Validacion Algun Monto con Datos ********/
		var inputs = $('form input[name^="montoPago"]');
		inputs.each(function() {
		  	var value = $(this).val();
		  	if(value>0){
					banderaMontos=1;
		  	}
		});
		if(banderaMontos==0){
			alert("Debe existir algun monto valido para guardar la cobranza.");
			return(false);
		}
		/******** Fin validacion Cantidades ********/
	}	
	return true;
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

require("../conexionmysqli.inc");

?>
<body>
<form action='guardarCobranza.php' method='post' name='form1' onsubmit="return validar(this)">
<h3 align="center">Registrar Pagos</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Cliente</th><th>Fecha Pago</th><th>Observaciones</th></tr>
<?php
$sql1="select cod_cliente, concat(nombre_cliente,' ',paterno) from clientes order by 2";
$resp1=mysqli_query($enlaceCon, $sql1);
?>
<tr>
<td align='center'>
<select name='cliente' id='cliente' class='selectpicker' data-style="btn btn-success" data-live-search="true" onChange="ajaxCargarDeudas();">
	<option value="0">Seleccione una opcion</option>
<?php
while($dat1=mysqli_fetch_array($resp1))
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
<img id='imagenFecha' src='../imagenes/fecha.bmp'>
</td>
<td>
<input type='text' class='texto' value="" id='observaciones' size='40' name='observaciones'>
</td>


</tr>
</table>

</br>

<div id="divDetalle">
	<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
		<tr><th>Nro. OC</th><th>Fecha OC</th><th>Monto OC</th><th>A Cuenta</th><th>Saldo OC</th><th>Monto a Pagar</th><th>Nro. Recibo</th></tr>
	</table>
</div>


<center><input type='submit' class='boton' value='Guardar Cobranza' id='btsubmit' name='btsubmit' ></center>

</form>
</body>