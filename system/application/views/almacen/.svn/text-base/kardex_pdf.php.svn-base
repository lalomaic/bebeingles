<?php
$h=20;
$pdf=new Fpdf_multicell();
//$pdf->Open('/var/www/soleman/tmp/kardex.pdf');
$pdf->AddPage();
$pdf->Cell(0,$h,$title.' ('.$periodo.')',0,1,'C');
$pdf->ln($h);
$pdf->SetWidths(array(30,40,40,40,40));
//201
//$pdf->ln(5);
if($orden=='familia')
{
	foreach($bloques as $eid=>$espacio)
	{
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,0,$espacios[$eid]);
		$pdf->ln($h);
		foreach($espacio as $fid=>$familia)
		{
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(0,0,$familias[$fid]);
			$pdf->ln($h);
			foreach($familia as $sid=>$subfamilia)
			{
				$pdf->SetFont('Times','I',10);
				$pdf->Cell(0,0,$subfamilias[$sid]);
				$pdf->ln($h);
				foreach($subfamilia as $pid=>$producto)
				{
					$pdf->ln(10);
					$pdf->SetFont('Times','',8);
					$pdf->Cell ( 0,0,$productos[$pid]['descripcion'].' ('.$productos[$pid]['presentacion'].')11');
					$pdf->ln($h-2);
					$total=(float)0;
					$pdf->Row(array('Fecha','Tipo movimiento','Entrada','Salida','Existencia'));
					foreach($producto as $movimiento)
					{
						$total+=(float)$movimiento['entrada'];
						$total-=(float)$movimiento['salida'];
						$pdf->Row(array($movimiento['fecha'],$movimiento['tipo'],$movimiento['entrada'],$movimiento['salida'],number_format($total,4)));
					}
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
			$pdf->ln(10);
			$pdf->SetFont('Times','I',10);
			$pdf->Cell(0,0,$productos[$pid]['descripcion'].' ('.$productos[$pid]['presentacion']).')1';
			$pdf->ln($h-2);
			$total=(float)0;
			$pdf->SetFont('Times','',8);
			$pdf->Row(array('Fecha','Tipo movimiento','Entrada','Salida','Existencia'));
			foreach($producto as $movimiento)
			{
				$total+=(float)$movimiento['entrada'];
				$total-=(float)$movimiento['salida'];
				$pdf->Row(array($movimiento['fecha'],$movimiento['tipo'],$movimiento['entrada'],$movimiento['salida'],number_format($total,4)));
			}
			$pdf->ln($h);
		}
	}
}

$pdf->Output();
unset($pdf);
?>
