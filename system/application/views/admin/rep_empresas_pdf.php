<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($empresas),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
$pdf->SetWidths(array(7,40,25,48,27,27,27));
$pdf->Row(array("Id","Nombre","RFC","Domicilio","Telefonos","Ciudad","Estado"));

if(count($empresas)!=false){
	foreach($empresas->all as $row) {
		$pdf->Row(array($row->id,utf8_decode($row->razon_social),$row->rfc,utf8_decode($row->domicilio_fiscal),$row->telefonos,$row->ciudad,utf8_decode($row->estado)));
	}
}
$pdf->Output();
?>
