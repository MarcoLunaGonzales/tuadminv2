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
    SELECT p.cod_proveedor, p.nombre_proveedor, p.direccion, p.telefono1, p.telefono2, p.contacto 
    FROM proveedores AS p 
    WHERE p.cod_proveedor = $codProv ORDER BY p.nombre_proveedor ASC
";
$rs=mysql_query($consulta);
$nroregs=mysql_num_rows($rs);
if($nroregs==1)
   {$reg=mysql_fetch_array($rs);
    //$codProv = $reg["cod_proveedor"];
    $nomProv = $reg["nombre_proveedor"];
    $direccion = $reg["direccion"];
    $telefono1 = $reg["telefono1"];
    $telefono2 = $reg["telefono2"];
    $contacto  = $reg["contacto"];
   }

?>
<center>
    <br/>
    <h1>Editar Distribuidor</h1>
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Proveedor</th>
            <th>Direccion</th>
        </tr>
        <tr>
            <td><span id="codpro"><?php echo "$codProv"; ?></span></td>
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
    </table>
</center>
<div class="divBotones"> 
	<input class="boton" type="button" value="Modificar" onclick="javascript:modificarProveedor();" />
    <input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoProveedores();" />
</div>
