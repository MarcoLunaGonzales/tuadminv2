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
<form action='guardarCobranza.php' method='post' name='form1' onsubmit="return validar(this)" enctype="multipart/form-data">
<h3 align="center">Registrar Pagos</h3>

<table border='0' class='texto' cellspacing='0' align='center' width='80%' style='border:#ccc 1px solid;'>
<tr><th>Cliente</th><th>Fecha Pago</th><th>Observaciones</th></tr>
<?php
	$global_usuario 	= $_COOKIE['global_usuario'];
    $global_admin_cargo = $_COOKIE['global_admin_cargo'];
	$globalAlmacen		= $_COOKIE['global_almacen'];

	$sql1  = "SELECT DISTINCT c.cod_cliente, concat(c.nombre_cliente, ' ', c.paterno) as cliente
				FROM clientes c
				INNER JOIN salida_almacenes sa ON sa.cod_cliente = c.cod_cliente
				WHERE sa.cod_tipopago = 4
				AND sa.cod_tiposalida = 1001
				AND sa.salida_anulada = 0 
				AND sa.monto_final > sa.monto_cancelado ";
    if(!$global_admin_cargo){ // SI NO ES ADMINISTRADOR
		$sql1 .= " AND sa.cod_chofer = '$global_usuario' ";
	} 
	// $sql1 .= " AND sa.cod_almacen = '$globalAlmacen' ";
	$sql1 .= " ORDER BY cliente";
	
	$resp1 = mysqli_query($enlaceCon, $sql1);
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
<input type='text' class='elegant-input' value='<?php echo $fecha; ?>' id='fecha' name='fecha' style="width:80%;" readonly>
<img id='imagenFecha' src='../imagenes/fecha.bmp'>
</td>
<td>
<input type='text' class='elegant-input' value="" id='observaciones' size='40' name='observaciones'>
</td>


</tr>
</table>

</br>

<div id="divDetalle">
	<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
		<tr>
			<th>Tipo Doc</th>
			<th>Nro.</th>
			<th>Fecha</th>
			<th>Monto</th>
			<th>A Cuenta</th>
			<th>Saldo</th>
			<th>Tipo de Pago</th>
			<th>Monto a Pagar</th>
			<th>Nro. Doc. Pago</th>
			<th>Referencia</th>
			<th>Adjuntar Archivo</th>
		</tr>
	</table>
</div>


<center><input type='submit' class='boton' value='Guardar Cobranza' id='btsubmit' name='btsubmit' ></center>

</form>
<style>
	/**
	* ESTILO DE FORMULARIO
	**/
	.elegant-label {
		font-family: 'Poppins', sans-serif;
		font-weight: bold;
		color: #6c757d;
		display: flex;
		align-items: center;
		margin-bottom: 0;
	}

	.elegant-label span.text-danger {
		margin-right: 5px;
	}
	/* INPUT */
	.elegant-input {
		width: 100%;
		border: 2px solid #ced4da;
		border-radius: 5px;
		padding: 5px 5px;
		transition: all 0.3s ease;
	}

	.elegant-input:focus {
		border-color: #80bdff;
		box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
	}
	/* SELECT */
    .elegant-select {
        width: 100%;
        border: 2px solid #ced4da;
        border-radius: 5px;
        padding: 5px 5px;
        transition: all 0.3s ease;
        appearance: none; /* Para eliminar la flecha de estilo predeterminado del select */
        background: #fff url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' width='4' height='5'><path fill='none' stroke='%23333' stroke-width='2' d='M0 0l2 2 2-2'/></svg>") no-repeat right 10px center; /* Agregar una flecha personalizada */
        background-size: 8px 10px;
    }

    .elegant-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        outline: none; /* Para eliminar el borde de enfoque predeterminado del navegador */
    }

	.elegant-input::placeholder {
		color: #999;
		opacity: 1;
	}
</style>
</body>