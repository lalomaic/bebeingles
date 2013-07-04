<?php

$h = 5;
$pdf = new Fpdf_multicell("P", 'mm', 'letter', 1);
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(0, 5, $title, 'B', 1, 'L');
$pdf->ln(2);

$x1 = $pdf->GetX();
$x2 = $x1 + 25;
$y = $pdf->GetY();
$borde = 1;

$generalesw = array(7, 16, 35, 35, 20, 20, 20, 16, 30);
$detallesh = array(7, 45, 100, 20, 20);
$detallesw = array(7, 15, 15, 15, 100, 20, 20);

$pdf->SetWidths(array(7, 16, 35, 35, 20, 20, 20, 16, 30));
$pdf->Cell(150);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(45, $h, "Id Poliza:", $borde, 1, 'C');
$pdf->Cell(150);
$pdf->SetFont('Times', '', 9);
$pdf->Cell(45, $h, $poliza->id, $borde, 1, 'C');
$pdf->Cell(150);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(45, $h, "No. Poliza:", $borde, 1, 'C');
$pdf->Cell(150);
$pdf->SetFont('Times', '', 9);
$pdf->Cell(45, $h, $poliza->folio_poliza, $borde, 1, 'C');
$pdf->Cell(150);
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(45, $h, "Fecha:", $borde, 1, 'C');
$pdf->Cell(150);
$pdf->SetFont('Times', '', 9);
$pdf->Cell(45, $h, date('d-m-Y', strtotime($poliza->fecha)), $borde, 1, 'C');
if ($poliza->numero_referencia != '') {
    $pdf->Cell(150);
    $pdf->SetFont('Times', 'B', 9);
    $pdf->Cell(45, $h, "Referencia:", $borde, 1, 'C');
    $pdf->Cell(150);
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(45, $h, $poliza->numero_referencia, $borde, 1, 'C');
}
$pdf->ln(3);
$pdf->Cell(25, $h, "Empresa:");
$pdf->Cell(0, $h, $poliza->empresa, $borde);
$pdf->ln($h + 1);
$pdf->Cell(25, $h, "Concepto:");
$pdf->Cell(0, $h, $poliza->concepto . $poliza->factura .
        ($poliza->pago_id > 0 && $poliza->automatico == 1 ?
                "(Id Pago: $poliza->pago_id, Id Factura Proveedor: $poliza->factura)" : ''), $borde);
$pdf->ln($h + 1);
$pdf->Cell(25, $h, "Espacio Fisico:");
$pdf->Cell(0, $h, $poliza->espacio, $borde);
$pdf->ln($h + 1);
$pdf->ln($h + 3);

// detalles de la factura
$pdf->SetWidths(array(7, 45, 100, 20, 20));
$pdf->SetAligns(array("L", "C", "L", "R", "R"));
$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(5);
$pdf->SetFillColor(230);
if ($detalles == false) {
    $pdf->Cell(0, 5, "Poliza sin detalles", 0, 1, 'C', true);
    $pdf->Output();
    return;
}
$pdf->Row(array("#", "Cuenta", "Nombre", "Debe.", "Haber"));
$pdf->SetFont('Times', '', 7);

$i = 1;
$iva = 0;
$iva_t = 0;
$subtotal_t = 0;
$debe_t = 0;
$haber_t = 0;
$dep = 0;
$d = array();
$pdf->SetAligns(array("L", "L", "L", "L", "L", "R", "R",));
$pdf->SetWidths(array(7, 15, 15, 15, 100, 20, 20));

foreach ($detalles as $detalle) {
    $pdf->Cell(5);
    if ($detalle->cobro > 0 or $detalle->cobro != '')
        $info = $detalle->tag . " $detalle->detalle Factura Id: $detalle->cl_factura_id";
    else if ($detalle->deposito != '') {
        $info = $detalle->tag . " Deposito Id: $detalle->deposito Ref: $detalle->referencia";
        $d[$dep] = $detalle->deposito;
        $dep+=1;
    } else
        $info = $detalle->tag . "$detalle->detalle";
    if ($i % 2 == 0)
        $pdf->SetFillColor(250);
    else
        $pdf->SetFillColor(255);
    $pdf->Row(array(
        $i++,
        $detalle->cta,
        $detalle->scta,
        $detalle->sscta,
        utf8_decode($info),
        $detalle->debe == 0 ? '' : number_format($detalle->debe, 2, ".", ","),
        $detalle->haber == 0 ? '' : number_format($detalle->haber, 2, ".", ",")
    ));
    $pdf->SetFillColor(255);
    $debe_t+=$detalle->debe;
    $haber_t+=$detalle->haber;
}
$pdf->Cell(5);
$pdf->Cell(52, $h, '', 'T');
$pdf->Cell(100, 5, "Sumas iguales", 'T', 0, 'R');
$pdf->Cell(20, 5, number_format($debe_t, 2, ".", ","), 1, 0, 'R');
$pdf->Cell(20, 5, number_format($haber_t, 2, ".", ","), 1, 1, 'R');
$pdf->ln(5);

$pdf->Output();
unset($pdf);