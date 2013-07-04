<?php
$h=10;
$pdf=new Fpdf_multicell('L','mm','letter',1);
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(5);
//Header
$pdf->AddPage('L');
$pdf->Image(base_url().'images/logo_pdf.jpg',5,5,50);
$pdf->SetFont('Times','B',12);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->Cell(0,5,"GRUPO PAVEL",0,1,'L');
$pdf->Cell(50,5,'',0,0,'L');
$pdf->Cell(0,5,$title,0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
$pdf->Cell(50,5,'',0,0,'L');
$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y"),0,1,'L');
$pdf->ln(5);
$monto_total=0; $gastos_totales=0; $fiscal_total=0; $efectivo_total=0; $compras_totales=0; $por_g=0; $por_c=0; $por_u=0;
$pdf->SetLeftMargin(20);
$pdf->SetWidths(array(40,35,14,35,14,35,14,35,14));
$pdf->SetAligns(array('L','R',"R",'R','R','R','R','R','R'));
$pdf->SetFont('Times','B',10);
$pdf->Row(array("TIENDA", 'VENTAS NETAS','%V', "COSTO COMPRA", "%C", "GASTOS", "%G", "UTILIDAD","%U"));
foreach($datos as $row) {
	if($row['ventas']>0){
		$por_g=round(100*$row['gastos']/$row['ventas'], 2);
		$por_c=round(100*$row['compra']/$row['ventas'], 2);
		$por_u=round(100*$row['efectivo']/$row['ventas'], 2);
	} else {
		$por_g=0;
		$por_c=0;
		$por_u=0;
	}
	$pdf->SetFont('Times','',10);
	$pdf->Row(array(utf8_decode($row['nombre']), number_format($row['ventas'],2,".",","), 100, number_format($row['compra'],2,".",","), number_format($por_c,2,".",","), number_format($row['gastos'],2,".",","), number_format($por_g,2,".",","), number_format($row['efectivo'],2,".",","),number_format($por_u, 2,".",","),));

	$monto_total+=$row['ventas'];
	$gastos_totales+=$row['gastos'];
	//$fiscal_total+=$row['fiscal'];
	$efectivo_total+=$row['efectivo'];
	$compras_totales+=$row['compra'];
}
$pdf->SetFont('Times','B',10);
$pdf->Row(array("TOTAL", number_format($monto_total,2,".",","), 100, number_format($compras_totales,2,".",","), round($compras_totales/$monto_total*100,2), number_format($gastos_totales,2,".",","), round($gastos_totales/$monto_total*100,2),  number_format($efectivo_total,2,".",","),round($efectivo_total/$monto_total*100,2)));
$pdf->SetLeftMargin(110);
$pdf->Image(base_url()."tmp/rentabilidad.jpeg",105,130,90);
$pdf->Output();
unset($pdf);
?>