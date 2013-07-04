<?php
$h=10;
$pagina=1;
$pdf=new Fpdf_multicell();
$pdf->AddPage('P');
$pdf->ln($h);
$pdf->Cell(0,$h,utf8_decode($title),0,1,'L');
$pdf->SetLeftMargin(60);
$pdf->ln($h);
// $pdf->Image(base_url()."tmp/gastos_globales1.jpeg",10,40,90);
$monto_total=0; $monto_total_global=0;
if(count($datos)>0){
	foreach($espacio->all as $linea){
		$monto_total=0;
		/*		if($pagina!=1)
			$pdf->AddPage('P');*/
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,$h,utf8_decode($linea->tag),0,1,'C');
		$pdf->SetWidths(array(60,40));
		$pdf->SetAligns(array('L','R'));
		$pdf->SetFont('Times','B',10);
		$pdf->Row(array("Concepto", 'Monto'));
		if(isset($datos[$linea->id])){
			foreach($datos[$linea->id]->result() as $row){
				$pdf->SetFont('Times','',10);
				$pdf->Row(array(utf8_decode($row->concepto), number_format($row->monto,2,".",",")));
				$monto_total+=$row->monto;
				$monto_total_global+=$row->monto;
			}
		}
		$pdf->SetFont('Times','B',10);
		$pdf->Row(array("TOTAL", number_format($monto_total,2,".",",")));
		$pagina+=1;
	}
}
$pdf->SetFont('Times','B',10);
$pdf->Row(array("TOTAL GLOBAL", number_format($monto_total_global,2,".",",")));

$pdf->Output();
unset($pdf);
?>