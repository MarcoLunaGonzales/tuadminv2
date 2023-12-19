<?php

require("../../conexion.inc");

echo "<link rel='stylesheet' type='text/css' href='../../stilos.css'/>";

$codProv   = "";
$nomProv   = "";
$direccion = "";
$telefono1 = "";
$telefono2 = "";
$contacto  = "";

?>
<center>
    <br/>
    <h1>Adicionar Fabricante</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codProv"; ?></span></td>
            <td><input type="text" id="nompro" value="<?php echo "$nomProv"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$direccion"; ?>"/></td>
        </tr>
        <tr>
            <th>Telefono 1</th>
            <th>Telefono 2</th>
            <th>Contacto</th>
        </tr>
        <tr>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
            <td><input type="text" id="tel2" value="<?php echo "$telefono2"; ?>"/></td>
            <td><input type="text" id="contacto" value="<?php echo "$contacto"; ?>"/></td>
        </tr>
        <tr>
            <th>Tipo Marca</th>
            <th colspan="2"></th>  
        </tr>
        <tr>
            <td>
                <select name="tipo_proveedor" id="tipo_proveedor">
                    <?php 
                    $consulta="SELECT tp.codigo, tp.nombre, tp.abreviatura 
                                FROM tipos_proveedor tp 
                                WHERE tp.estado = 1
                                ORDER BY tp.codigo DESC";
                    $rs = mysqli_query($enlaceCon,$consulta);
                    while($reg=mysqli_fetch_array($rs)){
                        $codigo = $reg["codigo"];
                        $nombre = $reg["nombre"];
                    
                    ?>
                    <option value="<?=$codigo?>"><?=$nombre?></option>
                    <?php 
                    }
                    ?>
                </select>    
            </td>
            <td colspan="2"></td>
        </tr>
    </table>
</center>
<div class="divBotones">
    <input class="boton" type="button" value="Guardar" onclick="javascript:adicionarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>