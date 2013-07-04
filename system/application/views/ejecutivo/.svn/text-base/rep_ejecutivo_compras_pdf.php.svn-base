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
$pares_total=0; $compras_totales=0; ;
$pdf->SetLeftMargin(20);
$pdf->SetWidths(array(40,20,20));
$pdf->SetAligns(array('L','R',"R"));
$pdf->SetFont('Times','B',8);
$pdf->Row(array("ALMACEN", 'MONTO TOTAL',  "PARES"));
foreach($datos as $row) {
	$pdf->SetFont('Times','',8);
	$pdf->Row(array(utf8_decode($row['tag']), number_format($row['compra'],2,".",","), number_format($row['pares'],0,".",",")));

	$pares_total+=$row['pares'];
	$compras_totales+=$row['compra'];
}
$pdf->SetFont('Times','B',8);
$pdf->Row(array("TOTAL", number_format($compras_totales,2,".",","),  number_format($pares_total,0,".",",")));
$pdf->SetLeftMargin(110);
$pdf->Image(base_url()."tmp/ventas_globales1.jpeg",110,30,150);
$pdf->Output();
unset($pdf);
?>