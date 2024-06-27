<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$globalAgencia=$_COOKIE["global_agencia"];
$globalAlmacen=$_COOKIE["global_almacen"];

$fil_nombre   = $_GET['fil_nombre'] ?? '';
$fil_nit      = $_GET['fil_nit'] ?? '';
$fil_direcion = $_GET['fil_direccion'] ?? '';

echo "<br>";
echo "<h1>Clientes</h1>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<button type='button' 
        name='abrirModalFiltro' 
        class='boton-image' 
        id='abrirModalFiltro' 
        onclick='abrirModalFiltro()'
        title='Abrir Filtro de Clientes'>
    <i class='material-icons'>manage_search</i>
</button>
</div>";

echo "<center>";
echo "<table class='texto'>";
echo "<tr>";

echo '<th style="width: 30%;">Cliente</th>';
echo '<th style="width: 15%;">NIT</th>';
echo '<th style="width: 25%;">Dirección</th>';
echo '<th style="width: 15%;">Ciudad</th>';
echo '<th style="width: 10%;" class="text-center">Acciones</th>';

echo "</tr>";
$consulta="SELECT c.cod_cliente, c.nombre_cliente, c.nit_cliente, c.dir_cliente, c.cod_area_empresa, a.descripcion
    FROM clientes AS c 
    INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad 
    WHERE c.cod_cliente is not null ";
if(!empty($fil_nombre)){
    $consulta .= " AND c.nombre_cliente LIKE '%".$fil_nombre."%' ";
}
if(!empty($fil_nit)){
    $consulta .= " AND c.nit_cliente LIKE '%".$fil_nit."%' ";
}
if(!empty($fil_direcion)){
    $consulta .= " AND c.dir_cliente LIKE '%".$fil_direcion."%' ";
}
$consulta .= " ORDER BY c.nombre_cliente ASC";

// echo $consulta;

$rs=mysqli_query($enlaceCon,$consulta);
$cont=0;
while($reg=mysqli_fetch_array($rs)){
    $cont++;
    $codCliente = $reg["cod_cliente"];
    $nomCliente = $reg["nombre_cliente"];
    $nitCliente = $reg["nit_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $codArea = $reg["cod_area_empresa"];
    $nomArea = $reg["descripcion"];
    echo "<tr>";
    echo "<td>$nomCliente</td>
            <td>$nitCliente</td>
            <td>$dirCliente</td>
            <td>$nomArea</td>
            <td class='text-center'>
                <button class='btn btn-sm btn-info pt-4 editarCliente' data-cod_cliente='$codCliente' title='Editar' style='padding-left: 10px; padding-right: 10px;'>
                    <i class='material-icons'>edit</i>
                </button>
                <button class='btn btn-sm btn-danger pt-4 eliminarCliente' data-cod_cliente='$codCliente' title='Eliminar' style='padding-left: 10px; padding-right: 10px;'>
                    <i class='material-icons'>delete</i>
                </button>
            </td>";
    echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' id='idtotal' value='$cont' >";
echo "</center>";

echo "<div class='divBotones'>
<input class='boton' type='button' value='Adicionar' onclick='javascript:frmAdicionar();'>
<input class='boton2' type='button' value='Eliminar' onclick='javascript:frmEliminar();'>
</div>";

?>
        
<!-- MODAL FILTRO -->
<div class="modal fade" id="modalControlVersion" tabindex="-1" role="dialog" aria-labelledby="modalAgregarEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTituloCambio">Filtro de Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row pb-2">
                    <label class="col-sm-3">Nombre:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="fil_nombre" id="fil_nombre" placeholder="Ingresar nombre del cliente" autocomplete="off" value="<?=$fil_nombre?>"/>
                    </div>
                </div>
                <div class="row pb-2">
                    <label class="col-sm-3">NIT:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="fil_nit" id="fil_nit" placeholder="Ingresar NIT" autocomplete="off" value="<?=$fil_nit?>"/>
                    </div>
                </div>
                <div class="row pb-2">
                    <label class="col-sm-3">Dirección:</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="fil_direccion" id="fil_direccion" placeholder="Ingresar dirección" autocomplete="off" value="<?=$fil_direcion?>"/>
                    </div>
                </div>
                <div class="modal-footer p-0 pt-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="filtrarLista" class="btn btn-primary" onclick="filtroCliente()">Filtrar</button>
                </div>
            </div>
        </div>
    </div>
</div>