<?php

require("../../conexion.inc");

$codCli = $_GET["codcli"];
$nomCli = $_GET["nomcli"];
$nit = $_GET["nit"];
$dir = $_GET["dir"];
$tel1 = $_GET["tel1"];
$mail = $_GET["mail"];
$area = $_GET["area"];
$fact = $_GET["fact"];

$nomCli = str_replace("'", "''", $nomCli);
$nit = str_replace("'", "''", $nit);
$dir = str_replace("'", "''", $dir);
$tel1 = str_replace("'", "''", $tel1);
$mail = str_replace("'", "''", $mail);
$area = $area;
$fact = str_replace("'", "''", $fact);

$consulta="
    UPDATE clientes SET
    nombre_cliente = '$nomCli',
    nit_cliente = '$nit',
    dir_cliente = '$dir',
    telf1_cliente = '$tel1',
    email_cliente = '$mail',
    cod_area_empresa = $area,
    nombre_factura = '$fact'
    WHERE cod_cliente = $codCli
";
$resp=mysql_query($consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha modificado el cliente.');listadoClientes();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al modificar cliente');</script>";
}

?>
