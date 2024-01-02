<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$codProv   = $_GET["codprov"];
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";
$consulta="
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto, p.cod_tipomaterial
    FROM proveedores AS p 
    WHERE p.cod_proveedor = $codProv ORDER BY p.nombre_proveedor ASC
";
$rs=mysqli_query($enlaceCon,$consulta);
$nroregs=mysqli_num_rows($rs);
if($nroregs==1)
   {$reg=mysqli_fetch_array($rs);
    //$codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
    $cod_tipomaterial  = $reg["cod_tipomaterial"];
   }

?>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h3>Editar Proveedor</h3>
        </div>
        <div class="card-body">
                <div class="mb-3 row">
                    <label for="codpro" class="col-sm-2 col-form-label font-weight-bold">Código</label>
                    <div class="col-sm-10">
                        <span id="codpro" class="form-control-plaintext"><?php echo "$codProv"; ?></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nompro" class="col-sm-2 col-form-label font-weight-bold">Proveedor</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nompro" value="<?php echo "$nomProv"; ?>"/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="dir" class="col-sm-2 col-form-label font-weight-bold">Dirección</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="dir" value="<?php echo "$direccion"; ?>"/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="tel1" class="col-sm-2 col-form-label font-weight-bold">Teléfono 1</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="tel1" value="<?php echo "$telefono1"; ?>"/>
                    </div>
                    <label for="tel2" class="col-sm-2 col-form-label font-weight-bold">Teléfono 2</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="tel2" value="<?php echo "$telefono2"; ?>"/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="contacto" class="col-sm-2 col-form-label font-weight-bold">Contacto</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="contacto" value="<?php echo "$contacto"; ?>"/>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="cod_tipomaterial" class="col-sm-2 col-form-label font-weight-bold">Tipo Material</label>
                    <div class="col-sm-10">
                        <select name="cod_tipomaterial" id="cod_tipomaterial" class="form-control">
                            <?php 
                            $consulta = "SELECT tm.cod_tipomaterial, tm.nombre_tipomaterial 
                                        FROM tipos_material tm
                                        ORDER BY tm.cod_tipomaterial DESC";
                            $rs = mysqli_query($enlaceCon, $consulta);
                            while ($reg = mysqli_fetch_array($rs)) {
                                $codigo = $reg["cod_tipomaterial"];
                                $nombre = $reg["nombre_tipomaterial"];
                                $selected = $cod_tipomaterial == $codigo ? 'selected' : '';
                            ?>
                            <option value="<?= $codigo ?>" <?= $selected ?>><?= $nombre ?></option>
                            <?php 
                            }
                            ?>
                        </select>
                    </div>
                </div>
        </div>
        <div class="card-footer justify-content-center">
            <div class="row">
                <input class="btn btn-primary" type="button" value="Modificar" onclick="javascript:modificarProveedor();" />
                <input class="btn btn-danger ml-2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
            </div>
        </div>
    </div>
</div>