<?php
require("../conexionmysqli.inc");
require("../estilos_almacenes.inc");
?>

<script language='JavaScript'>
function envia_formulario(f)
{	
    var rpt_territorio, fecha_ini, fecha_fin, rpt_ver;
    rpt_territorio = f.rpt_territorio.value;
    fecha_fin = f.exaffinal.value;
    fecha_ini = f.exafinicial.value;
	
    window.open('rptObligacionesPagar.php?rpt_territorio=' + rpt_territorio + '&fecha_ini=' + fecha_ini + '&fecha_fin=' + fecha_fin, '', 'scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
    return true;
}
</script>

<?php
$fecha_rptdefault = date("Y-m-d");
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
<div class="titulo-principal">Reporte Obligaciones x Pagar</div>
<form method='post' action='rptOpKardexCostos.php'>
<table class='tabla-reportes' align='center' cellSpacing='0' width='50%'>
    <tr>
        <th align='left'>Territorio</th>
        <td>
            <select name='rpt_territorio' class='selectpicker' data-style='btn btn-info' data-show-subtext='true' data-live-search='true'>
                <?php
                $sql = "select cod_ciudad, descripcion from ciudades order by descripcion";
                $resp = mysqli_query($enlaceCon, $sql);
                echo "<option value=''></option>";
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
        <th align='left'>De fecha:</th>
        <td bgcolor='#ffffff'>
            <input type='date' class='texto' value='<?php echo $fecha_rptdefault; ?>' id='exafinicial' size='10' name='exafinicial'>
        </td>
    </tr>
    <tr>
        <th align='left'>A fecha:</th>
        <td bgcolor='#ffffff'>
            <input type='date' class='texto' value='<?php echo $fecha_rptdefault; ?>' id='exaffinal' size='10' name='exaffinal'>
        </td>
    </tr>
</table>
<br>
<center>
    <input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
</center>
<br>
</form>
</div>
