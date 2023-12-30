<?php

require("../../conexion.inc");
ob_clean();

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


$sql = "SELECT IFNULL(MAX(cod_cliente)+1,1) as id from clientes order by cod_cliente desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigoCliente=$dat['id'];

$consulta="INSERT INTO clientes (cod_cliente, nombre_cliente, nit_cliente, dir_cliente, telf1_cliente, email_cliente, nombre_factura, contacto, telefono2, observaciones, cod_tipocliente)
VALUES ('$codigoCliente','$nombre_cliente','$nit','$dir','$tel1','$mail','$fact','$cont','$tel2','$obs','$tipo_cliente')
";

if(isset($_GET["dv"])){
    $resp=mysqli_query($enlaceCon,$consulta);
    if($resp) {
      echo "#####".$codigoCliente;
    } else {
      echo "#####0";
    }
}else{
  
    $resp=mysqli_query($enlaceCon,$consulta);
    if($resp) {
        echo "<script type='text/javascript' language='javascript'>alert('Se ha adicionado un nuevo cliente.');listadoClientes();</script>";
    } else {
        //echo "$consulta";
        echo "<script type='text/javascript' language='javascript'>alert('Error al crear cliente');</script>";
    }
}

?>
