<?php
$n = count($detalles->all);
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetTopMargin(20);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,$title,'B',1,'L');
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln($h);
$i = 1; $pag=0; $borde = 1; $u_salida=0; $u_entrada=0;$total=0; $total_pares=0;
$pdf->Cell(32,$h,"UBICACI�N SALIDA:");
$pdf->Cell(0,$h,$generales->origen,$borde);
$pdf->ln($h+1);
$pdf->Cell(32,$h,"UBICACI�N ENTRADA:");
$pdf->Cell(0,$h,$generales->destino,$borde);
$pdf->ln($h+1);
$pdf->Cell(32,$h,"FECHA DE IMPRESI�N:");
$pdf->Cell(0,$h,date("d m Y"),$borde);
$pdf->ln(10);
/*		$u_salida=$row->espacio_fisico_id;
 $u_entrada=$row->espacio_fisico_recibe_id;*/
$total_pares=0;
$total=0;
$pdf->SetFont('Times','B',10);
$pdf->SetFillColor(200,0,0);
$pdf->SetWidths(array(15,15,120,18,18));
$pdf->SetAligns(array("L","L","L","R","R"));
$pdf->Row(array("Cant. Sugerida",'Cant. Enviada',"Producto","Costo Unitario", "SubTotal"));
$pdf->SetFont('Times','',8);
foreach($detalles->all as $row){
	if(!($i%2))
		$pdf->SetFillColor(200,0,0);
	else
		$pdf->SetFillColor(255,0,0);
	$pdf->Row(array($row->cantidad, '', utf8_decode("$row->producto "),   number_format($row->precio_compra,2,".",","), number_format($row->precio_compra*$row->cantidad,2,".",",")));
	$total_pares+=$row->cantidad;
	$total+=$row->precio_compra*$row->cantidad;
	$i+=1;
}
$pdf->Row(array($total_pares,'', "", "",number_format($total,2,".",",")));
$pdf->Cell(0,$h,"",'T',1);
$pdf->Cell(32,7,"OBSERVACIONES:",0,1);
$pdf->Cell(0,7,"",1,1);
$pdf->Cell(0,7,"",'LBR',1);
$pdf->Cell(0,7,"",'LBR',1);
$pdf->ln(25);
$pdf->Cell(50);
$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',0,'C');
$pdf->Cell(10);
$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',1,'C');
$pdf->Cell(50);
$pdf->Cell(50,5,"SUCURSAL $generales->origen",0,0,'C');
$pdf->Cell(10);
$pdf->Cell(50,5,"SUCURSAL $generales->destino",0,1,'C');

$pdf->Output();
unset($pdf)
?>
