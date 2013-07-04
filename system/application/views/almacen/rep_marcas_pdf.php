<?php
$pdf=new Fpdf_multicell();
$pdf->SetTopMargin(20);
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($marcas),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(10,60,100));
$pdf->Row(array('Id', 'Marca', 'Proveedor'));
foreach($marcas as $row) {
	$pdf->Row(array($row->id,utf8_decode($row->marca), utf8_decode($row->proveedor)));
}
$pdf->Output();
unset($pdf);
exit();
?>
