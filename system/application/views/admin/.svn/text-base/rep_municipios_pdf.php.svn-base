<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($municipios),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
$pdf->SetWidths(array(7,40,45));
$pdf->Row(array("Id","Nombre","Estado"));
if(count($municipios)!=false){
	foreach($municipios->all as $row) {
		$pdf->Row(array($row->id,utf8_decode($row->municipio),utf8_decode($row->estado)));
	}
}
$pdf->Output();
exit();
?>
