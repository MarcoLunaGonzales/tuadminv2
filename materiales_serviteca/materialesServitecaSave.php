<?php
require("../conexion.inc");
require("../estilos2.inc");
require("configModule.php");

// Codigo de Venta
$cod_venta = $_POST['cod_venta'];
// Obtener el número total de campos de cantidad
$totalCampos = count($_POST) - 1; // Restar 1 para excluir el botón de enviar

// Inicializar la variable que cuenta los campos con valores mayores que cero
$camposConValor = 0;

// Limpiar
$sql="DELETE FROM ventas_materialesserviteca WHERE cod_venta = '$cod_venta'";
$sql_inserta=mysql_query($sql);

// Recorrer los campos de cantidad y guardar los valores que sean mayores que cero
for ($i = 1; $i <= $totalCampos; $i++) {
  $cantidad = $_POST["cantidad$i"];
  if ($cantidad > 0) {
    $camposConValor++;
    $codMaterial = $_POST["cod_materialserviteca$i"];
    $precio = $_POST["precio$i"];
    // Aquí puedes insertar los valores en la base de datos
    $sql="insert into ventas_materialesserviteca (cod_venta, cod_materialserviteca, cantidad, precio, monto_total) values('$cod_venta','$codMaterial','$cantidad','$precio','".($cantidad*$precio)."')";
    $sql_inserta=mysql_query($sql);
  }
}

//$sql="insert into $table (nombre, numero, peso, precio, cod_estado) values('$nombre','$numero','$peso','$precio','1')";
//echo $sql;
//$sql_inserta=mysql_query($sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='../navegadorVentasServiteca.php';
			</script>";

?>