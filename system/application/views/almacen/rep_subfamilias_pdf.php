<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($subfamilias),0,1,'C');
$pdf->SetFont('Times','',8);
$pdf->SetWidths(array(10,60,15));
$pdf->Row(array('Id', '(FAM) Nombre subfamilia','Clave'));
foreach($subfamilias as $row) {
	$pdf->Row(array($row->id, "({$familias[utf8_decode($row->cproducto_familia_id)]}) ".$row->tag,$row->clave));
}
$pdf->Output();
unset($pdf);
exit();
?>
