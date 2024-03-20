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
WHERE c.id_configuracion = 13
ORDER BY c.id_configuracion ASC";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$registro = mysqli_fetch_array($respConf);
$registro['valor_configuracion'];
$hoja_alto = $registro['valor_configuracion']; // ALTO
$hoja      = array($hoja_ancho,$hoja_alto); // (ancho, alto)

$radio_borde = 5;
$interlineado = 0;
/*****************************************************************************/

$pdf = new FPDF($orientation,$unit, $hoja);
$pdf->AddPage();

// Configuración de Texto
$pdf->SetFont($tipo_letra, $estilo, $tamanio_letra);

// Definimos las variables con los datos de ejemplo
$nombre_producto = $_GET['nombre'];
$codigo_general  = $_GET['codigo'];
$precio          = $_GET['precio'];
$costo           = $_GET['costo'];
// Cantidad de Tickets
$cantidad_tickets = isset($_GET['cantidad_tickets']) ? $_GET['cantidad_tickets'] : 1;

$numero = 0;
for($i = 0; $i < $cantidad_tickets; $i++){
    $numero = $numero + 1;
    // Dibujamos la primera card
    $pdf->RoundedRect($margen_x, $margen_y, $card_width, $card_height, $radio_borde);
    // Dibujamos la segunda card
    $pdf->RoundedRect($margen_x + $card_width + $margen_x2, $margen_y2, $card_width, $card_height, $radio_borde);

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
    $pdf->Ln();

    $pdf->setY($margen_seg_y + 12);
    $y = $pdf->getY();
    // $pdf->Image('barcode.php?text='.$codigo_general.'&size=40&codetype=Code39&print=true', $margen_x+2, $y, $card_width-5, ($card_height/2));
    // $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_x+2, $y, $card_width-5, ($card_height/2));
    $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_x, $y-3, $card_width, ($card_height/2)-6, 'PNG');
    $pdf->Ln();
    // PRECIO
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($margen_x+1);
    $pdf->multiCell($card_width, 3, utf8_decode("P: ".$precio), 0, 'B', false);
    // COSTO FALSE
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($card_width - 12);
    $pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo." / ".$numero), 0, 'L');
    $pdf->Ln();


    // CODIGO
    $pdf->setY($margen_seg_y + $card_height-10);
    $margen_seg_x_code = (($card_width)/2)-3;
    $pdf->setX($margen_seg_x_code);
    $pdf->SetFont($tipo_letra, $estilo, 12);
    $pdf->multiCell($card_width, 3, utf8_decode($codigo_general), 0, 'B', false);
    $pdf->Ln();

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
    $pdf->Ln();

    $pdf->setY($margen_seg_y + 12);
    $y = $pdf->getY();
    // $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_seg_x, $y, $card_width, ($card_height/2)+5);
    $pdf->Image('codigo_barra/'.$codigo_general.'.png', $margen_seg_x, $y-3, $card_width, ($card_height/2)-6, 'PNG');
    $pdf->Ln();

    // PRECIO
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($margen_seg_x+1);
    $pdf->multiCell($card_width, 3, utf8_decode("P: ".$precio), 0, 'B', false);
    // COSTO FALSE
    $pdf->setY($margen_seg_y + $card_height-7);
    $pdf->setX($margen_seg_x + $card_width - 14);
    $pdf->MultiCell($card_width, 3,  utf8_decode("000".$costo." / ".$numero), 0, 'L');
    $pdf->Ln();

    // CODIGO
    $pdf->setY($margen_seg_y + $card_height-10);
    $margen_seg_x = ($margen_x + $card_width + $margen_x2) + $margen_seg_x_code-3;
    $pdf->setX($margen_seg_x);
    $pdf->SetFont($tipo_letra, $estilo, 12);
    $pdf->multiCell($card_width, 3, utf8_decode($codigo_general), 0, 'B', false);
    $pdf->Ln();
    /*****************************************************************************/
    /*###########################################################################*/
    if ($i < $cantidad_tickets - 1) {
        $pdf->AddPage();
    }
    // Siguiente ticket
    // $margen_y  += $card_height + 2 + $config_margen_y;
    // $margen_y2 += $card_height + 2 + $config_margen_y2;
}

// $pdf->Output('F', 'mi_pdf_con_codigo_de_barras.pdf'); // Guardar el PDF en el servidor con el nombre "mi_pdf_con_codigo_de_barras.pdf"
$pdf->Output('I', 'mi_pdf_con_codigo_de_barras.pdf'); // Mostrar el PDF en el navegador
?>