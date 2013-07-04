<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetTopMargin(20);

$pdf->SetFont('Times','B',11);
$pdf->Cell(0,5,'Reporte de Devoluciones a Proveedor','B',1,'L');
$h = 5;// altura
$borde =1;
$pdf->ln(1);
$pdf->Cell( 170,$h,"Id:",'','','R');
$pdf->Cell(0,$h,$devolucion_proveedor->id,$borde);
$pdf->ln($h+1);
$pdf->Cell(170,$h,"Tipo:",'','','R');
$pdf->Cell(0,$h,$devolucion_proveedor->tipo,$borde);
$pdf->ln(7);
$pdf->Cell(40,$h,"Proveedor:",'','','R');
$pdf->Cell(0,$h,utf8_decode("$devolucion_proveedor->proveedor_razon_social"),$borde);
$pdf->ln($h+1);
$pdf->Cell(40,$h,"Fecha de Devolucion:",'','','R');
$pdf->Cell(35,$h,$devolucion_proveedor->fecha,$borde);
$pdf->ln($h+1);
$pdf->Cell(40,$h,"Transporte:",'','','R');
$pdf->Cell(0,$h,utf8_decode("$devolucion_proveedor->transporte"),$borde);
$pdf->ln($h+1);
$pdf->Cell(40,$h,"Numero de guia:",'','','R');
$pdf->Cell(0,$h,$devolucion_proveedor->numero_guia,$borde);
$pdf->ln($h+1);
$pdf->Cell(40,$h,"Domicilio:",'','','R');
$pdf->Cell(0,$h,utf8_decode("$devolucion_proveedor->domicilio"),$borde);


$pdf->SetFont('Times','',8);

$pdf->ln(10);
$i = 1; $pag=0; $borde = 1; $cantidad_total=0;
$pdf->SetWidths(array(12,13,120,18,18,15,15));
$pdf->SetAligns(array("L","C","L","C","R","L","C"));
$pdf->SetFillColor(255,0,0);

if($salidas->count()>0){
    $costo_total = 0;
    $pdf->Row(array('Id', "Cantidad", "Descripcion", "Talla","Costo Total","Subtotal"));
    foreach($salidas->all as $row) {
        $pdf->Row(array(
            $row->id,
            round($row->cantidad,0),
            utf8_decode("$row->cproductos_descripcion "),
            utf8_decode("$row->cproducto_numero_numero_mm "),
            number_format($row->costo_unitario,2,".",","),
            $row->costo_unitario * $row->cantidad));
        $cantidad_total += $row->cantidad;
        $costo_total += $row->costo_unitario * $row->cantidad;
    }
    $pdf->Row(array('Total', $cantidad_total, "", "","Total",$costo_total));
    $pdf->Cell(0,$h,"",'T',1);
    $pdf->Cell(32,7,"Observaciones:",0,1);
    $pdf->Cell(0,7,"",1,1);
    $pdf->Cell(0,7,"",'LBR',1);
    $pdf->Cell(0,7,"",'LBR',1);
    $pdf->ln(25);
    $pdf->Cell(50);
    $pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',0,'C');
    $pdf->Cell(10);
    $pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',1,'C');
    $pdf->Cell(50);
    $pdf->Cell(50,5,"Envia ",0,0,'C');
    $pdf->Cell(10);
    $pdf->Cell(50,5,"Recibe ",0,1,'C');
} else {
    $pdf->Cell(50);
    $pdf->Cell(50,5,"SIN DATOS",0,0,'C');
}
$pdf->Output();
unset($pdf)
?>
