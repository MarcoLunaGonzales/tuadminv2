<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$codProv   = "";
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";

?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Adicionar Distribuidor</h3>
        </div>
        <div class="card-body">
            <div class="mb-3 row">
                <label for="codpro" class="col-sm-2 col-form-label font-weight-bold">Código</label>
                <div class="col-sm-10">
                    <span id="id" class="form-control-plaintext"><?php echo "$codProv"; ?></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nompro" class="col-sm-2 col-form-label font-weight-bold">Proveedor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nompro"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="dir" class="col-sm-2 col-form-label font-weight-bold">Dirección</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dir"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="tel1" class="col-sm-2 col-form-label font-weight-bold">Teléfono 1</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="tel1"/>
                </div>
                <label for="tel2" class="col-sm-2 col-form-label font-weight-bold">Teléfono 2</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="tel2"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="contacto" class="col-sm-2 col-form-label font-weight-bold">Contacto</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="contacto"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="cod_tipomaterial" class="col-sm-2 col-form-label font-weight-bold">Tipo Material</label>
                <div class="col-sm-4">
                    <select name="cod_tipomaterial" id="cod_tipomaterial" class="form-control">
                        <?php 
                        $consulta = "SELECT tm.cod_tipomaterial, tm.nombre_tipomaterial 
                                    FROM tipos_material tm
                                    ORDER BY tm.cod_tipomaterial DESC";
                        $rs = mysqli_query($enlaceCon, $consulta);
                        while ($reg = mysqli_fetch_array($rs)) {
                            $codigo = $reg["cod_tipomaterial"];
                            $nombre = $reg["nombre_tipomaterial"];
                        ?>
                        <option value="<?= $codigo ?>"><?= $nombre ?></option>
                        <?php 
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input type="checkbox" class="form-check-input" id="linea_marca" checked/>
                    <label class="form-check-label" for="linea_marca">Crear Linea/Marca</label>
                </div>
            </div>
        </div>
        <div class="card-footer justify-content-center">
            <div class="row">
                <input class="btn btn-primary" type="button" value="Guardar" onclick="javascript:adicionarProveedor();" />
                <input class="btn btn-secondary ml-2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
            </div>
        </div>
    </div>
</div>