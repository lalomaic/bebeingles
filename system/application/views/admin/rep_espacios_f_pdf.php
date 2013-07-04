<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($espacios_fisicos),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
$pdf->SetWidths(array(7,40,35,19,40,62));
$pdf->Row(array("Id","Empresa","Tipo de local","Nombre","Domicilio","Telefono"));
if(count($espacios_fisicos)!=false){
	foreach($espacios_fisicos->all as $row) {
		$pdf->Row(array($row->id,utf8_decode($row->empresa),$row->tipo_espacio,utf8_decode($row->tag),utf8_decode($row->domicilio),$row->telefono));
	}
}

$pdf->Output();
?>
