<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$sql=mysqli_query($enlaceCon,"select nombre, abreviatura from $table where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$abreviatura=$dat[1];

echo "<form action='$urlSaveEdit' method='post'>";

echo "<h1>Editar $moduleNameSingular</h1>";

echo "<center>
<table class='texto'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='left'><input type='text' class='texto' name='nombre' value='$nombre' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'><input type='text' class='texto' name='abreviatura' value='$abreviatura' size='20' required></td></tr>";

echo "</table>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"list.php\"'>
</div>";

echo "</form>";
?>