<?php
require('assets/fpdf/fpdf.php');
require('assets/php-barcode-generator-main/src/BarcodeGeneratorPNG.php');

$pdf = new FPDF();
$pdf->AddPage();

$generator = new BarcodeGeneratorPNG();
// $barcode = $generator->getBarcode('123456789', $generator::TYPE_CODE_128);

// $pdf->Image('@' . base64_encode($barcode), 10, 10, 50);

$pdf->Output();
?>