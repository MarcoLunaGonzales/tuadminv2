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
$consulta="
    SELECT c.cod_cliente, c.nombre_cliente, c.nit_cliente, c.dir_cliente, c.telf1_cliente, c.email_cliente, c.cod_area_empresa, c.nombre_factura, a.cod_ciudad, a.descripcion
    FROM clientes AS c INNER JOIN ciudades AS a ON c.cod_area_empresa = a.cod_ciudad
    WHERE c.cod_cliente = $codCliente ORDER BY c.nombre_cliente ASC
";
$rs=mysql_query($consulta);
$nroregs=mysql_num_rows($rs);
if($nroregs==1)
   {$reg=mysql_fetch_array($rs);
    //$codCliente = $reg["cod_cliente"];
    $nomCliente = $reg["nombre_cliente"];
    $nitCliente = $reg["nit_cliente"];
    $dirCliente = $reg["dir_cliente"];
    $telefono1  = $reg["telf1_cliente"];
    $email      = $reg["email_cliente"];
    $codArea    = $reg["cod_area_empresa"];
    $nomFactura = $reg["nombre_factura"];
    $nomArea    = $reg["descripcion"];
    $consulta="SELECT c.cod_ciudad, c.descripcion FROM ciudades AS c WHERE 1 = 1 ORDER BY c.descripcion ASC";
    $rs=mysql_query($consulta);
    while($reg=mysql_fetch_array($rs))
       {$codCiudad = $reg["cod_ciudad"];
        $nomCiudad = $reg["descripcion"];
        if($codArea==$codCiudad) {
            $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad' selected>$nomCiudad</option>";
        } else {
            $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad'>$nomCiudad</option>";
        }
       }
   }

?>
<center>
    <br/>
    <h1>Editar Cliente</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Cliente</th>
            <th>NIT</th>
            <th>Direccion</th>
            <th>Telefono</th>
        </tr>
        <tr>
            <td><span id="codcli"><?php echo "$codCliente"; ?></span></td>
            <td><input type="text" id="nomcli" value="<?php echo "$nomCliente"; ?>"/></td>
            <td><input type="text" id="nit" value="<?php echo "$nitCliente"; ?>"/></td>
            <td><input type="text" id="dir" value="<?php echo "$dirCliente"; ?>"/></td>
            <td><input type="text" id="tel1" value="<?php echo "$telefono1"; ?>"/></td>
        </tr>
        <tr>
            <th>Correo</th>
            <th>Factura</th>
            <th colspan="3">Ciudad</th>
        </tr>
        <tr>
            <td><input type="text" id="mail" value="<?php echo "$email"; ?>"/></td>
            <td><input type="text" id="fact" value="<?php echo "$nomFactura"; ?>"/></td>
            <td colspan="3"><select id="area"><?php echo "$cadComboCiudad"; ?></select></td>
        </tr>
    </table>
    <br/>
    <input class='boton' type="button" value="Modificar" onclick="javascript:modificarCliente();" />
    <input class='boton2' type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
    <br/>
</center>
