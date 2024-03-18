<script language='Javascript'>
	

</script>

<head>

</head>
<?php
require("conexion.inc");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="SELECT m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_grupo, m.cod_tipomaterial, 
	m.observaciones, m.cod_unidad, m.modelo, m.medida, m.capacidad_carga_velocidad, m.cod_pais_procedencia, m.stock_minimo, m.cod_tipoaro, m.cod_tipopliegue
	FROM material_apoyo m 
	WHERE m.codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$codLineaX=$datEdit[3];
	$codGrupoX=$datEdit[4];
	$codTipoX=$datEdit[5];
	$observacionesX=$datEdit[6];
	$codUnidadX=$datEdit[7];
	
	$modeloX 					= $datEdit[8];
	$medidaX 					= $datEdit[9];
	$capacidad_carga_velocidadX = $datEdit[10];
	$cod_pais_procedenciaX 		= $datEdit[11];
	$stockMinimo 				= $datEdit[12];
	$cod_tipoaro 				= $datEdit[13];
	$cod_tipopliegueX 			= $datEdit[14];
}

$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=0 and p.`codigo_material`='$codProducto' and p.cod_ciudad='$globalAgencia'";
$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
$numFilas=mysqli_num_rows($respPrecio);
if($numFilas>=1){
	$costo=mysqli_result($respPrecio,0,0);
	$costo=redondear2($costo);
}else{
	$costo=0;
	$costo=redondear2($costo);
}
$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`='$codProducto' and p.cod_ciudad='$globalAgencia'";
$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
$numFilas=mysqli_num_rows($respPrecio);
if($numFilas>=1){
	$precio1=mysqli_result($respPrecio,0,0);
	$precio1=redondear2($precio1);
}else{
	$precio1=0;
	$precio1=redondear2($precio1);
}

echo "<form action='guarda_editarproducto.php' method='post' name='form1'>";

echo "<h1>Editar Producto</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProductoX' readonly>
	</td>";
	
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
			if($codLinea==$codLineaX){
				echo "<option value='$codLinea' selected>$nombreLinea</option>";
			}else{
				echo "<option value='$codLinea'>$nombreLinea</option>";
			}
		}
		echo "</select>
</td>";
echo "</tr>";

echo "<tr hidden><th>Tipo</th>";
$sql1="select e.cod_tipomaterial, e.nombre_tipomaterial from tipos_material e order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				if($codigo==$codGrupoX){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.cod_grupo, f.nombre_grupo from grupos f  where f.estado=1 order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_grupo' id='cod_grupo' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo=$dat1[0];
				$nombre=$dat1[1];
				if($codigo==$codGrupoX){
					echo "<option value='$codigo' selected>$nombre</option>";
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
			}
			echo "</select>
	</div>
	</div>
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
				$select = $cod_tipoaro == $codigo ? 'selected' : '';
				echo "<option value='$codigo' $select>$nombre</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

// echo "<tr><th align='left'>Descripcion</th>";
// echo "<td align='left'>
// 	<input type='text' class='texto' name='observaciones' id='observaciones' size='80' style='text-transform:uppercase;' value='$observacionesX'>
// 	</td>";


// echo "<tr><th>Unidad de Manejo</th>";
// $sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
// $resp1=mysqli_query($enlaceCon,$sql1);
// echo "<td>
// 			<select name='cod_unidad' id='cod_unidad' required>
// 			<option value=''></option>";
// 			while($dat1=mysqli_fetch_array($resp1))
// 			{	$codigo=$dat1[0];
// 				$nombre=$dat1[1];
// 				$abreviatura=$dat1[2];
// 				if($codigo==$codUnidadX){
// 					echo "<option value='$codigo' selected>$nombre $abreviatura</option>";
// 				}else{
// 					echo "<option value='$codigo'>$nombre $abreviatura</option>";
// 				}
// 			}
// 			echo "</select>
// </td>";
// echo "</tr>";


// echo "<tr><th align='left'>Costo</th>";
// echo "<td align='left'>
// 	<input type='number' class='texto' name='costo_producto' id='costo_producto' value='$costo' step='0.1'>
// 	</td></tr>";

// echo "<tr><th align='left'>Precio de Venta</th>";
// echo "<td align='left'>
// 	<input type='number' class='texto' name='precio_producto' id='precio_producto' value='$precio1' step='0.1'>
// 	</td></tr>";


echo "<tr><th align='left'>Medida</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='medida' id='medida' value='$medidaX'>
	</td></tr>";

echo "<tr><th align='left'>Modelo</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='modelo' id='modelo' value='$modeloX'>
	</td></tr>";

echo "<tr><th align='left'>Capacidad de Carga y <br> Código de Velocidad</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='capacidad_carga_velocidad' id='capacidad_carga_velocidad' value='$capacidad_carga_velocidadX'>
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
				$select = $codPaisProcedencia == $cod_pais_procedenciaX ? 'selected' : '';
				echo "<option value='$codPaisProcedencia' $select data-abrev='$abreviaturaPaisProcedencia'>$nombrePaisProcedencia $abreviaturaPaisProcedencia</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th>Stock Mínimo</th>";
echo "<td><input type='number' class='texto' name='stock_minimo' id='stock_minimo' value='$stockMinimo'></td>";
echo "</tr>";

echo "<tr><th>Tipo Pliegue</th>";
$sql1="SELECT t.codigo, t.nombre, t.abreviatura
		FROM tipos_pliegue t
		WHERE t.estado = 1
		ORDER BY t.codigo ASC";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
		<select name='cod_tipopliegue' id='cod_tipopliegue' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codigo = $dat1[0];
				$nombre = $dat1[1];
				$select = $cod_tipopliegueX == $codigo ? 'selected' : '';
				echo "<option value='$codigo' $select>$nombre</option>";
			}
			echo "</select>
</td>";
echo "</tr>";

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
									END AS nombre_tipoventa,
									COALESCE(p.precio, 0) as precio
								FROM 
									tipos_precio tp 
								LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = tp.cod_tipoventa
								LEFT JOIN precios p ON p.cod_precio = tp.codigo AND p.codigo_material = '$codProducto'
								WHERE tp.estado = 1
								ORDER BY tp.codigo";
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
						<td><input type='number' name='precio[]' step='0.02' value="<?= $dat1['precio'] ?>"></td>
					</tr>
					<?php
						}
					?>
				</tbody>
			</table>
		</td>
	</tr>
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
		$('#cod_pais_procedencia').on('change', function() {
			actualizarMaterial();
		});

		function actualizarMaterial() {
			// Obtener los valores de los campos
			var medida = $('#medida').val();
			var modelo = $('#modelo').val();
			var capacidad = $('#capacidad_carga_velocidad').val();
			// var pais = $('#cod_pais_procedencia option:selected').text(); // Obtener el texto seleccionado del select
			var pais = $('#cod_pais_procedencia option:selected').data('abrev'); // Obtener el texto seleccionado del select

			// Concatenar los valores
			var nuevoMaterial = medida + ' ' + modelo + ' ' + capacidad + ' ' + pais;

			// Actualizar el valor del campo "material"
			$('[name="material"]').val(nuevoMaterial);
		}
	});
</script>