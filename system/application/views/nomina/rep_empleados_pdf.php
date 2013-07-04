<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,5,$title.date(' (d-m-Y)'),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($datos),0,1,'C');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201
$pdf->SetWidths(array(12,37,37,37,45,20,30,40));
$pdf->Row(array('Id', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Puesto', 'Nomina', 'Horario', 'Espacio Fisico'));
foreach($datos as $row) {
	$pdf->Row(array($row->id, utf8_decode($row->nombre), utf8_decode($row->apaterno), utf8_decode($row->amaterno), $row->puesto, $row->nomina, $row->horario, $row->espacio));
}
$pdf->Output();
exit();
?>
