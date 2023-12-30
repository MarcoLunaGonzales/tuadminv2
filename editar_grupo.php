<?php

require("conexion.inc");
require("estilos.inc");

$sql=mysqli_query($enlaceCon,"select nombre_grupo, cod_tipomaterial from grupos where cod_grupo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombreGrupo	 = $dat[0];
$codTipoMaterial = $dat[1];

echo "<form action='guarda_editargrupo.php' method='post'>";

echo "<h1>Editar Grupo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre de Grupo</th>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='center'><input type='text' class='form-control' name='nombre_grupo' value='$nombreGrupo' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Estado</th>";
echo "<td align='center'>
<select name='estado' class='form-control'>
	<option value='1'>Activo</option>
	<option value='2'>Inactivo</option>
</select></td></tr>";

echo "<tr><th align='left'>Tipo Material</th>";
echo "<td align='center'>";
	$sql1="SELECT cod_tipomaterial, nombre_tipomaterial FROM tipos_material ORDER BY cod_tipomaterial ASC";
	$resp1=mysqli_query($enlaceCon,$sql1);
	echo "<select class='selectpicker form-control' name='cod_tipomaterial' data-style='btn btn-rose' data-live-search='true' id='cod_tipomaterial'>";
	while($dat=mysqli_fetch_array($resp1)){
		$codigo=$dat[0];
		$nombre=$dat[1];
		$selected = $codigo == $codTipoMaterial ? 'selected' : '';
		echo "<option value='$codigo' $selected>$nombre</option>";
	}
	echo "</select>";
echo "</td></tr>";

echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
</div>";

echo "</form>";
?>