<?php

require("../../conexion.inc");

$nombreLinea = $_POST["nombreLinea"];
$abreviatura = $_POST["abreviatura"];
$contacto1 = $_POST["contacto1"];
$contacto2 = $_POST["contacto2"];
$codProveedor=$_POST["codProveedor"];
$codProcedencia=$_POST["cod_procedencia"];
$margen=$_POST["margen"];

$sqlCod="select IFNULL(max(p.cod_linea_proveedor),0)+1 from proveedores_lineas p";
$respCod=mysql_query($sqlCod);
$codigoNew=mysql_result($respCod,0,0);

$consulta="
INSERT INTO proveedores_lineas (cod_linea_proveedor, nombre_linea_proveedor, abreviatura_linea_proveedor, contacto1, 
contacto2, estado, cod_proveedor, cod_procedencia, margen_precio)
VALUES ($codigoNew, '$nombreLinea', '$abreviatura', '$contacto1', '$contacto2', '1', '$codProveedor','$codProcedencia','$margen')";

$resp=mysql_query($consulta);

if($resp) {
    echo "<script>
		alert('Se guardo la linea correctamente.');
		location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
		</script>";
} else {
    //echo "$consulta";
    echo "<script>
		alert('Error al guardar el registro.');
		location.href='navegadorLineasDistribuidores.php?codProveedor=$codProveedor';
		</script>";
}

?>
