<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($productos),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetAligns(array('L','L','L','L','L','L','R','R','R','R'));
$pdf->SetWidths(array(12,90,30,25,25,25,14,14,14,10));
$pdf->Row(array('Id', 'Descripción',  'Depto', 'Tipo', 'Marca','Material','Precio1', 'Precio2', 'Precio3',  'TASA IVA'));

foreach($productos as $row) {
	$pdf->Row(array($row->id, utf8_decode($row->descripcion),  utf8_decode($row->familia), utf8_decode($row->subfamilia), utf8_decode($row->marca),utf8_decode($row->material),number_format($row->precio1,2,".",","), number_format($row->precio2,2,".",","), number_format($row->precio3,2,".",","),  $row->tasa_impuesto));
	unset($row);
}
$pdf->Output();
unset($pdf);
?>
