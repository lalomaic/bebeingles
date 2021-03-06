<?php
$h=5;
$mes = array("","Enero", "Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiebre","Octubre","Noviembre","Diciembre");
$pdf=new Fpdf_cl_factura();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Image(base_url().'images/logo_pdf.jpg',10,10,95);
$pdf->Cell(0,$h,utf8_decode('Noelia del Carmen Castañeda Hidalgo'),0,1,'R');
$pdf->Cell(0,$h,utf8_decode('R.F.C.: CAHN-671111-4J5'),0,1,'R');
$pdf->ln(4);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,$h,utf8_decode('Periferico	Paseo de la República #5030, Interior 11,'),0,1,'R');
$pdf->Cell(0,$h,utf8_decode('Col. Jardines del Rincón, C.P. 58270,'),0,1,'R');
$pdf->Cell(0,$h,utf8_decode('Morelia, Michoacán, México.'),0,1,'R');
$pdf->Cell(0,$h,utf8_decode('Teléfono (443) 312-21-07'),0,1,'R');

$pdf->Rect(10, 47, 145, 26,$style="");
$pdf->Rect(155, 47, 50, 26,$style="");
$pdf->Rect(155, 57, 50, 16,$style="");
$pdf->ln($h);
$pdf->SetFont('Arial','B',10);
//$pdf->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
$pdf->Cell(145,0,"FACTURADO A:",0,0,'L');
$pdf->Cell(50,0,utf8_decode('FACTURA'),0,0,'C');
$pdf->ln($h);
$pdf->Cell(145,0,utf8_decode($cliente->razon_social),0,0,'L');
$pdf->Cell(15,0,utf8_decode('Folio:'),0,0,'L');
$pdf->SetTextColor(255,0,0);
$pdf->Cell(30,0,$factura->folio_factura,0,0,'R');
$pdf->SetTextColor(0,0,0);

$pdf->ln($h);
$pdf->SetFont('Arial','',8);
$pdf->Cell(145,0,
        $cliente->calle.
        ($cliente->numero_exterior != "" ? " No. ext: ".$cliente->numero_exterior : "").
        ($cliente->numero_interior != "" ? " No. int: ".$cliente->numero_interior : "").
        ($cliente->colonia != "" ? " Colonia: ".$cliente->colonia : ""),0,0,'L');
$pdf->Cell(40,0,utf8_decode('LUGAR Y FECHA DE EXPEDICIÓN'),0,0,'L');

$pdf->ln($h);
$pdf->Cell(145,0,utf8_decode(
        ( $cliente->codigo_postal != "" ? "C.P.: ".$cliente->codigo_postal." " : "").
        ( $cliente->municipio != "" ? " ".$cliente->municipio.", " : "").
        ( $cliente->estado != "" ? " ".$cliente->estado." " : "").
        ( $cliente->telefono != "" ? "TELÉFONO: ".$cliente->telefono : "")),0,0,'L');
$pdf->Cell(50,0,utf8_decode('Morelia, Michoacán a'),0,0,'C');

$pdf->ln($h);
$pdf->Cell(145,0,"R.F.C.: ".$factura->rfc,0,0,'L');
$fecha = strtotime($factura->fecha);
$pdf->Cell(50,0,date("d",$fecha)." de ".$mes[(int)date("m",$fecha)]." de ".date("Y",$fecha),0,0,'C');

$pdf->ln($h);
$anchos=array(20,120,15,20,20);
$pdf->SetWidths($anchos);
$pdf->SetAligns(array('C','C','C','C'));
$pdf->SetFillColor(230,230,250);
$pdf->Row(array('CANTIDAD',utf8_decode('DESCRIPCIÓN'),'UNIDAD','P. UNITARIO',"IMPORTE"));
$pdf->SetFillColor(255,255,255);
$pdf->SetAligns(array('C','L','C','R','R'));
$pdf->SetFont('Times','',8);
$total = 0;

foreach($salidas as $i => $s) {
    $pdf->Row(array(
        number_format($s->cantidad).$i,
        $s->descripcion." ".$s->numero_mm,
        $s->medida,
        "$".number_format($s->costo_unitario),
        "$".number_format($s->costo_total)));
    //$total += $s->costo_total;
}
$pdf->SetFillColor(230,230,250);
//$folio_certificado->ruta_certificado
$pdf->Image(base_url().$folio_certificado->ruta_certificado,12,222,31,31);
//$pdf->Rect($x, $y, $w, $h, $style);
$pdf->Rect(10, 220, 35, 35,$style="");
$pdf->Rect(10, 255, 35, 15,$style="");
$pdf->Rect(45, 220, 160, 35,$style="");
$pdf->Rect(45, 255, 160, 15,$style="");

$pdf->SetFont('Times','B',10);
//$pdf->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link)
$pdf->SetY(220);
$pdf->SetX(45);
$pdf->Cell(120,5,"CANTIDAD CON LETRA",0,0,"L");
$pdf->Cell(20,5,"SUBTOTAL",1,0,"C");
$pdf->Cell(20,5,number_format($factura->subtotal),1,0,"C",1);
$pdf->ln($h);
$pdf->SetX(45);
$pdf->Cell(120,5, utf8_decode($factura->monto_letras),0,0,"L");
$pdf->Cell(20,5,"IVA",1,0,"C");
$pdf->Cell(20,5,number_format($factura->iva_total),1,0,"C",1);
$pdf->ln($h);
$pdf->SetX(45);
$pdf->Cell(120,5, "",0,0,"L");
$pdf->Cell(20,5,"TOTAL",1,0,"C");
$pdf->Cell(20,5,"$".number_format($factura->monto_total,2),1,0,"C",1);
$pdf->ln($h);
$pdf->SetFont('Times','B',8);

$pdf->Output();
unset($pdf)

?>
