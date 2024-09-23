<?php
require("../../conexion.inc");

$codFuncionario = $_POST['cod_funcionario'];
$codCliente     = $_POST['cod_cliente'];

$consulta = "DELETE FROM funcionarios_clientes 
            WHERE cod_funcionario = $codFuncionario AND cod_cliente = $codCliente";
ob_clean();
if (mysqli_query($enlaceCon, $consulta)) {
    echo 'success';
} else {
    echo 'error';
}
?>
