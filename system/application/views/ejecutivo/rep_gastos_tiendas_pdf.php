<?php
$h=10;
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->ln($h);
$pdf->Cell(0,$h,utf8_decode($title),0,1,'C');
$pdf->SetLeftMargin(120);
$pdf->ln($h);
$pdf->Image(base_url()."tmp/gastos_globales1.jpeg",10,40,90);
$monto_total=0;
$pdf->SetWidths(array(60,40));
$pdf->SetAligns(array('L','R'));
$pdf->SetFont('Times','B',10);
$pdf->Row(array("Concepto", 'Monto'));
foreach($datos->result() as $row){
	$pdf->SetFont('Times','',10);
	$pdf->Row(array(utf8_decode($row->concepto), number_format($row->monto,2,".",",")));
	$monto_total+=$row->monto;
}
$pdf->SetFont('Times','B',10);
$pdf->Row(array("TOTAL", number_format($monto_total,2,".",",")));

$pdf->Output();
unset($pdf);
?>