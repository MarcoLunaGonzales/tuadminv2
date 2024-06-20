<?php
require('assets/fpdf/fpdf.php');
require_once 'conexionmysqli.inc';

/*****************************************************************************/
// Definimos tamaño de HOJA
$orientation = 'P';
$unit        = 'mm';
// $hoja        = 'letter';

// Tipo de letra
$tipo_letra     = 'Arial';
$estilo         = 'B';
$tamanio_letra  = 7;
// Definimos los márgenes y las medidas de los cards

// Configuracion
$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 7
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$margen_x    = $registro['valor_configuracion']; // CARD 1

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 8
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$config_margen_y = $registro['valor_configuracion']; // Condig Superior 1
$margen_y    = $registro['valor_configuracion']; // CARD 1

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 9
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$margen_x2   = $registro['valor_configuracion']; // CARD 2

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 10
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$config_margen_y2 = $registro['valor_configuracion']; // Condig Superior 2
$margen_y2   = $registro['valor_configuracion']; // CARD 2

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 11
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$card_width  = $registro['valor_configuracion']; // ALTO

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 12
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$card_height = $registro['valor_configuracion']; // ANCHO


$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 14
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$hoja_ancho = $registro['valor_configuracion']; // ANCHO

$sqlConf="SELECT c.valor_configuracion
FROM configuraciones c
WHERE c.id_configuracion = 16
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$hoja_alto = $registro['valor_configuracion']; // ALTO
$hoja      = array($hoja_ancho, $hoja_alto); // (ancho, alto)

$radio_borde = 5;
$interlineado = 0;
/*****************************************************************************/

$pdf = new FPDF($orientation, $unit, $hoja);
$pdf->AddPage();

// Configuración de Texto
$pdf->SetFont($tipo_letra, $estilo, $tamanio_letra);

// Codigo de CIUDAD
$global_agencia = $_COOKIE["global_agencia"];

// Lista de Codigos Seleccionados
$codigos = empty($_GET['codigos']) ? '' : $_GET['codigos'];
// Definimos las variables con los datos de ejemplo
$cod_ingreso_almacen = empty($_GET['cod_ingreso_almacen']) ? '' : $_GET['cod_ingreso_almacen'];

// Cantidad de Tickets
$cantidad_tickets = isset($_GET['cantidad_tickets']) ? $_GET['cantidad_tickets'] : 1;

$indiceImpar=1;
$indicePar=2;

$numero = 0;


$query_fil = empty($codigos) 
                ? "AND ida.cod_ingreso_almacen = '$cod_ingreso_almacen'" 
                : "AND CONCAT(ida.cod_ingreso_almacen, '_', ma.codigo_material, '_', ida.lote) IN ($codigos)";

/**
 * LISTA DE INGRESO DE PRODUCTOS
 */
$sqlConf = "SELECT ma.codigo_material, ma.descripcion_material, ROUND(p.precio, 2) as precio, ida.lote, ida.cod_ingreso_almacen, ida.costo_promedio as costo
            FROM material_apoyo ma 
            LEFT JOIN ingreso_detalle_almacenes ida ON ida.cod_material = ma.codigo_material
            LEFT JOIN precios p ON p.codigo_material = ma.codigo_material
            WHERE p.cod_ciudad = '$global_agencia'
            AND p.cod_precio = 1
            $query_fil";
// echo $sqlConf;

$respConf = mysqli_query($enlaceCon, $sqlConf);
while ($registro = mysqli_fetch_assoc($respConf)) {
    // for($i = 0; $i < $cantidad_tickets; $i++){
    $cod_ingreso_almacen = $registro['cod_ingreso_almacen'];
    $nombre_producto     = $registro['descripcion_material'];
    $codigo_general      = $registro['codigo_material'];
    $precio              = $registro['precio'];
    $costo               = $registro['costo'];
    $lote                = $registro['lote'];
    $nombre_barra        = $cod_ingreso_almacen.'_'.$codigo_general.'_'.$lote;
    
    $numero = $numero + 1;
    // Dibujamos la primera card
    $pdf->RoundedRect($margen_x, $margen_y, $card_width, $card_height, $radio_borde);

    // ****************
    // Valor iniciarl
    $pdf->setY($margen_y+2);
    /*###########################################################################*/ 
    $margen_seg_y = $pdf->getY();
    // TEXTO PRIMERA COLUMNA
    /*****************************************************************************/
    $pdf->SetFont($tipo_letra, $estilo, $tamanio_letra);
    $pdf->setX($margen_x);
    $pdf->multiCell($card_width - 2, 3, utf8_decode($nombre_producto), 0, 'C', false);


    // # ETIQUETA
    $pdf->setY($margen_seg_y + $card_height - 30);
    $pdf->setX($card_width-8);
    //$pdf->MultiCell($card_width, 3, $indiceImpar, 0, '');
    $indiceImpar=$indiceImpar+2;

    $pdf->Ln();

    $pdf->setY($margen_seg_y + 12);
    $y = $pdf->getY();
    // $pdf->Image('barcode.php?text='.$codigo_general.'&size=40&codetype=Code39&print=true', $margen_x+2, $y, $card_width-5, ($card_height/2));
    // $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_x+2, $y, $card_width-5, ($card_height/2));
    $pdf->Image('codigo_barra/'.$nombre_barra.'.png', $margen_x, $y-3, $card_width, ($card_height/2)-6, 'PNG');
    $pdf->Ln();
    // PRECIO
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($margen_x+1);
    $pdf->multiCell($card_width, 3, utf8_decode("P: ".$precio), 0, 'B', false);
    // COSTO FALSE
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($card_width - 17);
    //$pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo." / ".$numero), 0, 'L');
    $pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo), 0, 'L');
    $pdf->Ln();

    // CODIGO
    $pdf->setY($margen_seg_y + $card_height-10);
    $margen_seg_x_code = (($card_width)/2) - 10;
    $pdf->setX($margen_seg_x_code);
    $pdf->SetFont($tipo_letra, $estilo, 10);
    $pdf->multiCell($card_width, 3, utf8_decode($cod_ingreso_almacen.'|'.$codigo_general. '|' . $lote), 0, 'B', false);
    $pdf->Ln();


    if ($registro = mysqli_fetch_assoc($respConf)) {
        // Dibujamos la segunda card
        $pdf->RoundedRect($margen_x + $card_width + $margen_x2, $margen_y2, $card_width, $card_height, $radio_borde);
        
        $cod_ingreso_almacen = $registro['cod_ingreso_almacen'];
        $nombre_producto     = $registro['descripcion_material'];
        $codigo_general      = $registro['codigo_material'];
        $precio              = $registro['precio'];
        $costo               = $registro['costo'];
        $lote                = $registro['lote'];
        $nombre_barra        = $cod_ingreso_almacen.'_'.$codigo_general.'_'.$lote;
        // ****************
        // Valor iniciarl
        $pdf->SetFont($tipo_letra, $estilo, $tamanio_letra);
        $pdf->setY($margen_y2+2);
        /*###########################################################################*/
        $margen_seg_y = $pdf->getY();
        /*****************************************************************************/
        // TEXTO SEGUNDA COLUMNA
        /*****************************************************************************/
        $numero = $numero + 1;
        $margen_seg_x = $margen_x + $card_width + $margen_x2;
        $pdf->setY($margen_seg_y);
        $pdf->setX($margen_seg_x);
        $pdf->multiCell($card_width - 2, 3, utf8_decode($nombre_producto), 0, 'C', false);


        // # ETIQUETA
        $pdf->setY($margen_seg_y + $card_height - 30);
        $pdf->setX($card_width + 45);
        //$pdf->MultiCell($card_width, 3, $indicePar, 0, '');
        $indicePar=$indicePar+2;

        $pdf->Ln();

        $pdf->setY($margen_seg_y + 12);
        $y = $pdf->getY();
        // $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_seg_x, $y, $card_width, ($card_height/2)+5);
        $pdf->Image('codigo_barra/'.$nombre_barra.'.png', $margen_seg_x, $y-3, $card_width, ($card_height/2)-6, 'PNG');
        $pdf->Ln();

        // PRECIO
        $pdf->setY($margen_seg_y + $card_height-7);
        $pdf->setX($margen_seg_x+1);
        $pdf->multiCell($card_width, 3, utf8_decode("P: ".$precio), 0, 'B', false);
        // COSTO FALSE
        $pdf->setY($margen_seg_y + $card_height-7);
        $pdf->setX($margen_seg_x + $card_width - 17);
        //$pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo." / ".$numero), 0, 'L');
        $pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo), 0, 'L');
        $pdf->Ln();

        // CODIGO
        $pdf->setY($margen_seg_y + $card_height-10);
        $margen_seg_x = ($margen_x + $card_width + $margen_x2) + $margen_seg_x_code - 3;
        $pdf->setX($margen_seg_x);
        $pdf->SetFont($tipo_letra, $estilo, 10);
        $pdf->multiCell($card_width, 3, utf8_decode($cod_ingreso_almacen.'|'.$codigo_general. '|' . $lote), 0, 'B', false);
        $pdf->Ln();
        
        /*****************************************************************************/
        /*###########################################################################*/
        // if ($i < $cantidad_tickets - 1) {
        //     $pdf->AddPage();
        // }

        // Siguiente ticket
        $margen_y  += $card_height + 2 + $config_margen_y;
        $margen_y2 += $card_height + 2 + $config_margen_y2;
    }
    // if ($i < $cantidad_tickets - 1) {
        // $pdf->AddPage();
    // }
}

// $pdf->Output('F', 'mi_pdf_con_codigo_de_barras.pdf'); // Guardar el PDF en el servidor con el nombre "mi_pdf_con_codigo_de_barras.pdf"
$pdf->Output('I', 'mi_pdf_con_codigo_de_barras.pdf'); // Mostrar el PDF en el navegador
?>