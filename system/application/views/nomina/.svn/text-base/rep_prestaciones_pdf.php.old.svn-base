<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,$title.date(' (d-m-Y)'),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($datos),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(12,90));
$pdf->Row(array('Id', 'Nombre'));

foreach($datos as $row) {
	$pdf->Row(array($row->id, $row->tag));
}
$pdf->Output();

// detener la ejecucion para que no salga error
exit();
?>
