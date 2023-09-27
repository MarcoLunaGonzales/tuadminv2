<?php
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
?>

<script>
function envia_formulario(f)
{	var rpt_territorio,fecha_ini, fecha_fin, rpt_ver, rpt_proveedor;
	rpt_territorio=f.rpt_territorio.value;
	fecha_ini=f.exafinicial.value;
	fecha_fin=f.exaffinal.value;
	rpt_proveedor=f.rpt_proveedor.value;
	window.open('rptObligaciones.php?rpt_territorio='+rpt_territorio+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_proveedor='+rpt_proveedor,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
	return(true);
}
</script>

<?php

$fecha_rptdefault=date("d/m/Y");
?>
    <style>
        /* Estilo del título principal */
        .titulo-principal {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Estilo general del formulario */
        form {
            text-align: center;
        }

        /* Estilo de la tabla */
        .tabla-reportes {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Agrega una sombra suave */
        }

        /* Estilo de las celdas de encabezado */
        .tabla-reportes th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f8f9fa; /* Cambia el color de fondo del encabezado */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Agrega una sombra suave */
            border: 1px solid #ccc; /* Agrega un borde suave */
        }

        /* Estilo de las filas impares */
        .tabla-reportes tr:nth-child(odd) {
            background-color: #f2f2f2; /* Color de fondo para filas impares */
        }

        /* Estilo de las celdas de datos */
        .tabla-reportes td {
            padding: 10px;
            border: 1px solid #ccc; /* Agrega un borde suave a las celdas de datos */
        }

        /* Estilo de los select */
        .selectpicker {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }

        /* Estilo de los inputs de fecha */
        .texto[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }

        /* Estilo del botón */
        .boton {
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Cambia el color del botón al pasar el cursor sobre él */
        .boton:hover {
            background-color: #0056b3;
        }
    </style>

<div class="titulo-principal">Reporte de Obligaciones</div>
<form method="post" action="">
    <table class="tabla-reportes" cellspacing="0" border="1">
        <tr>
            <th align="left">Territorio</th>
            <td>
                <select name="rpt_territorio" class="selectpicker" data-style="btn btn-info" data-show-subtext="true" data-live-search="true">
                    <option value=""></option>
                    <?php
                    $sql = "select cod_ciudad, descripcion from ciudades order by descripcion";
                    $resp = mysqli_query($enlaceCon, $sql);
                    while ($dat = mysqli_fetch_array($resp)) {
                        $codigo_ciudad = $dat[0];
                        $nombre_ciudad = $dat[1];
                        echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th align="left">Proveedor</th>
            <td>
                <select name="rpt_proveedor" class="selectpicker" data-style="btn btn-info" data-show-subtext="true" data-live-search="true">
                    <option value="0">Todos</option>
                    <?php
                    $sql = "select cod_cliente, concat(nombre_cliente,' ',paterno) from clientes order by 2";
                    $resp = mysqli_query($enlaceCon, $sql);
                    while ($dat = mysqli_fetch_array($resp)) {
                        $codigo = $dat[0];
                        $nombre = $dat[1];
                        echo "<option value='$codigo'>$nombre</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th align="left">Fecha inicio:</th>
            <td bgcolor="#ffffff">
                <input type="date" class="texto" value="<?php echo $fecha_rptdefault; ?>" id="exafinicial" size="10" name="exafinicial">
            </td>
        </tr>
        <tr>
            <th align="left">Fecha final:</th>
            <td bgcolor="#ffffff">
                <input type="date" class="texto" value="<?php echo $fecha_rptdefault; ?>" id="exaffinal" size="10" name="exaffinal">
            </td>
        </tr>
    </table>
    <br>
    <center>
        <input type="button" name="reporte" value="Ver Reporte" onClick="envia_formulario(this.form)" class="boton">
    </center>
    <br>
</form>
