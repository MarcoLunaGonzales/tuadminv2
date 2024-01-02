<?php

require("../../conexion.inc");
require("../../estilos_almacenes.inc");

$codCliente = "";
$nomcli = "";
$apCli  = "";
$nit    = "";
$fact   = "";
$tel1   = "";
$mail   = "";
$dir    = "";
$cont   = "";
$tel2   = "";
$obs    = "";
$tipo_cliente = "";

?>
<center>
    <br/>
    <h1>Adicionar Cliente</h1>
	
    <table class="texto">
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Paterno</th>
            <th>Tipo de Cliente</th>
            <th>NIT</th>
            <th>Factura</th>
        </tr>
        <tr>
            <td><span id="id"><?php echo "$codCliente"; ?></span></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="nomcli" value="<?php echo "$nomcli"; ?>"/></td>
            <td><input type="text" style="background-color: #fff;" class="form-control" id="apCli" value="<?php echo "$apCli"; ?>"/></td>
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
	<div class="divBotones">
		<input class="boton" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
		<input class="boton2" type="button" value="Cancelar" onclick="javascript:listadoClientes();" />
	</div>
    <br/>
</center>
