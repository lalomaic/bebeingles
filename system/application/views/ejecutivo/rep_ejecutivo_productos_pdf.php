<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,utf8_decode($title),0,1,'C');
$pdf->ln($h);
$pdf->SetWidths(array(40,50,60,30));
$pdf->SetFont('Times','B',10);
$pdf->SetAligns(array('C','C','C','C'));
$pdf->Row(array('FAMILIA','SUBFAMILIA','DESCRIPCI�N','CANTIDAD'));
$pdf->SetFont('Times','',9);
$pdf->SetAligns(array('L','L','L','R'));

foreach ($global->result() as $fila) {
	$pdf->Row(array(utf8_decode($fila->familia), utf8_decode($fila->subfamilia), utf8_decode($fila->descripcion), number_format($fila->cantidad, 4)));
	//	$pdf->Ln($h);
}
$pdf->Output();
unset($pdf);
?>
