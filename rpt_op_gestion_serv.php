<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Servicio</title>
</head>
<body>
	<?php
	require("conexion.inc");
	require("estilos_almacenes.inc");

	$globalCiudad=$_COOKIE["global_agencia"];

	$fechaHoy=date("d/m/Y");
	?>
    <h1>Listado de Gestión de Servicios</h1>
	<div class="divBotones">
		<input type="button" value="Registrar" name="adicionar" class="boton" id="registrar">
	</div> <br>
	<center>
		<table cellspacing="0" class="texto">
			<tr>
				<th>Nro. Correlativo</th>
				<th>Proveedor</th>
				<th>Fecha</th>
				<th>Observaciones</th>
				<th>Nro. Documento</th>
				<th>Acciones</th>
			</tr>
			
			<?php 
				$consulta="SELECT oc.codigo, 
									oc.nro_correlativo, 
									p.nombre_proveedor as proveedor, 
									oc.fecha, 
									oc.observaciones, 
									oc.cod_estado, 
									oc.nro_documento,
									oc.monto_cancelado
							FROM ordenes_compra oc
							LEFT JOIN proveedores p ON p.cod_proveedor = oc.cod_proveedor
							WHERE oc.cod_estado = 1
							ORDER BY oc.codigo DESC";
				$rs = mysqli_query($enlaceCon,$consulta);
				$cont = 0;
				while($reg=mysqli_fetch_array($rs)){
					$cont++;
			?>
				<tr>
					<td><?= $reg["nro_correlativo"]; ?></td>
					<td><?= $reg["proveedor"]; ?></td>
					<td><?= $reg["fecha"]; ?></td>
					<td><?= $reg["observaciones"]; ?></td>
					<td><?= $reg["nro_documento"]; ?></td>
					<td>
						<button class="form_editar" data-codigo="<?= $reg["codigo"]; ?>" style="border: none; background: none; padding: 0; cursor:pointer;" type="button"><img src="imagenes/edit.png" width="30" title="Editar"></button>
						<?php if($reg["monto_cancelado"] == 0){ ?>
						<button class="form_eliminar" data-codigo="<?= $reg["codigo"]; ?>" style="border: none; background: none; padding: 0; cursor:pointer;" type="button"><img src="imagenes/no2.png" width="30" title="Eliminar"></button>
						<?php } ?>
					</td>
				</tr>
				
			<?php 
				}
				echo "<input type='hidden' id='idtotal' value='$cont' >";
			?>
		</table>
	</center>

    <script>
		// Ir al formulario de registro
		$("#registrar").click(function() {
			window.location.href = "rpt_op_gestion_serv_registrar.php";
		});
		/**
		 * Formulario de Edición
		 */
		$('.form_editar').on('click', function(){
			let codigo = $(this).data('codigo') || 0;
			window.location.href = 'rpt_op_gestion_serv_editar.php?codigo='+codigo;
		});
		/**
		 * Eliminar Registros seleccionados
		 */
		$('.form_eliminar').on('click', function() {
			var codigo = $(this).data('codigo');
			
			Swal.fire({
				title: '¿Estás seguro?',
				text: 'Esta acción eliminará el registro.',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Continuar',
				cancelButtonText: 'Cancelar',
			}).then((result) => {
				if (result.value) {
					var formData = new FormData();
					formData.append("codigo", codigo);

					$.ajax({
						type: "POST",
						url: "rpt_op_gestion_serv_eliminar.php",
						data: formData,
						contentType: false,
						processData: false,
						dataType: 'json',
						success: function (response) {
							if (response.status) {
								Swal.fire('Eliminado', response.message, 'success').then(() => {
									window.location.href = "rpt_op_gestion_serv.php";
								});
							} else {
								Swal.fire('Error', 'Ocurrió un error en el registro', 'error');
							}
						},
						error: function () {
							Swal.fire('Error', 'Error en la solicitud AJAX', 'error');
						}
					});
				}
			});
		});

    </script>
</body>
</html>
