<?php
$h=5;
$pdf=new Fpdf_multicell("P","mm", "letter", 1);
$pdf->AddPage();
$pdf->SetTopMargin(15);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,$h,utf8_decode($title),0,1,'L');
$pdf->Cell(0,$h, "Fecha Reporte: $fecha", 0,1,"L");
$pdf->Cell(0,$h, $criterios, 0,1,"L");
$pdf->ln($h); $i=0;
$global_total=(float)0;
$global_vencido=(float)0;
$global_xvencer=(float)0;
if($desglosar==false){
	$pdf->SetAligns(array('L','R','R','R',));
	$pdf->SetWidths(array(100,30,30,30));
	$pdf->SetFillColor(192,192,192);
	$pdf->Row(array('Proveedor','Saldo X Vencer','Saldo Vencido','Total Adeudo'));
	$pdf->SetFont('Times','',8);
	foreach($datos as $pid=>$valores){
		if(!($i%2))
			$pdf->SetFillColor(230,230,250);
		else
			$pdf->SetFillColor(255,255,255);
		if(isset($valores['saldo_xvencer'])==false)
			$valores['saldo_xvencer']=0;
		if(isset($valores['saldo_vencido'])==false)
			$valores['saldo_vencido']=0;
		$pdf->Row( array( utf8_decode($valores['proveedor']), number_format($valores['saldo_xvencer'],2), number_format($valores['saldo_vencido'],2), number_format($valores['total_adeudo'],2)));
		$global_total+=$valores['total_adeudo'];
		$global_vencido+=$valores['saldo_vencido'];
		$global_xvencer+=$valores['saldo_xvencer'];
		$i+=1;
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Row(array('Total Global',number_format($global_xvencer,2),number_format($global_vencido, 2),number_format($global_total,2)));

} else {
	$pdf->SetAligns(array('L','L','R','R','R',));
	$pdf->SetWidths(array(70,40,30,30,30));
	$pdf->SetFillColor(192,192,192);
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array('Proveedor','Marca','Saldo X Vencer','Saldo Vencido','Total Adeudo'));
	$pdf->SetFont('Times','',8);
	foreach($datos as $pid=>$valores1){
		foreach($valores1 as $cid=>$valores){
			if(!($i%2))
				$pdf->SetFillColor(230,230,250);
			else
				$pdf->SetFillColor(255,255,255);
			if(isset($valores['saldo_xvencer'])==false)
				$valores['saldo_xvencer']=0;
			if(isset($valores['saldo_vencido'])==false)
				$valores['saldo_vencido']=0;
			$pdf->Row( array( utf8_decode($valores['proveedor']), utf8_decode($valores['marca']), number_format($valores['saldo_xvencer'], 2), number_format($valores['saldo_vencido'],2), number_format($valores['total_adeudo'],2)));
			$global_total+=$valores['total_adeudo'];
			$global_vencido+=$valores['saldo_vencido'];
			$global_xvencer+=$valores['saldo_xvencer'];
			$i+=1;
		}
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillcolor(225,0,0);
	$pdf->Row(array('Total Global','',number_format($global_xvencer,2),number_format($global_vencido, 2),number_format($global_total,2)));

}
$pdf->Output();
unset($pdf);
exit();
?>
