<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
        <script type='text/javascript' language='javascript'>
        function cambiar_vista(ruta)
        {
            location.href=ruta;
        }
        </script>
		
		<script>
			$(document).ready(function () {
				/**
				 * Arma JSON para almacenar
				 */
				function armarJSON() {
					// Cabecera
					var codigo 	  		  = $('#edit_cod_despacho').val();
					var codigoFuncionario = $("#funcionario").val();
					var observaciones 	  = $("#observaciones").val();

					// Detalle
					var items = [];
					$("#items_detalle #items tr").each(function () {
						var codigo_material 	= $(this).find("input[name='codigo_material']").val();
						var cantidad_entrega 	= $(this).find("input[name='cantidad_entrega']").val();
						var cantidad_venta 		= $(this).find("input[name='cantidad_venta']").val();
						var monto_venta 		= $(this).find("input[name='monto_venta']").val();
						items.push({
							codigo_material: codigo_material,
							cantidad_entrega: cantidad_entrega,
							cantidad_venta: cantidad_venta,
							monto_venta: monto_venta
						});
					});

					var data = {
						codigo: codigo,
						codigo_funcionario: codigoFuncionario,
						observaciones: observaciones,
						items: items
					};
					// Imprimir el JSON en la consola (puedes hacer lo que quieras con él aquí)
					console.log(data);
					return data;
				}
				/**
				 * guardar datos AJAX
				 **/ 
				$("#form_guardar").click(function () {
					console.log(armarJSON());
					// return true;
					$.ajax({
						type: "POST",
						url: "guarda_despachosalida.php",
						data: { 
							data: armarJSON() 
						},
						success: function (resp) {
							let response = JSON.parse(resp);
							// console.log(response);
							if (response.status) {
								// Si la respuesta indica éxito, mostrar el mensaje de éxito en un alert
								alert("Éxito: " + response.message);
								location.href="navegador_despachoalmacenes.php";
							} else {
								// Si la respuesta indica un error, mostrar el mensaje de error en un alert
								alert("Error: " + response.message);
							}
						}
					});
				});
				/**
				 * CANTIDAD VENTA - Obtiene total de Salida - Venta = Devolución
				 */
				$(".cantidad-venta").keyup(function () {
					var fila = $(this).closest(".item-row");
					var cantidadEntrega = parseFloat(fila.find(".cantidad-entrega").val() || 0);
					var cantidadVenta   = parseFloat($(this).val() || 0);
					var montoVenta      = parseFloat(fila.find(".monto-venta").val() || 0);
					
					let cantidadDevolucion = 0;
					// Devolución
					if (!isNaN(cantidadEntrega) && !isNaN(cantidadVenta)) {
						cantidadDevolucion = cantidadEntrega - cantidadVenta;
					}
					fila.find(".cantidad-devolucion").val(cantidadDevolucion.toFixed(2));
					// Precio Producto
					obtienePrecioProducto(fila, montoVenta);
				});
				/**
				 * TOTAL VENTA - Obtiene Precio Producto
				 */
				$(".monto-venta").keyup(function () {
					var fila = $(this).closest(".item-row");
					// Precio Producto
					obtienePrecioProducto(fila, $(this).val());
				});
				/* Función obtiene Total Producto */
				function obtienePrecioProducto(fila, montoVenta){
					var cantidadVenta = parseFloat(fila.find(".cantidad-venta").val() || 0);
					var montoVenta    = parseFloat(montoVenta || 0);
					
					let precioProducto = 0;
					if (!isNaN(montoVenta) && cantidadVenta !== 0) {
						precioProducto = montoVenta / cantidadVenta;
						fila.find(".precio-producto").val(precioProducto.toFixed(2));
					}
					fila.find(".precio-producto").val(precioProducto.toFixed(2));
					// Total
					calcularTotales();
				}
				/**
				 * Función para calcular la suma total de "monto_venta" y "precio_producto"
				 */
				function calcularTotales() {
					var totalMontoVenta = 0;
					var totalPrecioProducto = 0;

					$(".item-row").each(function () {
						var montoVenta = parseFloat($(this).find(".monto-venta").val()) || 0;
						var precioProducto = parseFloat($(this).find(".precio-producto").val()) || 0;

						totalMontoVenta += montoVenta;
						totalPrecioProducto += precioProducto;
					});

					// Actualizar los campos de "total_monto_venta" y "total_precio_producto"
					$(".total-monto-venta").val(totalMontoVenta.toFixed(2));
					$(".total-precio-producto").val(totalPrecioProducto.toFixed(2));
				}
				calcularTotales();
			});
		</script>

    </head>

    <?php
require("conexion.inc");
require("estilos_almacenes.inc");
require_once("funciones.php");
// codigo ciudad
$global_agencia = $_COOKIE["global_agencia"];
$globalAlmacen = $_COOKIE['global_almacen'];

/***************************
 * En caso de actualización
 ***************************/
$edit_cod_despacho 	   = empty($_GET['codigo']) ? '' : $_GET['codigo'];
$edit_tipo 	   		   = empty($edit_cod_despacho) ? 0 : 1; // Tipo(0:Registro, 1:Modificacion, 2:Vista)
$edit_cod_funcionario  = '';
$edit_observacion 	   = '';
$edit_fecha_entrega    = '';
// Editar
if($edit_tipo == 1){
	$consulta = "SELECT dp.cod_funcionario, DATE_FORMAT(dp.fecha_entrega,'%Y-%m-%d') as fecha_entrega, DATE_FORMAT(dp.fecha_recepcion,'%Y-%m-%d') as fecha_recepcion, dp.observaciones
				FROM despacho_productos dp
				WHERE dp.codigo = '$edit_cod_despacho'";
	$result = mysqli_query($enlaceCon, $consulta);
	if ($row = mysqli_fetch_assoc($result)) { // Verificación de Registro encontrado
		$edit_cod_funcionario  = $row['cod_funcionario'];
		$edit_fecha_entrega    = $row['fecha_entrega'];
		$edit_fecha_recepcion  = $row['fecha_recepcion'];
		$edit_observacion 	   = $row['observaciones'];
		$edit_tipo 	   		   = (!empty($edit_fecha_entrega) && !empty($edit_fecha_recepcion)) ? 2 : 1; // Tipo(0:Registro, 1:Modificacion, 2:Vista)
	}
}
// Titulo
if($edit_tipo == 0){
	$tipo_titulo = "Registro";
}else if($edit_tipo == 1){
	$tipo_titulo = "Recepción";
}else{
	$tipo_titulo = "Detalle";
}
?>
<style>
    /* Estilo para la tabla */
	.table-container {
		overflow-x: auto;
	}
	.table {
		width: 100%;
		border-collapse: collapse;
		margin-bottom: 20px;
	}
	/* Estilos adicionales para tus celdas de tabla si es necesario */
	.table th, .table td {
		padding: 8px;
		text-align: left;
		/* Otros estilos aquí */
	}

    .table th,
    .table td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ccc;
    }

    .table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    /* Estilo para los inputs dentro de la tabla */
    .table input[type="text"] {
        width: 100%;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
	/* Estilo genérico para elementos input, textarea y select */
	.form-control {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 6px 12px;
        width: 100%;
    }

    /* Estilo para textarea (si es necesario modificarlo) */
    .form-control.textarea {
        resize: vertical;
    }
	/* Aplica un estilo personalizado a los inputs de tipo number */
	input[type="number"] {
		font-size: 17px;
		color: red;
		padding: 5px;
		border: 1px solid #ccc;
		border-radius: 4px;
		background-color: #f9f9f9;
	}

	/* Aplica un estilo personalizado a los inputs de tipo number en solo lectura */
	input[type="number"]:read-only {
		background-color: #eee;
		color: #332F2E;
	}
</style>

<form method="post" action="procesar_formulario.php">

    <h1>Despacho de Productos - <?=$tipo_titulo?></h1>

	<table border="0" class="table" cellspacing="0" align="center" style="border:#ccc 1px solid;">
		<tbody>
			<tr>
				<input type="hidden" id="edit_cod_despacho" value="<?=$edit_cod_despacho;?>">
				<th>Vendedor</th>
				<td align="center">
					<select class="form-control selectpicker" data-style="btn btn-success" name="funcionario" id="funcionario" data-live-search="true" <?=($edit_tipo == 0 ? '':'disabled')?>>
						<?php
						/*Esta Bandera es para la validacion de stocks*/
						$busca_items = obtenerValorConfiguracion(15);

						$consulta = "SELECT f.codigo_funcionario, CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as funcionario
									FROM funcionarios f
									LEFT JOIn funcionarios_agencias fa ON fa.codigo_funcionario = f.codigo_funcionario
									WHERE fa.cod_ciudad = '$global_agencia'";
						$result = mysqli_query($enlaceCon, $consulta);
						while ($row = mysqli_fetch_array($result)) {
							$codigo = $row[0];
							$nombre = $row[1];
						?>
							<option value="<?= $codigo ?>" <?= $codigo == $edit_cod_funcionario ? 'selected' : '' ?>><?= $nombre ?></option>
						<?php
						}
						?>
					</select>
				</td>
				<th>Observación</th>
				<td>
					<textarea class="form-control" name="observaciones" id="observaciones" <?=($edit_tipo == 2?'readonly':'')?>><?= $edit_observacion; ?></textarea>
				</td>
				<th>Fecha</th>
				<td>
					<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo (empty($edit_fecha_entrega) ? date('Y-m-d') : $edit_fecha_entrega); ?>" disabled>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="table-container">
		<!-- Lista de items -->
		<table class="table table-bordered" id="items_detalle" align="center">
			<thead>
				<tr>
					<th>#</th>
					<th>Producto</th>
					<th>Stock</th>
					<th>Entregado</th>
					
					<?php if($edit_tipo != 0){ ?>
					<!-- Editar -->
					<th>Vendido</th>
					<th>Devolución</th>
					<th>Total Venta</th>
					<th>Precio Producto</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody id="items">
				<?php
				/*Esta Bandera es para la validacion de stocks*/
				$busca_items = obtenerValorConfiguracion(15);

				if($edit_tipo == 0){
					// Nuevo
					$consulta = "SELECT ma.codigo_material, ma.descripcion_material, 0 AS cantidad_entrega, 0 AS cantidad_venta, 0 AS cantidad_devolucion, 0 AS monto_venta, 0 AS precio_producto
								FROM material_apoyo ma
								WHERE ma.estado=1";
				}else{
					// Editar
					$consulta = "SELECT ma.codigo_material, ma.descripcion_material, dpd.cantidad_entrega, dpd.cantidad_venta, dpd.cantidad_devolucion, dpd.monto_venta, dpd.precio_producto
								FROM despacho_productosdetalle dpd
								LEFT JOIN material_apoyo ma ON ma.codigo_material = dpd.cod_material
								WHERE dpd.cod_despachoproducto = '$edit_cod_despacho'";
				}
				//echo $consulta;
				$result = mysqli_query($enlaceCon, $consulta);
				$index = 1;
				while ($row = mysqli_fetch_array($result)) {
					// Nuevo
					$item_codigo_material 	   = $row[0];
					$item_descripcion_material = $row[1];
					// Editar
					$item_cantidad_entrega	  = $row[2];
					$item_cantidad_venta	  = $row[3];
					$item_cantidad_devolucion = $row[4];
					$item_monto_venta 		  = $row[5];
					$item_precio_producto 	  = $row[6];

					$fechaActual=date("Y-m-d");
					$stockProducto=stockProductoAFecha($globalAlmacen,$item_codigo_material,$fechaActual);

				?>
					<tr class="item-row">
						<td><input type="hidden" name="codigo_material" value="<?= $item_codigo_material ?>"><?= $index++ ?></td>
						<td><?= $item_descripcion_material; ?></td>
						<td><?= $stockProducto; ?></td>
						<td><input type="number" class="cantidad-entrega" name="cantidad_entrega" value="<?= $item_cantidad_entrega ?>" <?=($edit_tipo == 0?'':'readonly')?>></td>
						<!-- Editar -->
						<td <?=($edit_tipo == 0?'hidden':'')?>><input type="number" class="cantidad-venta" name="cantidad_venta" value="<?= $item_cantidad_venta ?>" <?=($edit_tipo == 2?'readonly':'')?>></td>
						<td <?=($edit_tipo == 0?'hidden':'')?>><input type="number" class="cantidad-devolucion" name="cantidad_devolucion" value="<?= $item_cantidad_devolucion ?>" readonly></td>
						<td <?=($edit_tipo == 0?'hidden':'')?>><input type="number" class="monto-venta" name="monto_venta" value="<?= $item_monto_venta ?>" <?=($edit_tipo == 2?'readonly':'')?>></td>
						<td <?=($edit_tipo == 0?'hidden':'')?>><input type="number" class="precio-producto" name="precio_producto" value="<?= $item_precio_producto ?>" readonly></td>
					</tr>
				<?php
				}
				?>
			</tbody>
			<tfooter>
				<!-- Editar (Solo aparece el total en la edición) -->
				<?php if($edit_tipo != 0){ ?>
					<tr class="item-row">
						<th colspan="6" style="text-align: right;"><b>Total:</b></th>
						<th><input type="number" class="total-monto-venta" name="total_monto_venta" value="0" readonly></th>
						<th><input type="number" class="total-precio-producto" name="total_precio_producto" value="0" readonly></th>
					</tr>
				<?php } ?>
			</tfooter>
		</table>
	</div>
    <!-- Botón para enviar el formulario -->
    <div class="divBotones">
		<?php if($edit_tipo < 2){ ?>
			<input type="button" value="Guardar Despacho" class="boton" id="form_guardar">
		<?php } ?>
		<input class="boton2" type="button" value="Volver" onclick="javascript:cambiar_vista('navegador_despachoalmacenes.php');">
    </div>
</form>

</body>
</html>