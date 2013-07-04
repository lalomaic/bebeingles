<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($familias),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(10,60,15));
$pdf->Row(array('Id', 'Nombre','Clave'));

foreach($familias as $row) {
	$pdf->Row(array($row->id,utf8_decode($row->tag),$row->clave));
}
$pdf->Output();
unset($pdf);
exit();
?>
