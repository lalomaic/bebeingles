<?php
$n = count($generales->all);
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetTopMargin(20);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,utf8_decode($title),'B',1,'L');
//$pdf->ln(3);
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln($h);
$x1 = $pdf->GetX();
$x2 = $x1+25;
$y = $pdf->GetY();
//201
$generalesw= array(7,16,35,35,20,20,20,16,30);
$detallesw= array(10,25,135,26);
$i = 1; $pag=0;
$borde = 1;
foreach($generales->all as $row) {
	if($pag>0)
		$pdf->AddPage();
	// 	$pdf->SetTopMargin(15);
	$pdf->SetXY($x1,$y+10);
	$pdf->SetWidths($generalesw);
	$pdf->Cell(165);
	$pdf->Cell(30,$h,"TRASPASO ID:",$borde,1, 'C');
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(165);
	$pdf->Cell(30,$h,$row->id,$borde,1, 'C');
	$pdf->Cell(165);
	$pdf->Cell(30,$h,"FECHA ENVIO:",$borde,1, 'C');
	$pdf->Cell(165);
	$pdf->Cell(30,$h,$row->fecha_envio,$borde,1, 'C');
        $pdf->Cell(165);
	$pdf->Cell(30,$h,"FECHA RECEPCION:",$borde,1, 'C');
	$pdf->Cell(165);
	$pdf->Cell(30,$h,$row->fecha_recibe,$borde,1, 'C');
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"UBICACION SALIDA:");
	$pdf->Cell(0,$h,$espacio_envia,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"UBICACION ENTRADA:");
	$pdf->Cell(0,$h,$espacio_recibe,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"CAPTURISTA:");
	$pdf->Cell(0,$h,$usuario,$borde);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detallesw);
	$pdf->SetAligns(array("C","C","L","L"));
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0,100);
	$pdf->Row(array("#","Cantidad","Producto","Precio Compra"));
	$pdf->SetFont('Times','',8);
	$i=1; $total=0;
	foreach($detalles->all as $lista){
                if(!($i%2))
                        $pdf->SetFillColor(200,0,0);
                else
                        $pdf->SetFillColor(255,0,0);
                $pdf->Row(array($i, round($lista->cantidad),utf8_decode($lista->descripcion." ".$lista->numero),$lista->precio));
                $total+=$lista->cantidad;
                $i+=1;
		
	}
	$pdf->Row(array( "Total: ",$total, "",""));
	//$pdf->Cell(0,$h,"",'T',1);
	$pdf->Cell(32,7,"OBSERVACIONES:",0,1);
	$pdf->Cell(0,7,"",1,1);
	$pdf->Cell(0,7,"",'LBR',1);
	$pdf->Cell(0,7,"",'LBR',1);
	$pdf->ln(25);
	$pdf->Cell(15);
	$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',0,'C');
	$pdf->Cell(10);
	$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',0,'C');
	$pdf->Cell(10);
	$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',1,'C');
	$pdf->Cell(15);
	$pdf->Cell(50,5,"ENVIA",0,0,'C');
	$pdf->Cell(10);
	$pdf->Cell(50,5,"DE QUIEN TRASLADA",0,0,'C');
	$pdf->Cell(10);
	$pdf->Cell(50,5,"RECIBE",0,1,'C');

	if($i++ < $n) $pdf->AddPage();
	$pag+=1;
}
$pdf->Output();
unset($pdf)
?>
