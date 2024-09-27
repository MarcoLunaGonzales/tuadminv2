<?php

require("../../conexion.inc");

// Obtener el cliente
$codCliente = $_GET["codcli"];
$consultaCliente = "SELECT c.cod_cliente, CONCAT(c.nombre_cliente,' ',c.paterno) as nombre_cliente 
                    FROM clientes AS c
                    WHERE c.cod_cliente = '$codCliente'";
$rs = mysqli_query($enlaceCon, $consultaCliente);
$cliente = mysqli_fetch_array($rs);
$nombreCliente = $cliente['nombre_cliente'];

// Obtener lista de funcionarios
$consultaFuncionarios = "SELECT f.codigo_funcionario, CONCAT(f.nombres,' ',f.paterno) as nombre_funcionario
                        FROM funcionarios AS f order by 2";
$rsFuncionarios = mysqli_query($enlaceCon, $consultaFuncionarios);
$funcionarios = [];
while ($row = mysqli_fetch_assoc($rsFuncionarios)) {
    $funcionarios[] = $row;
}

// Obtener funcionarios asignados al cliente
$consultaAsignados = "SELECT
                        f.codigo_funcionario,
                        CONCAT(f.nombres,' ',f.paterno) as nombre_funcionario 
                    FROM funcionarios_clientes AS fc
                    INNER JOIN funcionarios AS f ON fc.cod_funcionario = f.codigo_funcionario 
                    WHERE fc.cod_cliente = '$codCliente'";
$rsAsignados = mysqli_query($enlaceCon, $consultaAsignados);
$asignados = [];
while ($row = mysqli_fetch_assoc($rsAsignados)) {
    $asignados[] = $row;
}

?>

<center>
    <h3>Funcionarios Asignados al Cliente: 
        <b><?php echo $nombreCliente; ?></b>
        <a class="btn btn-danger pt-4" href='inicioClientes.php' title='Lista de Clientes' style='padding-left: 10px; padding-right: 10px;'>
            <i class='material-icons'>arrow_back</i>
        </a>
    </h3>

    <div class="container">
        <form id="form-asignacion" class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="funcionario">Seleccionar Funcionario:</label>
                    <select id="funcionario" class="selectpicker form-control" data-style='btn btn-success' data-live-search="true" name="funcionario" required>
                        <option value="">-- Seleccionar --</option>
                        <?php foreach ($funcionarios as $funcionario): ?>
                            <option value="<?php echo $funcionario['codigo_funcionario']; ?>">
                                <?php echo $funcionario['nombre_funcionario']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="cod_cliente" value="<?php echo $codCliente; ?>" />
                <button type="button" class="btn btn-primary" onclick="agregarAsignacion()">Agregar Funcionario</button>
            </div>
        </form>
    </div>

    <!--h3>Lista de Funcionarios Asignados</h3-->
    <div class="container">
        <table class="table table-bordered table-striped table-hover" id="tabla-asignados">
            <thead class="thead-light">
                <tr>
                    <th width="90%">Funcionario</th>
                    <th width="10%">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($asignados)): ?>
                    <tr>
                        <td colspan="2" class="text-center">No hay funcionarios asignados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($asignados as $asignado): ?>
                        <tr id="funcionario-<?php echo $asignado['codigo_funcionario']; ?>">
                            <td><?php echo $asignado['nombre_funcionario']; ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm pt-4" onclick="eliminarAsignacion(<?php echo $asignado['codigo_funcionario']; ?>)">
                                    <i class="material-icons">delete</i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</center>

<script>
    function agregarAsignacion() {
        var funcionario = $("#funcionario").val();
        var cod_cliente = $("input[name='cod_cliente']").val();
        
        if (funcionario === "") {
            alert("Debe seleccionar un funcionario");
            return;
        }

        $.ajax({
            url: 'funcionarioClienteGuardar.php',
            type: 'POST',
            data: {
                cod_funcionario: funcionario,
                cod_cliente: cod_cliente
            },
            success: function(response) {
                if (response == 'success') {
                    alert("Funcionario asignado con éxito");
                    // Recargar la tabla con la nueva asignación
                    location.reload();
                } else {
                    alert("Error al asignar funcionario");
                }
            }
        });
    }

    function eliminarAsignacion(cod_funcionario) {
        var cod_cliente = $("input[name='cod_cliente']").val();
        
        $.ajax({
            url: 'funcionarioClienteEliminar.php',
            type: 'POST',
            data: {
                cod_funcionario: cod_funcionario,
                cod_cliente: cod_cliente
            },
            success: function(response) {
                if (response == 'success') {
                    alert("Funcionario eliminado con éxito");
                    // Eliminar la fila de la tabla
                    $("#funcionario-" + cod_funcionario).remove();
                } else {
                    alert("Error al eliminar funcionario");
                }
            }
        });
    }
</script>
