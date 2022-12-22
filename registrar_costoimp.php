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


?>

<body>
<form action='guardar_costoimp.php' method='post' name='form1'>
<h3 align="center">Registro de Item de Importacion<br><?=$nombreTerritorio;?></h3>

<table border='0' class='texto' cellspacing='0' align='center' width='60%' style='border:#ccc 1px solid;'>
<tr><th>Nombre</th></tr>
<tr>

<td>
<input type='text' class='texto' value="" id='nombre_costoimp' size='100' name='nombre_costoimp' required>
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