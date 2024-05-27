
<?php

    date_default_timezone_set('America/La_Paz');


    require('fpdf.php');
    require('conexionmysqlipdf.inc');
    require('funciones.php');
    require('NumeroALetras.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $cod_cotizacion = empty($_GET["cod_cotizacion"]) ? '' : $_GET["cod_cotizacion"];
    
    /***********************************************************************************/
    /*                          Datos Generales de la Factura                          */
    /***********************************************************************************/
    $sqlCotizacion = "SELECT c.nit, 
                    c.razon_social,
                    c.observaciones, 
                    c.nro_correlativo, 
                    tv.nombre_tipoventa as tipo_venta,
                    tp.nombre_tipopago as tipo_pago,
                    CONCAT(cli.nombre_cliente, ' ', cli.paterno) as cliente,
                    CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as funcionario,
                    c.monto_total,
                    c.descuento,
                    c.monto_final
                FROM cotizaciones c
                LEFT JOIN funcionarios f ON f.codigo_funcionario = c.cod_chofer
                LEFT JOIN clientes cli ON cli.cod_cliente = c.cod_cliente
                LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = c.cod_tipoventa
                LEFT JOIN tipos_pago tp ON tp.cod_tipopago = c.cod_tipopago
                WHERE c.codigo = '$cod_cotizacion'
                LIMIT 1";
    // echo $sqlDatosVenta;
    $respCotizacion=mysqli_query($enlaceCon, $sqlCotizacion);
    while($dataCotizacion = mysqli_fetch_array($respCotizacion)){
        $cab_nit             = $dataCotizacion['nit'];
        $cab_razon_social    = $dataCotizacion['razon_social'];
        $cab_observaciones   = $dataCotizacion['observaciones'];
        $cab_nro_correlativo = $dataCotizacion['nro_correlativo'];
        $cab_tipo_venta      = $dataCotizacion['tipo_venta'];
        $cab_tipo_pago       = $dataCotizacion['tipo_pago'];
        $cab_cliente         = $dataCotizacion['cliente'];
        $cab_funcionario     = $dataCotizacion['funcionario'];
        $cab_monto_total     = $dataCotizacion['monto_total'];
        $cab_descuento       = $dataCotizacion['descuento'];
    }
    // Tamaño Carta
    // $pdf = new FPDF($orientation='P',$unit='mm', 'Letter');
    // Media Hoja Carta
    $pdf = new FPDF($orientation='P',$unit='mm', 'Letter');
    $pdf->AddPage();
    #Establecemos los márgenes izquierda, arriba y derecha:
    $pdf->SetMargins(10, 25 , 30);

    /************************************/
    /*              TITULO              */
    /************************************/
    $pdf->SetFont('Arial','B',13);    
    $textypos = 5;
    $pdf->setY(6);$pdf->setX(90);
    $pdf->Cell(5,$textypos,"PROFORMA");
    
    // Cambiar el color del texto a rojo
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetFont('Arial','B',12);
    $pdf->setY(10);$pdf->setX(100);
    $pdf->Cell(5,$textypos,utf8_decode("N° $cab_nro_correlativo"),0,0,'C');
    // Restablecer el color del texto a negro
    $pdf->SetTextColor(0, 0, 0);


    // Imagenes
    $pdf->Image('assets/imagenes/pdf_img2.png', 130, 5, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img3.png', 155, 5, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img4.png', 180, 5, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img8.png', 110, 15, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img5.png', 130, 15, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img6.png', 155, 15, 20, 10);
    $pdf->Image('assets/imagenes/pdf_img9.png', 180, 15, 20, 10);
    

    /************************************/
    /*              LOGO                */
    /************************************/
    $pdf->setY(35);$pdf->setX(10);
    // $pdf->Image('pruebaImg.jpg' , 10, 20, 20, 20,'JPG', 'http://www.desarrolloweb.com');

    /********************************************************/
    /*              Datos Generales de la Factura           */
    /********************************************************/
    // Titulos
    $pdf->Image('assets/imagenes/pdf_img1.png', 7, 12, 35, 15);
    $pdf->SetFont('Arial','B',10);    
    $pdf->setY(12); $pdf->setX(42);
    $pdf->Cell(5, $textypos, utf8_decode("Av. 6 de Marzo Nro. 250")); 
    $pdf->setY(15); $pdf->setX(42);
    $pdf->Cell(5, $textypos,utf8_decode("Cel.: 74276636 - 75800783"));
    $pdf->setY(18); $pdf->setX(42);
    $pdf->Cell(5, $textypos,utf8_decode("El Alto, La Paz, Bolivia"));

    
    // Información
    $pdf->SetFont('Arial','B',7);    
    $pdf->setY(30);$pdf->setX(10);
    $pdf->Cell(30,$textypos,utf8_decode("Señor(es):"), 'LTB', 0, 'L'); 
    $pdf->setY(35);$pdf->setX(10);
    $pdf->Cell(20,$textypos,utf8_decode("Vendedor:"), 'L', 0, 'L');

    $pdf->SetFont('Arial','',7);    
    $pdf->setY(30);$pdf->setX(25);
    $pdf->Cell(130,$textypos,utf8_decode($cab_cliente), 'TB', 0, 'L');  
    $pdf->setY(35);$pdf->setX(25);
    $pdf->Cell(130,$textypos,utf8_decode($cab_funcionario), 'TB', 0, 'L');  
    // $pdf->MultiCell(120, 3, utf8_decode($cab_funcionario), 0, 'L');
    
    $pdf->SetFont('Arial','B',7);    
    $pdf->setY(30);$pdf->setX(150);
    $pdf->Cell(30,$textypos,utf8_decode("NIT/CI/CEX:"), 'TB', 0, 'L');
    $pdf->setY(35);$pdf->setX(150);
    $pdf->Cell(20,$textypos,utf8_decode("Tipo Venta :"), 0, 'L');
    
    $pdf->SetFont('Arial','',7);    
    $pdf->setY(30); $pdf->setX(170);
    $pdf->Cell(30, $textypos, $cab_nit, 'TRB', 0, 'L');
    $pdf->setY(35);$pdf->setX(170);
    $pdf->Cell(30, $textypos, $cab_tipo_venta, 'R', 0, 'L');
    $pdf->Ln();

    // DETALLE DE ITEMS
    $header = array("CANTIDAD", "DETALLE", "PRECIO UNITARIO", "SUB-TOTAL");
    // Column widths
    $w = array(26, 80, 25, 25);
    $pdf->SetFont('Arial','B',8);    
    // Header
    $add_size = 8.5;
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i] + $add_size, 7, utf8_decode($header[$i]), 'LTRB', 0, 'C');
    $pdf->Ln();
    // Data
    $total = 0;
    $pdf->SetFont('Arial','',6);

    /************************************************/
    /*              DETALLE DE COTIZACIÓN           */
    /************************************************/
    $sqlCotizacionDet = "SELECT
                        m.codigo_material,
                        cd.orden_detalle,
                        m.descripcion_material,
                        cd.precio_unitario,
                        cd.cantidad_unitaria,
                        cd.descuento_unitario,
                        cd.monto_unitario 
                    FROM cotizaciones_detalle cd
                    LEFT JOIN material_apoyo m ON m.codigo_material = cd.cod_material
                    WHERE m.codigo_material = cd.cod_material 
                    AND cd.cod_cotizacion = '$cod_cotizacion'
                    ORDER BY cd.orden_detalle DESC";
    // echo $sqlDatosVenta;
    $respCotizacionDet = mysqli_query($enlaceCon, $sqlCotizacionDet);
    $montoTotal = 0;
    while($dataCotizacionDet = mysqli_fetch_array($respCotizacionDet)){
        $codigo_material      = $dataCotizacionDet['codigo_material'];
        $orden_detalle        = $dataCotizacionDet['orden_detalle'];
        $descripcion_material = $dataCotizacionDet['descripcion_material'];
        $precio_unitario      = $dataCotizacionDet['precio_unitario'];
        $cantidad_unitaria    = $dataCotizacionDet['cantidad_unitaria'];
        $descuento_unitario   = $dataCotizacionDet['descuento_unitario'];
        $monto_unitario       = $dataCotizacionDet['monto_unitario'];

        
        $row_index = 1;

        $y = $pdf->getY();
        $x = $pdf->GetX();

        // * CANTIDAD
        $pdf->SetFont('Arial','',7);
        $pdf->multiCell(34.5, 5 * $row_index, $cantidad_unitaria, 1, 'B', false);
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->SetX($x + 34.5); // regresar a columna anterior mas espacio de la columna
        // * DESCRIPCIÓN
        $y = $pdf->getY();
        $x = $pdf->GetX();
        $nombreProductoX = strtoupper( substr($descripcion_material,0,40) );
        $pdf->multiCell(88.5, 5 * $row_index, utf8_decode($nombreProductoX), 1, 'B', false);
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->SetX($x + 88.5); // regresar a columna anterior mas espacio de la columna
        // * PRECIO UNITARIO
        $y = $pdf->getY();
        $x = $pdf->GetX();
        $pdf->multiCell(33.5, 5 * $row_index, utf8_decode($precio_unitario), 1, 'R');
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->SetX($x + 33.5); // regresar a columna anterior mas espacio de la columna
        // * SUBTOTAL
        $y = $pdf->getY();
        $x = $pdf->GetX();
        $pdf->multiCell(33.5, 5 * $row_index, utf8_decode(redondear2($monto_unitario)), 1, 'R');
        $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
        $pdf->SetY($y); // regresar a fila anterior
        $pdf->SetX($x + 33.5); // regresar a columna anterior mas espacio de la columna

        $pdf->Ln();

        $montoTotal += ($cantidad_unitaria * $precio_unitario) - $descuento_unitario;
    }

    /****************************************/
    /*              PIE DE PAGINA           */
    /****************************************/
    $y = $pdf->getY();
    $montoFinal=number_format($montoTotal,2,'.','');
    $arrayDecimal=explode('.', $montoFinal);
    if(count($arrayDecimal)>1){
        list($montoEntero, $montoDecimal) = explode('.', $montoFinal);
    }else{
        list($montoEntero,$montoDecimal)=array($montoFinal,0);
    }

    if($montoDecimal==""){
        $montoDecimal="00";
    }
    $txtMonto=NumeroALetras::convertir($montoEntero);
    $pdf->setY($y);
    $pdf->SetFont('Arial','B',7);  
    $pdf->Cell(123, 6, ("Son: ".$txtMonto." ".$montoDecimal."/100 Bolivianos"), 1, 0, 'L');

    $pdf->SetFont('Arial','',7);
    $pdf->Cell(33.5, 6, utf8_decode("SUBTOTAL Bs:"), 1, 0, 'R');
    $pdf->Cell(33.5, 6, number_format($montoTotal, 2, ".",","), 1, 0, 'R');
    $pdf->Ln();

    
    /*********************************************************/
    /*        "ENTREGUE CONFORME" y "RECIBÍ CONFORME"        */
    /*********************************************************/
    $y = $pdf->getY();
    $pdf->setY($y + 20);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX(30);
    $pdf->Cell(50, 10, utf8_decode("ENTREGUE CONFORME"), 0, 0, 'L');
    $pdf->SetX(27);
    $pdf->Cell(50, 0, '', 'T');

    $pdf->SetX(128);
    $pdf->Cell(50, 10, utf8_decode("RECIBÍ CONFORME"), 0, 0, 'L');
    $pdf->SetX(120);
    $pdf->Cell(50, 0, '', 'T');


    // Limpiar el buffer de salida antes de generar el PDF
    ob_end_clean();
    $fecha = date('d-m-Y').'_cotizacion.pdf';
    $pdf->Output($fecha, "I");
?>
