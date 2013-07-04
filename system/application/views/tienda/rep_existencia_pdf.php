<?php
function esPar($num){
	return !($num%2);
}
$pdf=new Fpdf_multicell();
if($arqueo!=false){
	foreach($arqueo->all as $row) {
		$pdf->AddPage();
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(0,5,$title,'B',1,'L');
		$pdf->ln(3);
		$pdf->SetFont('Times','',8);
		$h = 5;// altura
		$pdf->ln($h);
		$x1 = $pdf->GetX();
		$x2 = $x1+25;
		$y = $pdf->GetY();
		$arqueo1= array(7,16,35,35,20,20,20,16,30);
		$arqueo_detalle1 = array(8, 15,100,35);
		$i = 1;
		$borde = 1;
		$pdf->SetXY($x1,$y);
		$pdf->SetWidths($arqueo1);
		$pdf->Cell(15,$h,"Id Arqueo:");
		$pdf->Cell(23,$h,$row->id,$borde);
		$pdf->Cell(4);
		//$pdf->SetTextColor(100);
		$pdf->Cell(12,$h,"FECHA:");
		$pdf->Cell(17,$h,$row->fecha,$borde);
		$pdf->Cell(4);
		$pdf->Cell(12,$h,"HORA:");
		$pdf->Cell(17,$h,$row->hora,$borde);
		$pdf->Cell(4);
		$pdf->Cell(16,$h,"ESTATUS:");
		$pdf->Cell(0,$h,$row->estatus,$borde);
		$pdf->ln($h+1);
		$pdf->Cell(41,$h,"UBICACION - CAPTURISTA:");
		$pdf->Cell(0,$h,"$row->espacio - $row->usuario",$borde);
		$pdf->ln($h+1);
		$pdf->ln($h+3);
		// detalles de la factura
		$pdf->SetWidths($arqueo_detalle1);
		$pdf->SetAligns(array("L","C","L","R"));
		$pdf->SetFont('Times','B',9);
		$pdf->SetFillColor(255,0,0);
		$pdf->SetDrawColor(1);
		$pdf->Row(array("#","Id","Producto","Cantidad Capturada"));
		$pdf->SetFont('Times','',8);
		if($arqueo_detalles!=false){
			$r=0;
			foreach($arqueo_detalles->all as $lista){
				if(esPar($r))
			  $pdf->SetFillColor(200,0,0);
				else
			  $pdf->SetFillColor(255,0,0);

				$pdf->Row(array($r+1,$lista->id, "$lista->producto", number_format($lista->cantidad_real, 4, ".", ",")));
				$r+=1;
			}

		}
	}
	$pdf->Output();
} else {
	echo "No existe Captura de Existencias Reales en dicho periodo de tiempo";
}
?>
