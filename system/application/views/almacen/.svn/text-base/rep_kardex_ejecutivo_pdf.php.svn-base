<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,$h,utf8_decode($title).' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
$pdf->SetWidths(array(40,40,40));
//201
//$pdf->ln(5);
if($orden=='familia')
{
	foreach($bloques as $eid=>$espacio)
	{
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,0,utf8_decode($espacios[$eid]));
		$pdf->ln($h);
		foreach($espacio as $fid=>$familia)
		{
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(0,0,utf8_decode($familias[$fid]));
			$pdf->ln($h);
			foreach($familia as $sid=>$subfamilia)
			{
				$pdf->SetFont('Times','I',10);
				$pdf->Cell(0,0,utf8_decode($subfamilias[$sid]));
				$pdf->ln($h);
				foreach($subfamilia as $pid=>$producto)
				{
					$pdf->SetFont('Times','B',8);
					$pdf->Cell(0,0,utf8_decode($productos[$pid]['descripcion']).' ('.utf8_decode($productos[$pid]['presentacion']).')');
					$pdf->ln($h-2);
					$total=(float)0;
					$pdf->SetAligns(array('C','C','C'));
					$pdf->Row(array('Entrada','Salida','Existencia'));
					$pdf->SetAligns(array('R','R','R'));
					$pdf->SetFont('Times','',8);
					$pdf->Row(array($producto[0]['entrada'],$producto[0]['salida'],number_format($producto[0]['existencia'],4)));
					$pdf->Row(array(number_format($producto[1]['entrada'],4),number_format($producto[1]['salida'],4),number_format($producto[1]['existencia'],4)));
					$pdf->ln($h);
				}
			}
		}
	}
}
if($orden=='producto')
{
	foreach($bloques as $eid=>$espacio)
	{
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,0,$espacios[$eid]);
		$pdf->ln($h);
		foreach($espacio as $pid=>$producto)
		{
			$pdf->SetFont('Times','I',10);
			$pdf->Cell(0,0,utf8_decode($productos[$pid]['descripcion']).' ('.utf8_decode($productos[$pid]['presentacion']).')');
			$pdf->ln($h-2);
			$total=(float)0;
			$pdf->SetFont('Times','B',8);
			$pdf->SetAligns(array('C','C','C'));
			$pdf->Row(array('Entrada','Salida','Existencia'));
			$pdf->SetFont('Times','',8);
			$pdf->SetAligns(array('R','R','R'));
			$pdf->Row(array($producto[0]['entrada'],$producto[0]['salida'],number_format($producto[0]['existencia'],4)));
			$pdf->Row(array(number_format($producto[1]['entrada'],4),number_format($producto[1]['salida'],4),number_format($producto[1]['existencia'],4)));
			$pdf->ln($h);
		}
	}
}
$pdf->Output();
unset($pdf)
// $name=uniqid('reporte_').'.pdf';
// $pdf->Output($name);
// echo 'Terminado '.$name;
?>
