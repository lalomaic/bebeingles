<?php
$h=10;
$pdf=new Fpdf_multicell('P','mm','letter',1);
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(20);
//Header
$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,"GRUPO PAVEL",0,1,'L');
$pdf->Cell(0,5,$title,0,1,'L');
$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y"),0,1,'L');
$pdf->Cell(0,5,"Periodo 1: $fecha1 al $fecha2",0,1,'L');
$pdf->Cell(0,5,"Periodo 2: $fecha3 al $fecha4",0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Secci�n Comparativa de Ventas',0,1,'L');
$total_p1=0; $total_p2=0;
$pdf->ln();
$y=$pdf->getY();
$pdf->Image(base_url()."tmp/ventas.jpeg",110,$y,90);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TIENDA", 'PERIODO 1', "PERIODO 2"));
$pdf->SetFont('Times','',8);
foreach($efisicos as $e_id=>$tag) {
	$pdf->Row(array($tag, number_format($datos["periodo1"][$e_id]['ventas'],2,".",","),number_format($datos["periodo2"][$e_id]['ventas'],2,".",",")));
	$total_p1+=$datos["periodo1"][$e_id]['ventas'];
	$total_p2+=$datos["periodo2"][$e_id]['ventas'];
}
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TOTAL", number_format($total_p1,2,".",","),number_format($total_p2,2,".",",")));
$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Secci�n de Costos.',0,1,'L');
$total_p1=0; $total_p2=0;
$pdf->ln();
$y=$pdf->getY();
$pdf->Image(base_url()."tmp/compras.jpeg",110,$y,90);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TIENDA", 'PERIODO 1', "PERIODO 2"));
$pdf->SetFont('Times','',8);
foreach($efisicos as $e_id=>$tag) {
	$pdf->Row(array($tag, number_format($datos["periodo1"][$e_id]['compra'],2,".",","),number_format($datos["periodo2"][$e_id]['compra'],2,".",",")));
	$total_p1+=$datos["periodo1"][$e_id]['compra'];
	$total_p2+=$datos["periodo2"][$e_id]['compra'];
}
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TOTAL", number_format($total_p1,2,".",","),number_format($total_p2,2,".",",")));

$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Secci�n de Gastos',0,1,'L');
$total_p1=0; $total_p2=0;
$pdf->ln();
$y=$pdf->getY();
$pdf->Image(base_url()."tmp/gastos.jpeg",110,$y,90);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TIENDA", 'PERIODO 1', "PERIODO 2"));
$pdf->SetFont('Times','',8);
foreach($efisicos as $e_id=>$tag) {
	$pdf->Row(array($tag, number_format($datos["periodo1"][$e_id]['gastos'],2,".",","),number_format($datos["periodo2"][$e_id]['gastos'],2,".",",")));
	$total_p1+=$datos["periodo1"][$e_id]['gastos'];
	$total_p2+=$datos["periodo2"][$e_id]['gastos'];
}
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TOTAL", number_format($total_p1,2,".",","),number_format($total_p2,2,".",",")));


$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Secci�n de Utilidades Netas',0,1,'L');
$total_p1=0; $total_p2=0;
$pdf->ln();
$y=$pdf->getY();
$pdf->Image(base_url()."tmp/utilidad.jpeg",110,$y,90);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TIENDA", 'PERIODO 1', "PERIODO 2"));
$pdf->SetFont('Times','',8);
foreach($efisicos as $e_id=>$tag) {
	$pdf->Row(array($tag, number_format($datos["periodo1"][$e_id]['utilidad'],2,".",","),number_format($datos["periodo2"][$e_id]['utilidad'],2,".",",")));
	$total_p1+=$datos["periodo1"][$e_id]['utilidad'];
	$total_p2+=$datos["periodo2"][$e_id]['utilidad'];
}
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TOTAL", number_format($total_p1,2,".",","),number_format($total_p2,2,".",",")));

$pdf->AddPage();
$pdf->SetTopMargin(5);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Secci�n de Pares',0,1,'L');
$total_p1=0; $total_p2=0;
$pdf->ln();
$y=$pdf->getY();
$pdf->Image(base_url()."tmp/pares.jpeg",110,$y,90);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TIENDA", 'PERIODO 1', "PERIODO 2"));
$pdf->SetFont('Times','',8);
foreach($efisicos as $e_id=>$tag) {
	$pdf->Row(array($tag, number_format($datos["periodo1"][$e_id]['pares'],2,".",","),number_format($datos["periodo2"][$e_id]['pares'],2,".",",")));
	$total_p1+=$datos["periodo1"][$e_id]['pares'];
	$total_p2+=$datos["periodo2"][$e_id]['pares'];
}
$pdf->SetFont('Times','B',9);
$pdf->Row(array("TOTAL", number_format($total_p1,2,".",","),number_format($total_p2,2,".",",")));


$pdf->Output();
unset($pdf);
?>