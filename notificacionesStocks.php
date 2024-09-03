<?php

require_once 'conexionmysqli.inc';
require("estilos.inc");

$fechaActual=date("Y-m-d");
?>
<div class="card">
    <div class="card-header card-header-icon text-center">
        <h4 class="card-title"><b>Notificación de Productos Bajo Stock</b></h4>
    </div>
    <div class="card-body text-center">
        <center class='scroll-container'><table class='texto' id='myTable'>
            <tr>
                <th>-</th>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Stock Minimo</th>
                <th>Stock Total</th>
            </tr>
        <?php
            $sqlStocks="SELECT n.codigo, n.cod_producto, m.descripcion_material, n.stock, n.stock_minimo from notificaciones_stocks n 
                INNER JOIN material_apoyo m ON m.codigo_material=n.cod_producto
                WHERE n.fecha_registro='$fechaActual' ";
            //echo $sqlStocks;
            $respStocks=mysqli_query($enlaceCon, $sqlStocks);
            $indice=1;
            while($dat=mysqli_fetch_array($respStocks)){
                $codigo=$dat[1];
                $nombreProducto=$dat[2];
                $stockActual=$dat[3];
                $stockMinimo=$dat[4];
        ?>
            <tr>
                <td><?=$indice;?></td>
                <td><?=$codigo;?></td>
                <td><?=$nombreProducto;?></td>
                <td align="right"><?=$stockMinimo;?></td>
                <td align="right"><?=$stockActual;?></td>
            </tr>
        <?php
                $indice++;
            }
        ?>
    </div>
    <!--div class="text-center mb-2">
        <a href="/ruta-al-reporte" class="btn btn-warning btn-sm">Ver Reporte</a>
    </div-->
</div>
