<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$h=5;
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->Cell(0,$h,"PROVEEDOR: ".utf8_decode($proveedor),0,1,'L');
$pdf->Cell(0,$h,"MARCA: ".utf8_decode($marca),0,1,'L');
$pdf->Cell(0,$h,"PERIODO: DEL $fecha1 AL $fecha2",0,1,'L');
$pdf->ln($h);

if($pendientes!=false){
	$totalp=0;
	$pdf->Cell(0,$h,"Devoluciones sin abonar a pago del proveedor","B",1,'C');
	$pdf->ln($h);
	$pdf->Cell(0,5,'Resultados: '.count($pendientes->all),0,1,'L');
	$pdf->SetFont('Times','B',12);
	$pdf->SetWidths(array(15,15,20,75,15,15,20,20));
	$pdf->SetAligns(array("R","R","C","L","L","R","R","R"));
	// 	s.id,  s.cantidad, date(s.fecha) as fecha, s.cproductos_id, p.descripcion as producto, s.lote_id, s.costo_unitario, s.costo_total
	$pdf->Row(array("S Id", "Cant.",'Fecha','Descripci�n','Tienda','Lote','Costo U.','SubTotal'));
	$pdf->SetFont('Times','',7);
	foreach($pendientes->all as $row) {
		$pdf->Row(array($row->id, number_format($row->cantidad,0), $row->fecha, utf8_decode($row->producto),utf8_decode($row->espacio), $row->lote_id, number_format($row->costo_unitario,2,".",","), number_format($row->costo_total,2,".",",")));
		$totalp+=$row->costo_total;
	}
	$pdf->Row(array("", "", "","","", "","TOTAL", number_format($totalp,2,".",",")));

} else{
	// 	$pdf->Cell(0,$h,"No hay devoluciones pendientes al proveedor seleccionado","B",1,'C');
	// 	$pdf->ln($h);
}

if($finiquitadas!=false){
	$totalf=0;
	$pdf->ln($h);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(0,$h,"Devoluciones abonadas a pagos del proveedor","B",1,'C');
	$pdf->ln($h);
	$pdf->Cell(0,5,'Resultados: '.count($finiquitadas->all),0,1,'L');
	$pdf->SetFont('Times','B',12);
	$pdf->SetWidths(array(15,15,20,75,15,15,20,20));
	$pdf->SetAligns(array("R","R","C","L","L","R","R","R"));
	// 	s.id,  s.cantidad, date(s.fecha) as fecha, s.cproductos_id, p.descripcion as producto, s.lote_id, s.costo_unitario, s.costo_total
	$pdf->Row(array("S Id", "Cant.",'Fecha','Descripci�n','Tienda','Lote','Costo U.','SubTotal'));
	$pdf->SetFont('Times','',7);
	foreach($finiquitadas->all as $row) {
		$pdf->Row(array($row->id, number_format($row->cantidad,0), $row->fecha, utf8_decode($row->producto),utf8_decode($row->espacio), $row->lote_id, number_format($row->costo_unitario,2,".",","), number_format($row->costo_total,2,".",",")));
		$totalf+=$row->costo_total;
	}
	$pdf->Row(array("", "", "","","", "","TOTAL", number_format($totalf,2,".",",")));

} else{
	$pdf->Cell(0,$h,"No hay devoluciones previas al proveedor seleccionado","B",1,'C');
	$pdf->ln($h);
}
$pdf->Output();
unset($pdf);
exit();
?>