<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,$title,1,1,'L');
$pdf->SetFont('Times','',10);
$pdf->Cell(0,5,'Resultados: '.count($cobros->all),1,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(7,19,20,60,30,30,30));
$pdf->Row(array("Id","Fecha","Folio Factura","Cuenta Bancaria","Monto Pagado","Tipo Cobro","Forma Cobro"));


foreach($cobros->all as $row) {
	$pdf->Row(array($row->id,$row->fecha,$row->factura,$row->banco."\n".$row->numero_cuenta,$row->monto_pagado,$row->tipo_cobro,$row->forma_cobro));
}
$pdf->Output();
unset($pdf)
?>