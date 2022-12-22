<html>
        <script type='text/javascript' language='javascript'>



function validar(f)
{   

f.submit();

}




	</script>
<?php
require("conexion.inc");
require("estilos_almacenes.inc");
require("funcion_nombres.php");

$globalCiudad=$_COOKIE["global_agencia"];
$nombreTerritorio=nombreTerritorio($globalCiudad);
$codCostoImpEditar=$_GET["codigo"];

	$consulta = "select cod_costoimp, nombre_costoimp, estado,created_by, modified_by, created_date,modified_date
	FROM costos_importacion  where  cod_costoimp=".$codCostoImpEditar;
	
	$resp = mysql_query($consulta);

	while ($dat = mysql_fetch_array($resp)) {
		
		$codCostoimp = $dat['cod_costoimp'];
		$nombreCostoimp= $dat['nombre_costoimp'];
		
	}

?>

<body>
<form action='guardar_editarCostoimp.php' method='post' name='form1'>
<input type="hidden" name="codCostoImpEditar" id="codCostoImpEditar" value="<?=$codCostoImpEditar;?>">
<h3 align="center">Editar de Item de Importacion<br><?=$nombreTerritorio;?></h3>

<table border='0' class='texto' cellspacing='0' align='center' width='60%' style='border:#ccc 1px solid;'>
<tr><th>Nombre</th></tr>

<tr>
<td>
<input type='text' class='texto'  id='nombre_costoimp' size='100' name='nombre_costoimp' value='<?=$nombreCostoimp;?>' required>
</td>
</tr>
</table>


<?php
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_costosimp.php\"'>
</div>";
?>




</form>
</body>