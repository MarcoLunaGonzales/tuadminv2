<?php

require "conexionmysqli.php";
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenes.inc");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$cod_cliente = $_GET['cod_cliente'];

$rpt_territorio=$_COOKIE['global_agencia'];
$rpt_almacen=$_COOKIE['global_almacen'];
 
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
?>

<html>
    <head>
		<title>Clientes y Productos</title>
        <link  rel="icon"   href="imagenes/card.png" type="image/png" />
        <link href="assets/style.css" rel="stylesheet" />
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<script type="text/javascript" src="functionsGeneral.js"></script>

        <style type="text/css">
        </style>

	<?php
		$nombreClienteX=nombreCliente($cod_cliente);
	?>

<center>
	<h1>Precios de Productos por Cliente 
		<br> Cliente: <?= $nombreClienteX; ?></h1>
</center>

<form method="post" action="guardarProductosClientes.php">
	<input type="hidden" name="cod_cliente" id="cod_cliente" value="<?=$cod_cliente;?>">
<center>
<fieldset id="fiel" style="width:70%;border:0;">
	<table align="center" class="texto" width="70%" id="data0" border="0">
		<tr align="center">
			<th width="10%">-</th>
			<th width="20%">Codigo</th>
			<th width="40%">Producto</th>
			<th width="20%">Precio</th>
			<th width="20%">Precio Cliente</th>
		</tr>

	<?php
	$cantidad_total = 0;
	// Obtener el detalle de un registro específico
	$codigo_registro = 1; // Código del registro que deseas obtener el detalle		
	$query = "SELECT m.codigo_material, m.descripcion_material
				FROM material_apoyo m WHERE m.estado=1
				ORDER BY m.descripcion_material";
	$result = mysqli_query($enlaceCon, $query);

	if (!$result) {
		echo "Error al ejecutar la consulta: " . mysqli_error($enlaceCon);
		exit;
	}

	// Verificar si se encontraron registros
	if (mysqli_num_rows($result) > 0) {
		// Recorrer los registros
			$indice=1;
			while ($row = mysqli_fetch_assoc($result)) {
				$codigoProducto=$row['codigo_material'];
				$nombreProducto=$row['descripcion_material'];

				$precioProducto=precioVenta($codigoProducto,$globalAgencia);
				$precioCliente=precioVentaCliente($cod_cliente, $codigoProducto)

	?>
			<tr bgcolor="#FFFFFF" class="lista_registro">
				<td align="center">
					<?=$indice;?>
				</td>

				<td align="center">
					<?=$codigoProducto;?>
				</td>

				<td align="left">
					<?=$nombreProducto;?>)
				</td>

				<td align="center">
					<input id="precio_producto" name="precio_producto" type="number" value="<?=$precioProducto;?>" step="0.01" required>
				</td>

				<td align="center">
					<input id="precio_producto" name="precio_producto" type="number" value="<?=$precioCliente;?>" step="0.01" required>
				</td>

			</tr>
	<?php
			$indice++;
		}
	}
	?>
	</table>
</fieldset>
</center>


<center>
	<table border="0" class="texto">
		<tbody>
			<tr>
				<td>
					<input class="boton2" type="submit" value="Guardar Precios">
				</td>
			</tr>
		</tbody>
	</table>
</center>

</form>

</body>
</html>
