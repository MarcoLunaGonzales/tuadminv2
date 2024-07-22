<?php
require("../conexion.inc");
require("../estilos.inc");
require('../funciones.php');

// Obtén los parámetros de consulta
$fecha_iniconsulta = $_POST['fecha_inicio'];
$fecha_finconsulta = $_POST['fecha_fin'];
$rptTerritorio     = $_POST['territorios'];

// Consulta SQL
$sql = "SELECT g.cod_grupo, COALESCE(g.nombre_grupo, '-') as nombre, 
        (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, sum(((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento)as descuentocabecera
        from salida_almacenes s 
        INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen 
        INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
        INNER JOIN almacenes a ON a.cod_almacen=s.cod_almacen
        LEFT JOIN grupos g ON g.cod_grupo=m.cod_grupo
        WHERE s.fecha BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
        AND s.salida_anulada=0 AND s.cod_tiposalida=1001 AND a.cod_ciudad IN ($rptTerritorio)
	    group by g.cod_grupo order by montoVenta desc";

$resp = mysqli_query($enlaceCon, $sql);

$data = [];

if (mysqli_num_rows($resp) > 0) {
    // Salida de datos de cada fila
    while($row = mysqli_fetch_assoc($resp)) {
        $data[] = $row;
    }
}

ob_clean();

// Devolver datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
