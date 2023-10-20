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
									oc.nro_documento
							FROM ordenes_compra oc
							LEFT JOIN proveedores p ON p.cod_proveedor = oc.cod_proveedor
							ORDER BY oc.codigo DESC";
				$rs = mysqli_query($enlaceCon,$consulta);
				while($reg=mysqli_fetch_array($rs)){
			?>
				<tr>
					<td><?= $reg["nro_correlativo"]; ?></td>
					<td><?= $reg["proveedor"]; ?></td>
					<td><?= $reg["fecha"]; ?></td>
					<td><?= $reg["observaciones"]; ?></td>
					<td><?= $reg["nro_documento"]; ?></td>
					<td>
						<!-- <button type="button"><img src="imagenes/edit.png" width="30" title="Editar"></button>
						<button type="button"><img src="imagenes/no2.png" width="30" title="Eliminar"></button> -->
					</td>
				</tr>
				
			<?php 
				}
			?>
		</table>
	</center>

    <script>
		// Ir al formulario de registro
		$("#registrar").click(function() {
			window.location.href = "rpt_op_gestion_serv_registrar.php";
		});
    </script>
</body>
</html>
