<?php

require("conexionmysqli.php");
require('funciones.php');
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$global_admin_cargo=$_COOKIE["global_admin_cargo"];

?>
<html>
    <head>
        <title>Lista Verificación</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type='text/javascript' language='javascript'>
   
        </script>
    </head>
    <body>
        <h1>Listado de Verificación</h1>
        <div class="container-fluid m-0">
            <table class="table table-hover" style="font-size: 12px;">
                <thead class="thead-light">
                    <tr>
                        <th>Cant. Salida</th>
                        <th>Cant. Ingreso</th>
                        <th>Cant. Venta</th>
                        <th>COD. VENTA</th>
                        <th>Producto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $consulta = "SELECT SUM(trsd.cantidad_unitaria) as salida_cantidad, 
												SUM(trid.cantidad_unitaria) as ingreso_cantidad,
												SUM(vd.cantidad_unitaria) as venta_cantidad,
												v.cod_salida_almacenes as venta_cod, 
												vd.cod_material,
												ma.descripcion_material as producto
									FROM salida_almacenes v
									INNER JOIN salida_detalle_almacenes vd ON vd.cod_salida_almacen = v.cod_salida_almacenes
									INNER JOIN ingreso_almacenes tri ON tri.cod_ingreso_almacen = vd.cod_ingreso_almacen
									INNER JOIN ingreso_detalle_almacenes trid ON trid.cod_ingreso_almacen = tri.cod_ingreso_almacen
									INNER JOIN salida_almacenes trs ON trs.fecha = v.fecha AND trs.hora_salida = v.hora_salida
									INNER JOIN salida_detalle_almacenes trsd ON trsd.cod_salida_almacen = trs.cod_salida_almacenes
									LEFT JOIN material_apoyo ma ON ma.codigo_material = vd.cod_material
									WHERE v.cod_tiposalida = 1001
									AND tri.cod_tipoingreso = 1002
									AND trs.cod_tiposalida = 1000
									AND trsd.precio_unitario = 0
									AND vd.cod_material = trsd.cod_material
									GROUP BY v.cod_salida_almacenes, vd.cod_material
									HAVING SUM(trsd.cantidad_unitaria) <> SUM(trid.cantidad_unitaria)
									ORDER BY v.cod_salida_almacenes, vd.cod_material ASC";

                        $resp = mysqli_query($enlaceCon, $consulta);

                        if(mysqli_num_rows($resp) > 0) {
                            $resultados = mysqli_fetch_all($resp, MYSQLI_ASSOC);

                            foreach($resultados as $dat) {
                    ?>
                        <tr>
                            <td class="pt-2 pb-2"><?=$dat['salida_cantidad']?></td>
                            <td class="pt-2 pb-2"><?=$dat['ingreso_cantidad']?></td>
                            <td class="pt-2 pb-2"><?=$dat['venta_cantidad']?></td>
                            <td class="pt-2 pb-2"><?=$dat['venta_cod']?></td>
                            <td class="pt-2 pb-2"><?=$dat['producto']?></td>
                        </tr>
                    <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>


