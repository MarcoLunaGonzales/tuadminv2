<?php
require("../conexion.inc");
require("../estilos.inc");
require('../funciones.php');

// Cantidad de Registros
$cantidad_registros = obtenerValorConfiguracion(12);

// Obtén los parámetros de consulta
$fecha_iniconsulta = $_POST['fecha_inicio'];
$fecha_finconsulta = $_POST['fecha_fin'];
$rptTerritorio     = $_POST['territorios'];

// Consulta SQL
$sql = "SELECT pl.cod_linea_proveedor as codigo, COALESCE(pl.nombre_linea_proveedor, '-') as nombre,
        (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria)cantidadventa, sum(((sd.monto_unitario-sd.descuento_unitario)/s.monto_total)*s.descuento)as descuentocabecera, concat(YEAR(s.fecha),'.',MONTH(s.fecha)) as mes
        from salida_almacenes s 
        INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen 
        INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
        INNER JOIN almacenes a ON a.cod_almacen=s.cod_almacen
        LEFT JOIN proveedores_lineas pl ON pl.cod_linea_proveedor=m.cod_linea_proveedor
        where s.fecha BETWEEN '$fecha_iniconsulta' AND '$fecha_finconsulta'
        and s.salida_anulada=0 and s.cod_tiposalida=1001 and a.cod_ciudad in ($rptTerritorio)
        group by pl.cod_linea_proveedor, mes order by mes, montoVenta desc
        LIMIT $cantidad_registros";
$resp = mysqli_query($enlaceCon, $sql);

$meses     = [];
$registros = [];
$data      = [];

while ($row = mysqli_fetch_assoc($resp)) {
    $codigo     = $row['codigo'];
    $nombre     = $row['nombre'];
    $mes        = $row['mes'];
    $montoVenta = $row['montoVenta'];
    
    // Agrega registros a la lista
    if (!isset($registros[$codigo])) {
        $registros[$codigo] = $nombre;
    }
    
    // Agrega meses a la lista
    if (!isset($data[$mes])) {
        $data[$mes] = [];
    }
    
    $data[$mes][$codigo] = $montoVenta;
}

// Ordena los meses
$meses = array_keys($data);
sort($meses);

$series = [];

// Prepara datos para la respuesta
foreach ($registros as $paisCodigo => $paisNombre) {
    $seriesData = array_map(function($mes) use ($data, $paisCodigo) {
        return isset($data[$mes][$paisCodigo]) ? $data[$mes][$paisCodigo] : 0;
    }, $meses);
    
    $series[] = [
        'name' => $paisNombre,
        'data' => $seriesData
    ];
}

ob_clean();

// Prepara la respuesta JSON
header('Content-Type: application/json');
echo json_encode([
    'data' => [
        'categories' => $meses,
        'series'     => $series,
    ]]);
?>
