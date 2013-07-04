<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($proveedores),0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
/*$pdf->MultiCell(0,5,'EMPRESA: '.$empresa);
 $pdf->MultiCell(0,5,'E. FISICO: '.$espacio);*/
//P=201
//L=272
$anchos=array(15,20,30,25,20,50,30);
$pdf->SetWidths($anchos);
foreach($proveedores as $prov)
{
	$pdf->SetFont('Times','B',8);
	$pdf->Cell(0,5,utf8_decode($prov['nombre']));
	$pdf->ln();
	$pdf->SetFont('Times','B',8);
	$pdf->SetAligns(array('C','C','C','C','C','C','C'));
	$pdf->Row(array('Id','Fecha','Folio','Pedido','Fecha Pago','Monto','Forma de Pago'));
	$pdf->SetFont('Times','',8);
	$pdf->SetAligns(array('L','L','L','C','C','R','L'));
	$monto=(float)0;
	foreach($prov['facturas'] as $factura)
	{
		$monto+=(float)$factura['monto'];
		$pdf->Row(array($factura['id'], date("d-m-Y",strtotime($factura['fecha'])), $factura['folio'], $factura['pedido'], date("d-m-Y",strtotime($factura['fecha_pago'])),number_format($factura['monto'],2),$factura['tipofac']));
	}
	$pdf->Cell($anchos[0]+$anchos[1]+$anchos[2]+$anchos[3]+$anchos[4]);
	$pdf->SetFont('Times','B',8);
	$pdf->Cell($anchos[5],5,'MONTO TOTAL   $ '.number_format($monto,2),1,0,'R');
	
}
$pdf->Output();
unset($pdf);
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
