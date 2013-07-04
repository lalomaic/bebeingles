<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($proveedores->all),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetWidths(array(7,60,25,70,20,35,13,15));
$pdf->Row(array("Id","Nombre","RFC","Domicilio","Tel�fonos","E-mail","L�mite Cr�dito","Estatus"));

foreach($proveedores->all as $row) {
	$pdf->Row(array($row->id,utf8_decode($row->razon_social),$row->rfc,utf8_decode($row->domicilio)."\n".utf8_decode($row->municipio).", ".utf8_decode($row->estado),$row->telefono,$row->email,$row->limite_credito,$row->estatus));
}
$pdf->Output();
unset($pdf);
?>
