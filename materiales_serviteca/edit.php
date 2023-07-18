<?php

require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

$sql=mysqli_query($enlaceCon,"select nombre, numero, peso, precio from $table where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$numero=$dat[1];
$peso=$dat[2];
$precio=$dat[3];

echo "<form action='$urlSaveEdit' method='post'>";

echo "<h1>Editar $moduleNameSingular</h1>";

echo "<center>
<table class='texto'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='left'><input type='text' class='texto' name='nombre' value='$nombre' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Numero</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='numero' size='30' value='$numero' required>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Peso</th>";
echo "<td align='left'>
	<input type='number' step='0.01' class='texto' name='peso' size='30' value='$peso'required>
</td>";
echo "</tr>";


echo "<tr><th align='left'>Precio</th>";
echo "<td align='left'>
	<input type='number' step='0.01' class='texto' name='precio' size='30'  value='$precio' required>
	</td>";
echo "</tr>";


echo "</table>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_grupos.php\"'>
</div>";

echo "</form>";
?>