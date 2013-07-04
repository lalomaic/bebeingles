<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y").' Resultados: '.count($salidas),0,1,'C');
$pdf->ln(.5);
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetAligns(array('C','C','C','L','R','R','R','R','C','C','C'));
$pdf->SetWidths(array(10,15,27,55,16,15,15,20,25,40,20));
$pdf->Row(array('Id','Folio Factura','Fecha Captura','Producto','Cantidad','Costo Unitario','Tasa Impuesto','Costo Total','Tipo','Ubicaci�n','Cliente'));
$gtotal=0;
$i=0;
foreach($salidas as $row) {
	if(!($i%2))
		$pdf->SetFillColor(200,0,0);
	else
		$pdf->SetFillColor(255,0,0);

	$total=(($row->cantidad*$row->costo_unitario)+(($row->cantidad*$row->costo_unitario)*$row->tasa_impuesto/100));
	$gtotal+=$total;
	$pdf->Row(array($row->id, $row->factura, $row->fecha, utf8_decode($row->producto), $row->cantidad, $row->costo_unitario, $row->tasa_impuesto, number_format($total, 2, ".", ","), $row->tipo, utf8_decode($row->espacio_fisico), $row->cliente));
	$i+=1;
}
$pdf->SetFillColor(200,0,0);
$pdf->Row(array('--', '--', '--', 'TOTAL CON IVA', '', '', '',"$ ".number_format($gtotal, 2, ".", ","), '', '', ''));

$pdf->Output();
unset($pdf)
?>
