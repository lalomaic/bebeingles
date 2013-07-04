<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,$title,0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($clientes->all),0,1,'C');
$pdf->ln(5);
$pdf->SetFont('Times','',8);
//263
$pdf->SetWidths(array(7,60,25,70,20,35,13,11,15));
$pdf->Row(array("Id","Nombre","RFC","Domicilio","Telefonos","E-mail",utf8_decode("Límite Crédito"),utf8_decode("Dias Crédito"),"Estatus"));



foreach($clientes->all as $row) {
	$pdf->Row(array($row->id,$row->razon_social,$row->rfc,$row->domicilio."\n".$row->municipio.", ".$row->estado,$row->telefono,$row->email,round($row->limite_credito,0),round($row->dias_credito,0),$row->estatus));
}
$pdf->Output();
unset($pdf);
exit();
?>
