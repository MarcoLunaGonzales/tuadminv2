<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$codCliente = $_GET["codcli"];
$nomCliente = "";
$nitCliente = "";
$dirCliente = "";
$telefono1  = "";
$email      = "";
$codArea    = "";
$nomFactura = "";
$nomArea    = "";
$cadComboCiudad = "";
$consulta="SELECT
                c.cod_cliente,
                c.nombre_cliente,
                c.nit_cliente,
                c.dir_cliente,
                c.telf1_cliente,
                c.email_cliente,
                c.nombre_factura,
                c.contacto,
                c.telefono2,
                c.observaciones,
                c.cod_tipocliente
            FROM clientes AS c
            WHERE c.cod_cliente = $codCliente
            ORDER BY c.nombre_cliente ASC";
$rs=mysqli_query($enlaceCon,$consulta);
$nroregs=mysqli_num_rows($rs);
if($nroregs==1){
    $reg=mysqli_fetch_array($rs);
    
    $codCliente   = $reg['cod_cliente'];
    $nit          = $reg['nit_cliente'];
    $fact         = $reg['nombre_factura'];
    $tel1         = $reg['telf1_cliente'];
    $mail         = $reg['email_cliente'];
    $dir          = $reg['dir_cliente'];
    $cont         = $reg['contacto'];
    $tel2         = $reg['telefono2'];
    $obs          = $reg['observaciones'];
    $tipo_cliente = $reg['cod_tipocliente'];
    
    // Dividir el nombre en dos partes
    $nombreApellido = explode(' ', $reg['nombre_cliente'], 2);

    // Obtener las dos partes o asignar el valor original si no se divide
    $nombre   = isset($nombreApellido[0]) ? $nombreApellido[0] : $reg['nombre_cliente'];
    $apellido = isset($nombreApellido[1]) ? $nombreApellido[1] : '';

}

?>
<center>
    <br/>
    <h1>Editar Cliente</h1>

    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Cliente</th>
            <th>Paterno</th>
            <th>Tipo de Precio</th>
            <th>NIT</th>
            <th>Factura</th>
        </tr>
        <tr>
            <td>
                <input type="hidden" name="codcli" id="codcli" value="<?php echo "$codCliente"; ?>">
                <span id="id"><?php echo "$codCliente"; ?></span>
            </td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="nomcli" value="<?php echo "$nombre"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="apCli" value="<?php echo "$apellido"; ?>"/></td>
            <td>
                <select id="tipo_cliente" name="tipo_cliente" style="background-color: #fff;" class="form-control">
                    <?php 
                        $consulta1="SELECT tc.codigo, tc.nombre, tc.abreviatura
                                    FROM tipos_clientes tc";
                        $res = mysqli_query($enlaceCon,$consulta1);
                        while($row = mysqli_fetch_array($res)){
                    ?>
                    <option value="<?=$row['codigo']?>"><?=$row['nombre']?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="nit" value="<?php echo "$nit"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="fact" value="<?php echo "$fact"; ?>"/></td>
        </tr>
        <tr>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Direccion</th>
            <th>Contacto/Cel</th>
            <th>Contacto/Telf</th>
            <th>Observaciones</th>
        </tr>
        <tr>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="tel1" value="<?php echo "$tel1"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="mail" value="<?php echo "$mail"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="dir" value="<?php echo "$dir"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="cont" value="<?php echo "$cont"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="tel2" value="<?php echo "$tel2"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="obs" value="<?php echo "$obs"; ?>"/></td>
        </tr>
    </table>
    <br/>
    <input class='boton' type="button" value="Modificar" onclick="javascript:modificarCliente();" />
    <input class='boton2' type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
    <br/>
</center>
