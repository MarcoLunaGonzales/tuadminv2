<?php

require("../../conexion.inc");

$nomPro = $_GET["nompro"];
$dir = $_GET["dir"];
$tel1 = $_GET["tel1"];
$tel2 = $_GET["tel2"];
$contacto = $_GET["contacto"];
$cod_tipomaterial = $_GET["cod_tipomaterial"];
$linea_marca = isset($_GET['linea_marca']) ? 1 : 0;

$nomPro = str_replace("'", "''", $nomPro);
$dir = str_replace("'", "''", $dir);
$tel1 = str_replace("'", "''", $tel1);
$tel2 = str_replace("'", "''", $tel2);
$contacto = str_replace("'", "''", $contacto);
$cod_tipomaterial = str_replace("'", "''", $cod_tipomaterial);

$consulta="INSERT INTO proveedores (cod_proveedor, nombre_proveedor, direccion, telefono1, telefono2, contacto, cod_tipomaterial)
VALUES ( (SELECT ifnull(max(p.cod_proveedor),0)+1 FROM proveedores p) , '$nomPro', '$dir', '$tel1', '$tel2', '$contacto', '$cod_tipomaterial')
";
$resp=mysqli_query($enlaceCon,$consulta);
$codProveedorCreado = mysqli_insert_id($enlaceCon);
// Crea Linea/Marca
if($linea_marca){
    $lm_abreviatura_linea_proveedor = implode('', array_map(function($palabra) {
                                            return substr(ucfirst($palabra), 0, 1);
                                        }, explode(' ', $nomPro)));
    $lm_nombre_linea_proveedor = $nomPro;
    $lm_cod_proveedor          = $codProveedorCreado;
    $consulta="INSERT INTO proveedores_lineas (cod_linea_proveedor, nombre_linea_proveedor, abreviatura_linea_proveedor, cod_proveedor, estado)
                VALUES ((SELECT ifnull(max(p.cod_linea_proveedor),0)+1 FROM proveedores_lineas p), '$lm_nombre_linea_proveedor', '$lm_abreviatura_linea_proveedor', (SELECT ifnull(max(p.cod_proveedor),0) FROM proveedores p), '1')";
    $resp=mysqli_query($enlaceCon,$consulta);
}

if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha adicionado un nuevo proveedor.');listadoProveedores();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al crear proveedor');</script>";
}

?>
