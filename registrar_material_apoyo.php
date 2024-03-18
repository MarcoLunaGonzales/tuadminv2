<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');

echo "<form enctype='multipart/form-data' action='guarda_material_apoyo.php' method='post' name='form1'>";

echo "<h1>Adicionar Producto</h1>";


echo "<center><table class='texto'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' required readonly>
	</td></tr>";
	
echo "<tr><th align='left'>Marca</th>";
$sql1="select pl.cod_linea_proveedor, CONCAT(p.nombre_proveedor,' - ',pl.nombre_linea_proveedor) from proveedores p, proveedores_lineas pl 
where p.cod_proveedor=pl.cod_proveedor and pl.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='codLinea' id='codLinea' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
		<option value=''></option>";
		while($dat1=mysqli_fetch_array($resp1))
		{	$codLinea=$dat1[0];
		$nombreLinea=$dat1[1];
		echo "<option value='$codLinea'>$nombreLinea</option>";
		}
		echo "</select>
</td>";
echo "</tr>";

echo "<tr hidden><th>Tipo</th>";
$sql1="select f.cod_tipomaterial, f.nombre_tipomaterial from tipos_material f where f.cod_tipomaterial in (1,2) order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>
				<option value=''>-</option>";
				
			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipo=$dat1[0];
				$nombreTipo=$dat1[1];
				echo "<option value='$codTipo'>$nombreTipo</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.cod_grupo, f.nombre_grupo from grupos f 
where f.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_grupo' id='cod_grupo' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				echo "<option value='$codGrupo'>$nombreGrupo</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Tipo Aro</th>";
$sql1="SELECT ta.codigo, ta.nombre, ta.abreviatura
		FROM tipos_aro ta
		WHERE ta.estado = 1
		ORDER BY ta.codigo ASC";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_tipoaro' id='cod_tipoaro' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo = $dat1[0];
				$nombre = $dat1[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

// echo "<tr><th align='left'>Descripcion</th>";
// echo "<td align='left'>
// 	<input type='text' class='texto' name='observaciones' id='observaciones' size='80' style='text-transform:uppercase;'>
// 	</td>";

// echo "<tr><th>Unidad de Manejo</th>";
// $sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
// $resp1=mysqli_query($enlaceCon,$sql1);
// echo "<td>
// 			<select name='cod_unidad' id='cod_unidad' required>
// 			<option value=''></option>";
// 			while($dat1=mysqli_fetch_array($resp1))
// 			{	$codUnidad=$dat1[0];
// 				$nombreUnidad=$dat1[1];
// 				$abreviatura=$dat1[2];
// 				echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";
// 			}
// 			echo "</select>
// </td>";
// echo "</tr>";



echo "<tr><th align='left'>Medida</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='medida' id='medida'>
	</td></tr>";

echo "<tr><th align='left'>Modelo</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='modelo' id='modelo'>
	</td></tr>";

echo "<tr><th align='left'>Capacidad de Carga y <br> Código de Velocidad</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='capacidad_carga_velocidad' id='capacidad_carga_velocidad'>
	</td></tr>";
	
echo "<tr><th>País de Origen</th>";
$sql1="SELECT p.codigo, p.nombre, p.abreviatura from pais_procedencia p order by 1;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_pais_procedencia' id='cod_pais_procedencia' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codPaisProcedencia=$dat1[0];
				$nombrePaisProcedencia=$dat1[1];
				$abreviaturaPaisProcedencia=$dat1[2];
				echo "<option value='$codPaisProcedencia' data-abrev='$abreviaturaPaisProcedencia'>$nombrePaisProcedencia $abreviaturaPaisProcedencia</option>";
			}
			echo "</select>
</td>";
echo "</tr>";



echo "<tr><th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' type='file' class='boton2'/> </td>";
echo "</tr>";

echo "<tr><th>Stock Mínimo</th>";
echo "<td><input type='number' class='texto' name='stock_minimo' id='stock_minimo'></td>";
echo "</tr>";

echo "<tr><th align='left'>Codigo Barras</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='codigo_barras' id='codigo_barras'>
	</td></tr>";

// echo "<tr><th align='left'>Codigo Interno</th>";
// echo "<td align='left'>
// 	<input type='text' class='texto' name='codigo_interno' id='codigo_interno' required='true'>
// 	</td></tr>";

// echo "<tr><th align='left'>Costo</th>";
// echo "<td align='left'>
// 	<input type='number' class='texto' name='costo_producto' id='costo_producto' step='0.01'>
// 	</td></tr>";

// echo "<tr><th align='left'>Precio de Venta</th>";
// echo "<td align='left'>
// 	<input type='number' class='texto' name='precio_producto' id='precio_producto' step='0.01'>
// 	</td>";

?>
	<tr>
		<td colspan='2'>
			<table width='100%'>
				<thead>
					<tr>
						<th colspan="5">Detalles de Precios</th>
					</tr>
					<tr>
						<th style="text-align: left;">Descripción</th>
						<th style="text-align: left;">Tipo Venta</th>
						<th style="text-align: left;">Cantidad Inicio</th>
						<th style="text-align: left;">Cantidad Final</th>
						<th style="text-align: left;">Precio</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql1="SELECT 
									tp.codigo, 
									tp.nombre, 
									tp.abreviatura, 
									tp.cantidad_inicio, 
									tp.cantidad_final,
									tp.cod_tipoventa,
									CASE
										WHEN tp.cod_tipoventa = 0 THEN 'CONTADO/CREDITO'
										ELSE tv.nombre_tipoventa
									END AS nombre_tipoventa
								FROM 
									tipos_precio tp 
								LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = tp.cod_tipoventa
								WHERE tp.estado = 1
								ORDER BY 
									tp.codigo";
						$resp1=mysqli_query($enlaceCon,$sql1);
						$index = 0;
						while($dat1=mysqli_fetch_array($resp1)){
							$index++;
					?>
					<tr>
						<input type='hidden' name='cod_precio[]' value="<?= $dat1['codigo'] ?>"></td>
						<input type='hidden' name='cod_tipoventa[]' value="<?= $dat1['cod_tipoventa'] ?>"></td>
						<td><?= $dat1['nombre'] ?></td>
						<td><?= $dat1['nombre_tipoventa'] ?></td>
						<td>
							<?= $dat1['cantidad_inicio'] ?>
							<input type='hidden' name='cantidad_inicio[]' step='0.02' value="<?= $dat1['cantidad_inicio'] ?>"></td>
						<td>
							<?= $dat1['cantidad_final'] ?>
							<input type='hidden' name='cantidad_final[]' step='0.02' value="<?= $dat1['cantidad_final'] ?>"></td>
						<td><input type='number' name='precio[]' step='0.02'></td>
					</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</td>
	</tr>
<?php

echo "</tr>";

?>	

	
<?php
echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";
?>


<script>
	$(document).ready(function() {
		// Detectar cambios en los campos mediante keyup
		$('#medida, #modelo, #capacidad_carga_velocidad').on('keyup', function() {
			actualizarMaterial();
		});

		// Detectar cambios en el select mediante change
		$('#cod_pais_procedencia, #codLinea, #cod_grupo, #cod_tipoaro').on('change', function() {
			actualizarMaterial();
		});

		/* Variable de configuración - Arma Nombre */
		var config_nombre = <?=obtenerValorConfiguracion(9) ?? 1?>;
		
		function actualizarMaterial() {
			// Obtener los valores de los campos
			var marca 	  = $('#codLinea option:selected').text();
			var grupo 	  = $('#cod_grupo option:selected').text();
			var tipoAro   = $('#cod_tipoaro option:selected').text();
			var medida 	  = $('#medida').val() || '';
			var modelo	  = $('#modelo').val() || '';
			var capacidad = $('#capacidad_carga_velocidad').val() || '';
			var pais 	  = $('#cod_pais_procedencia option:selected').data('abrev') || ''; // Obtener el texto seleccionado del select

			// Concatenar los valores
			// var nuevoMaterial = medida + ' ' + modelo + ' ' + capacidad + ' ' + pais;

			var nuevoMaterial = '';
			switch (config_nombre) {
				case 1:
					nuevoMaterial = medida + ' ' + modelo + ' ' + capacidad + ' ' + pais;
					break;
				case 2:
					nuevoMaterial = grupo + ' ' + medida + ' ' + marca;
					break;
				default:
					break;
			}

			// Actualizar el valor del campo "material"
			$('[name="material"]').val(nuevoMaterial);
		}
	});
</script>