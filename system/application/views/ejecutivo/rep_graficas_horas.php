<?php
function esPar($num){
	return !($num%2);
}
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,utf8_decode($title),'B',1,'L');
$pdf->ln($h);
$pdf->Image(base_url()."tmp/tickets_horas1.jpeg",10,20,190);
$pdf->SetXY(100, 150);
$pdf->ln($h);
$pdf->Cell(0,$h,"Tabla de Comportamiento de Ventas por Hora",0,1,'C');
$pdf->ln(3);
$pdf->Cell(0, $h, "TIENDA: $tienda", 0, 1, 'C');
$pdf->ln(3);
//$pdf->Cell(0,$h,$etiqueta,0,1,'C');
$pdf->SetWidths(array(30,25, 40,28,27,35));
$pdf->SetFont('Times','B',10);
$pdf->SetAligns(array('C','C','C', 'C','C','C'));
$pdf->SetFillColor(200,0,0);
$pdf->Row(array('HORA','TICKETS','PROMEDIO TICKETS','VENTAS', 'PROMEDIO VENTAS', 'DIAS CONTABILIZADOS'));
$pdf->SetFont('Times','',9);
$pdf->SetAligns(array('L','R','R','R','R', 'C'));
$r=1;
$total=0;
$tot_tic=0;
$prom = 0;
$hor = 1;
$cero1 = '';
$cero2 = '';
foreach ($global->result() as $fila) {
	if(esPar($r))
		$pdf->SetFillColor(220,0,0);
	else
		$pdf->SetFillColor(255,0,0);

	$hor = $fila->h+1;
	if($fila->h < 10) $cero1 = '0'; else $cero1 = '';
	if($hor < 10) $cero2 = '0'; else $cero2 = '';
	$pdf->Row(array($cero1.$fila->h.":00 - ".$cero2.$hor.":00",$fila->tickets, $fila->prom, "$".number_format($fila->venta, 2, ".",","), "$".number_format($fila->promv, 2, ".",","), $fila->dias));
	$total+=$fila->venta;
	$tot_tic+=$fila->tickets;
	$prom+=$fila->prom;
	$r++;
}
if(esPar($r))
	$pdf->SetFillColor(220,0,0);
else
	$pdf->SetFillColor(255,0,0);
$pdf->SetFont('Times','B',10);
$pdf->SetWidths(array(30,25, 40,28, 62));
$pdf->SetAligns(array('C','R','R','R','C'));
$pdf->Row(array('TOTAL', $tot_tic, $prom,"$".number_format($total,2,".",","), ''));
$pdf->Cell(185, 0, '', 1);
$pdf->ln($h);
$pdf->Output();
?>