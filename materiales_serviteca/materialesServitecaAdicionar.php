<?php
	require_once("../conexion.inc");
	require_once("../estilos2.inc");
	require_once("configModule.php");

	$cod_venta = $_GET['codVenta'];

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='$urlRegister';
		}

		function calcularTotal(index) {
			var cantidad = parseFloat(document.getElementsByName('cantidad'+index)[0].value);
			var precio = parseFloat(document.getElementsByName('precio'+index)[0].value);
			var total = cantidad * precio;
			if (!isNaN(total)) {
				document.getElementsByClassName('total'+index)[0].innerHTML = total.toFixed(2);
			} else {
				document.getElementsByClassName('total'+index)[0].innerHTML = '0';
			}
			actualizarTotalGeneral();
		}
		function actualizarTotalGeneral() {
			var tablaRegistros = document.getElementById('tablaRegistros');
			var filas = tablaRegistros.getElementsByTagName('tr');
			var totalGeneral = 0;
			for (var i = 1; i < filas.length-1; i++) {
				var total = parseFloat(filas[i].getElementsByClassName('total')[0].innerHTML);
				if (!isNaN(total)) {
					totalGeneral += total;
				}
			}
			document.getElementById('totalGeneral').innerHTML = totalGeneral.toFixed(2);
		}
		</script>";
	
	
	echo "<form method='post' action='materialesServitecaSave.php'>";
	$sql="SELECT codigo, nombre, numero, peso, precio, cod_estado, (SELECT vms.precio FROM ventas_materialesserviteca vms WHERE vms.cod_venta = ".$cod_venta." AND vms.cod_materialserviteca = codigo) as precio_materialserviteca, (SELECT vms.cantidad FROM ventas_materialesserviteca vms WHERE vms.cod_venta = ".$cod_venta." AND vms.cod_materialserviteca = codigo) as cantidad_materialserviteca 
			FROM $table 
			WHERE cod_estado=1 
			ORDER BY 2";
	$resp=mysql_query($sql);
	echo "<h1>$moduleNamePlural</h1>";
		
	echo "<input type='text' name='cod_venta' value='".$cod_venta."' size='10' hidden>";

	echo "<center><table class='texto' id='tablaRegistros'>";
	echo "<tr>
			<th>Codigo</th>
			<th>Nombre</th>
			<th>Numero</th>
			<th>Peso</th>
			<th>Cantidad</th>
			<th>Precio</th>
			<th>Monto</th></tr>";
	$index=0;
	$total = 0;
	while($dat=mysql_fetch_array($resp))
	{
		$index++;
		$codigo=$dat[0];
		$nombre=$dat[1];
		$numero=$dat[2];
		$peso=$dat[3];
		$precio=empty($dat[6])?$dat[4]:$dat[6];
		$cantidad=empty($dat[7])?0:$dat[7];
		$subtotal = $precio * $cantidad;
		$total += $subtotal;
		echo "<tr>
		<td>$codigo</td>
		<td>$nombre</td>
		<td>$numero</td>
		<td>$peso</td>
		<td>
			<input type='text' name='cod_materialserviteca$index' value='$codigo' size='10' hidden>
			<input type='text' name='cantidad$index' value='$cantidad' size='10' oninput='calcularTotal($index)'>
		</td>
		<td><input type='text' name='precio$index' value='$precio' size='10' oninput='calcularTotal($index)'></td>
		<td class='total total$index'>".number_format($subtotal, 2, '.', '')."</td>
		</tr>";
	}
	echo "<tr>
	<td colspan='5'></td>
	<td><b>Total:</b></td>
	<td id='totalGeneral'>".number_format($total, 2, '.', '')."</td>
	</tr>";
	echo "</table></center><br>";

	echo "<input type='submit' class='boton' value='Guardar'>";

	echo "</form>";
?>