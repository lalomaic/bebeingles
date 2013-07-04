<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($grupos),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
$pdf->SetWidths(array(7,60,95));
$pdf->Row(array("Id","Nombre","Descripcion"));
if(count($grupos)!=false){
	foreach($grupos->all as $row) {
		$pdf->Row(array($row->id,utf8_decode($row->nombre),utf8_decode($row->descripcion)));
	}
}
$pdf->Output();
exit();
?>
