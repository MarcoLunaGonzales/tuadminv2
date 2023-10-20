<?php

require("../../conexion.inc");

$codPro = $_GET["codpro"];
$nomPro = $_GET["nompro"];
$dir = $_GET["dir"];
$tel1 = $_GET["tel1"];
$tel2 = $_GET["tel2"];
$contacto = $_GET["contacto"];
$tipo_proveedor = $_GET["tipo_proveedor"];

$nomPro = str_replace("'", "''", $nomPro);
$dir = str_replace("'", "''", $dir);
$tel1 = str_replace("'", "''", $tel1);
$tel2 = str_replace("'", "''", $tel2);
$contacto = str_replace("'", "''", $contacto);
$tipo_proveedor = str_replace("'", "''", $tipo_proveedor);

$consulta="
    UPDATE proveedores SET
    nombre_proveedor = '$nomPro',
    direccion = '$dir',
    telefono1 = '$tel1',
    telefono2 = '$tel2',
    contacto = '$contacto',
    cod_tipo_proveedor = '$tipo_proveedor'
    WHERE cod_proveedor = $codPro
";
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha modificado el proveedor.');listadoProveedores();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al modificar proveedor');</script>";
}

?>
