<?php
require("../../conexion.inc");

$codFuncionario = $_POST['cod_funcionario'];
$codCliente = $_POST['cod_cliente'];

$consulta = "INSERT INTO funcionarios_clientes (cod_funcionario, cod_cliente) 
            VALUES ($codFuncionario, $codCliente)";
ob_clean();
if (mysqli_query($enlaceCon, $consulta)) {
    echo 'success';
} else {
    echo 'error';
}
?>
