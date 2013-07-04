<?php

$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.$n,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->MultiCell(0,5,'EMPRESA: '.$empresa);
$pdf->MultiCell(0,5,'E. FISICO: '.$espacio);
//P=201
//L=272
$anchos=array(15,20,25,35,50,40);
$pdf->SetWidths($anchos);
foreach($clientes as &$cliente)
{
	if(count($cliente['facturas'])>0)
	{
		$pdf->SetFont('Times','B',8);
		$pdf->Cell(0,5,$cliente['nombre']);
		$pdf->ln();
		//$pdf->SetFont('Times','B',8);// comentado 13/05/2010 02:20:38 p.m.
		$pdf->SetAligns(array('C','C','C','C','C','C'));
		$pdf->Row(array("Id","Fecha","Folio","Iva Total","Monto Total","Forma de Pago"));
		$pdf->SetFont('Times','',8);
		$pdf->SetAligns(array('L','L','L','R','R','L'));
		$iva=$monto=(float)0;
		foreach($cliente['facturas'] as &$factura)
		{
			$iva+=(float)$factura['iva'];
			$monto+=(float)$factura['monto'];
			$pdf->Row(array($factura['id'], $factura['fecha'], $factura['folio'], '$ '.number_format($factura['iva'],2), '$ '.number_format($factura['monto'],2),$tipofac[$factura['pago']]));
		}
		$pdf->Cell($anchos[0]+$anchos[1]+$anchos[2]);
		$pdf->SetFont('Times','B',8);
		$pdf->Cell($anchos[3],5,'IVA TOTAL   $ '.number_format($iva,2),1,0,'R');
		$pdf->Cell($anchos[4],5,'MONTO TOTAL   $ '.number_format($monto,2),1,0,'R');
	}
	$pdf->ln();
}
$pdf->Output();
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
