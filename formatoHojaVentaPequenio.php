
<?php

    date_default_timezone_set('America/La_Paz');


    require('fpdf.php');
    require('conexionmysqlipdf.inc');
    require('funciones.php');
    require('NumeroALetras.php');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $cod_salida_almacen = empty($_GET["cod_salida_almacen"]) ? '' : $_GET["cod_salida_almacen"];
    
    /***********************************************************************************/
    /*                          Datos Generales de la Factura                          */
    /***********************************************************************************/
    $sqlSalida = "SELECT sa.nit, 
                    sa.razon_social,
                    sa.observaciones, 
                    sa.nro_correlativo, 
                    tv.nombre_tipoventa as tipo_venta,
                    tp.nombre_tipopago as tipo_pago,
                    CONCAT(cli.nombre_cliente, ' ', cli.paterno) as cliente,
                    CONCAT(f.nombres, ' ', f.paterno, ' ', f.materno) as funcionario,
                    sa.monto_total,
                    sa.descuento,
                    sa.monto_final
                FROM salida_almacenes sa
                LEFT JOIN funcionarios f ON f.codigo_funcionario = sa.cod_chofer
                LEFT JOIN clientes cli ON cli.cod_cliente = sa.cod_cliente
                LEFT JOIN tipos_venta tv ON tv.cod_tipoventa = sa.cod_tipoventa
                LEFT JOIN tipos_pago tp ON tp.cod_tipopago = sa.cod_tipopago
                LEFT JOIN tipos_docs td ON td.codigo = sa.cod_tipo_doc
                WHERE sa.cod_salida_almacenes = '$cod_salida_almacen'
                LIMIT 1";
    // echo $sqlDatosVenta;
    $respSalida=mysqli_query($enlaceCon, $sqlSalida);
    while($dataSalida = mysqli_fetch_array($respSalida)){
        $cab_nit             = $dataSalida['nit'];
        $cab_razon_social    = $dataSalida['razon_social'];
        $cab_observaciones   = $dataSalida['observaciones'];
        $cab_nro_correlativo = $dataSalida['nro_correlativo'];
        $cab_tipo_venta      = $dataSalida['tipo_venta'];
        $cab_tipo_pago       = $dataSalida['tipo_pago'];
        $cab_cliente         = $dataSalida['cliente'];
        $cab_funcionario     = $dataSalida['funcionario'];
        $cab_monto_total     = $dataSalida['monto_total'];
        $cab_descuento       = $dataSalida['descuento'];
    }
    // Tamaño Carta
    // $pdf = new FPDF($orientation='P',$unit='mm', 'Letter');
    // Media Hoja Carta
    $pdf = new FPDF($orientation='L',$unit='mm', 'Letter');
    $pdf->AddPage();
    #Establecemos los márgenes izquierda, arriba y derecha:
    $pdf->SetMargins(10, 25 , 30);


    // Posición inicial EJE X
    $ejeX = 0;
    for ($col = 1; $col <= 3; $col++) {
        /************************************/
        /*              TITULO              */
        /************************************/
        $pdf->SetFont('Arial','B',13);    
        $textypos = 5;
        $pdf->setY(5);$pdf->setX($ejeX + 28);
        $pdf->Cell(5, $textypos, utf8_decode("NOTA DE REMISIÓN"));
        
        // Cambiar el color del texto a rojo
        $pdf->SetTextColor(255, 0, 0);
        $pdf->SetFont('Arial','B',12);
        $pdf->setY(10);$pdf->setX($ejeX + 46);
        $pdf->Cell(5,$textypos,utf8_decode("N° $cab_nro_correlativo"),0,0,'C');
        // Restablecer el color del texto a negro
        $pdf->SetTextColor(0, 0, 0);


        // Imagenes
        $pdf->Image('assets/imagenes/pdf_img3.png', $ejeX + 60, 12, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img4.png', $ejeX + 77, 12, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img5.png', $ejeX + 60, 20, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img6.png', $ejeX + 77, 20, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img8.png', $ejeX + 43, 28, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img9.png', $ejeX + 77, 28, 15, 7);
        $pdf->Image('assets/imagenes/pdf_img2.png', $ejeX + 60, 28, 15, 7);
        

        /************************************/
        /*              LOGO                */
        /************************************/
        $pdf->setY(35);$pdf->setX($ejeX + 5);
        // $pdf->Image('pruebaImg.jpg' , 10, 20, 20, 20,'JPG', 'http://www.desarrolloweb.com');

        /********************************************************/
        /*              Datos Generales de la Factura           */
        /********************************************************/
        // Titulos
        $pdf->Image('assets/imagenes/pdf_img1.png', $ejeX + 3, 10, 30, 15);
        $pdf->SetFont('Arial','B',8);    
        $pdf->setY(25); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos, utf8_decode("Av. 6 de Marzo Nro. 250")); 
        $pdf->setY(29); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos,utf8_decode("Cel.: 74276636 - 75800783"));
        $pdf->setY(33); $pdf->setX($ejeX + 3);
        $pdf->Cell(5, $textypos,utf8_decode("El Alto, La Paz, Bolivia"));

        
        // Información
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(38);$pdf->setX($ejeX + 3);
        $pdf->Cell(20,$textypos,utf8_decode("Señor(es):"), 'LTB', 0, 'L'); 
        $pdf->setY(43);$pdf->setX($ejeX + 3);
        $pdf->Cell(20,$textypos,utf8_decode("Vendedor:"), 'L', 0, 'L');

        $pdf->SetFont('Arial','',7);    
        $pdf->setY(38);$pdf->setX($ejeX + 16);
        $pdf->Cell(55,$textypos,utf8_decode($cab_cliente), 'TB', 0, 'L');
        $pdf->setY(43);$pdf->setX($ejeX + 16);
        $pdf->Cell(40,$textypos,utf8_decode($cab_funcionario), 'TB', 0, 'L');  
        // $pdf->MultiCell(40, 3, utf8_decode($cab_funcionario), 0, 'L');
        
        $pdf->SetFont('Arial','B',7);    
        $pdf->setY(38);$pdf->setX($ejeX + 62);
        $pdf->Cell(20,$textypos,utf8_decode("NIT/CI/CEX:"), 'TB', 0, 'L');
        $pdf->setY(43);$pdf->setX($ejeX + 62);
        $pdf->Cell(20,$textypos,utf8_decode("Tipo Venta :"), 0, 'L');
        
        $pdf->SetFont('Arial','',7);    
        $pdf->setY(38); $pdf->setX($ejeX + 76.5);
        $pdf->Cell(15, $textypos, $cab_nit, 'TRB', 0, 'L');
        $pdf->setY(43);$pdf->setX($ejeX + 76.5);
        $pdf->Cell(15, $textypos, $cab_tipo_doc, 'R', 0, 'L');
        $pdf->Ln();

        // DETALLE DE ITEMS
        $header = array("CANT", "DETALLE", "P.U.", "SUB-TOTAL");
        // Column widths
        $w = array(2, 49.5, 7.5, 7.5);
        $pdf->SetFont('Arial','B',6);    
        // Header
        $add_size = 5.5;
        $pdf->setX($ejeX + 3);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i] + $add_size, 5, utf8_decode($header[$i]), 'LTRB', 0, 'C');
            $pdf->Ln();
        // Data
        $total = 0;
        $pdf->SetFont('Arial','',6);

        /********************************************/
        /*              DETALLE DE SALIDA           */
        /********************************************/
        $sqlSalidaDet = "SELECT
                            m.codigo_material,
                            sda.orden_detalle,
                            m.descripcion_material,
                            sda.precio_unitario,
                            sda.cantidad_unitaria,
                            sda.descuento_unitario,
                            sda.monto_unitario 
                        FROM salida_detalle_almacenes sda
                        LEFT JOIN material_apoyo m ON m.codigo_material = sda.cod_material
                        WHERE m.codigo_material = sda.cod_material 
                        AND sda.cod_salida_almacen = '$cod_salida_almacen'
                        ORDER BY sda.orden_detalle DESC";
        // echo $sqlDatosVenta;
        $respSalidaDet = mysqli_query($enlaceCon, $sqlSalidaDet);
        $montoTotal = 0;
        while($dataSalidaDet = mysqli_fetch_array($respSalidaDet)){
            $codigo_material      = $dataSalidaDet['codigo_material'];
            $orden_detalle        = $dataSalidaDet['orden_detalle'];
            $descripcion_material = $dataSalidaDet['descripcion_material'];
            $precio_unitario      = $dataSalidaDet['precio_unitario'];
            $cantidad_unitaria    = $dataSalidaDet['cantidad_unitaria'];
            $descuento_unitario   = $dataSalidaDet['descuento_unitario'];
            $monto_unitario       = $dataSalidaDet['monto_unitario'];

            
            $row_index = 1;

            $pdf->setX($ejeX + 3);
            $y = $pdf->getY();
            $x = $pdf->GetX();

            // * CANTIDAD
            $pdf->SetFont('Arial','',6.5);
            $cantidad_unitaria = intval($cantidad_unitaria);
            $pdf->multiCell(7.5, 5 * $row_index, $cantidad_unitaria, 1, 'B', false);
            $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
            $pdf->SetY($y); // regresar a fila anterior
            $pdf->setX($x + 7.5); // regresar a columna anterior mas espacio de la columna
            // * DESCRIPCIÓN
            $pdf->SetFont('Arial','',6.5);
            $y = $pdf->getY();
            $x = $pdf->GetX();
            $nombreProductoX = strtoupper( substr($descripcion_material,0,40) );
            $pdf->multiCell(55, 5 * $row_index, utf8_decode($nombreProductoX), 1, 'B', false);
            $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
            $pdf->SetY($y); // regresar a fila anterior
            $pdf->setX($x + 55); // regresar a columna anterior mas espacio de la columna
            // * PRECIO UNITARIO
            $pdf->SetFont('Arial','',7);
            $y = $pdf->getY();
            $x = $pdf->GetX();
            $pdf->multiCell(13, 5 * $row_index, utf8_decode($precio_unitario), 1, 'R');
            $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
            $pdf->SetY($y); // regresar a fila anterior
            $pdf->setX($x + 13); // regresar a columna anterior mas espacio de la columna
            // * SUBTOTAL
            $pdf->SetFont('Arial','',7);
            $y = $pdf->getY();
            $x = $pdf->GetX();
            $pdf->multiCell(13, 5 * $row_index, utf8_decode(redondear2($monto_unitario)), 1, 'R');
            $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
            $pdf->SetY($y); // regresar a fila anterior
            $pdf->setX($x + 13); // regresar a columna anterior mas espacio de la columna

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
        $pdf->SetFont('Arial','B',6);  
        $pdf->setX($ejeX + 3);
        $pdf->Cell(62.5, 6, ("Son:".$txtMonto." ".$montoDecimal."/100 Bolivianos"), 1, 0, 'L');

        $pdf->SetFont('Arial','',6);
        $pdf->Cell(13, 6, utf8_decode("SubTotal Bs:"), 1, 0, 'R');
        $pdf->Cell(13, 6, number_format($montoTotal, 2, ".",","), 1, 0, 'R');
        $pdf->Ln();

        
        /*********************************************************/
        /*        "ENTREGUE CONFORME" y "RECIBÍ CONFORME"        */
        /*********************************************************/
        $y = $pdf->getY();
        $pdf->setY($y + 15);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->setX($ejeX + 8);
        $pdf->Cell(50, 10, utf8_decode("ENTREGUE CONFORME"), 0, 0, 'L');
        $pdf->setX($ejeX + 8);
        $pdf->Cell(35, 0, '', 'T');

        $pdf->setX($ejeX + 55);
        $pdf->Cell(50, 10, utf8_decode("RECIBÍ CONFORME"), 0, 0, 'L');
        $pdf->setX($ejeX + 52);
        $pdf->Cell(35, 0, '', 'T');
        
        $ejeX += 93;
    }

    

    // Limpiar el buffer de salida antes de generar el PDF
    ob_end_clean();
    $fecha = date('d-m-Y').'_cotizacion.pdf';
    $pdf->Output($fecha, "I");
?>
