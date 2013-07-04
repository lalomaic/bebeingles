<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,utf8_decode($title),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($unidades),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(10,60,30,30,60));
$pdf->Row(array('Id', 'Nombre',utf8_decode('Ultima Fecha edición'),"Estatus","Modificado por"));



foreach($unidades as $row) {

	$pdf->Row(array($row->id,utf8_decode($row->tag),date_format(date_create($row->fecha_cambio), 'd-m-Y'),$row->estatus_general->tag,utf8_decode($row->usuarios->nombre)));
}
$pdf->Output();
unset($pdf);
exit();
?>