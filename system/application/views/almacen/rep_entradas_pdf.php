<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y").' Resultados: '.count($entradas),0,1,'C');
$pdf->ln(.5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetAligns(array('L','C','C','C','L','R','R','R','R','C','C','C'));
$pdf->SetWidths(array(8,10,13,27,55,16,15,15,22,25,40,20));
$pdf->Row(array('#','Id','Folio Factura','Fecha Captura','Producto','Cantidad','Costo Unitario','Tasa Impuesto','Costo Total','Tipo','Ubicaci�n','Proveedor'));
$gtotal=0;
$r=1;
foreach($entradas as $row) {

	$total=(($row->cantidad*$row->costo_unitario)+(($row->cantidad*$row->costo_unitario)*$row->tasa_impuesto/100));
	$gtotal+=$total;
	$pdf->Row(array($r,$row->id, $row->factura, $row->fecha, utf8_decode($row->producto), $row->cantidad, $row->costo_unitario, $row->tasa_impuesto, number_format($total, 2, ".", ","), $row->tipo, $row->espacio, $row->proveedor));
	$r+=1;
}
$pdf->Row(array('--', '--', '--', 'TOTAL CON IVA', '', '', '', '', "$ ".number_format($gtotal, 2, ".", ","), '','', ''));

$pdf->Output();
unset($pdf)
?>
