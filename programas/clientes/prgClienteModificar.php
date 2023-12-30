<?php

require("../../conexion.inc");


$cod_cliente = $_GET['codcli'];

$nomCli = isset($_GET['nomcli']) ? str_replace("'", "''", $_GET['nomcli']) : '';
$apCli  = isset($_GET['apCli']) ? str_replace("'", "''", $_GET['apCli']) : '';
$nit    = isset($_GET['nit']) ? str_replace("'", "''", $_GET['nit']) : '';
$fact   = isset($_GET['fact']) ? str_replace("'", "''", $_GET['fact']) : '';
$tel1   = isset($_GET['tel1']) ? str_replace("'", "''", $_GET['tel1']) : '';
$mail   = isset($_GET['mail']) ? str_replace("'", "''", $_GET['mail']) : '';
$dir    = isset($_GET['dir']) ? str_replace("'", "''", $_GET['dir']) : '';
$cont   = isset($_GET['cont']) ? str_replace("'", "''", $_GET['cont']) : '';
$tel2   = isset($_GET['tel2']) ? str_replace("'", "''", $_GET['tel2']) : '';
$obs    = isset($_GET['obs']) ? str_replace("'", "''", $_GET['obs']) : '';
$tipo_cliente   = isset($_GET['tipo_cliente']) ? str_replace("'", "''", $_GET['tipo_cliente']) : '';
$nombre_cliente = $nomCli.' '.$apCli;

$consulta = "UPDATE clientes 
            SET nombre_cliente = '$nombre_cliente',
                nit_cliente = '$nit',
                dir_cliente = '$dir',
                telf1_cliente = '$tel1',
                email_cliente = '$mail',
                nombre_factura = '$fact',
                contacto = '$cont',
                telefono2 = '$tel2',
                observaciones = '$obs',
                cod_tipocliente = '$tipo_cliente'
            WHERE cod_cliente = '$cod_cliente'";
// echo $consulta;
// exit;
$resp=mysqli_query($enlaceCon,$consulta);
if($resp) {
    echo "<script type='text/javascript' language='javascript'>alert('Se ha modificado el cliente.');listadoClientes();</script>";
} else {
    //echo "$consulta";
    echo "<script type='text/javascript' language='javascript'>alert('Error al modificar cliente');</script>";
}

?>
