<?php
$n = $traspasos->num_rows();
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetTopMargin(20);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,'Reporte de Traspasos entre Tiendas','B',1,'L');
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln($h);
$i = 1; $pag=0; $borde = 1; $u_salida=0; $u_entrada=0;$total=0; $total_pares=0;
if($traspasos->num_rows()>0){
	foreach($traspasos->result() as $row) {
		$pdf->SetWidths(array(10,12,12,100,18,18,15,15));
		$pdf->SetAligns(array("L","L","C","L","C","R","L","C"));

		if($row->espacio_fisico_id!=$u_salida or $row->espacio_fisico_recibe_id!=$u_entrada){
			if($total!=0){
				$pdf->Row(array('','', $total, "", "",'',$total_pares,""));
				$pdf->Cell(0,$h,"",'T',1);
				$pdf->Cell(32,7,"OBSERVACIONES:",0,1);
				$pdf->Cell(0,7,"",1,1);
				$pdf->Cell(0,7,"",'LBR',1);
				$pdf->Cell(0,7,"",'LBR',1);
				$pdf->ln(25);
				$pdf->Cell(15);
				$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',0,'C');
				$pdf->Cell(10);
				$pdf->Cell(50,5,"NOMBRE Y FIRMA",'T',1,'C');
				$pdf->Cell(15);
				$pdf->Cell(50,5,"$row->espacio_salida",0,0,'C');
				$pdf->Cell(10);
				$pdf->Cell(50,5,"$row->espacio_recibe",0,1,'C');
			}

			if($i>1){
				$pdf->AddPage();
			}
			$pdf->Cell(32,$h,"UBICACI�N SALIDA:");
			$pdf->Cell(0,$h,$row->espacio_salida,$borde);
			$pdf->ln($h+1);
			$pdf->Cell(32,$h,"UBICACI�N ENTRADA:");
			$pdf->Cell(0,$h,$row->espacio_recibe,$borde);
			$pdf->ln($h+1);
			$pdf->Cell(32,$h,"PERIODO:");
			$pdf->Cell(0,$h,"DEL $fecha1 AL $fecha2",$borde);
			$pdf->ln($h+1);
			$pdf->Cell(32,$h,"FECHA DE IMPRESI�N:");
			$pdf->Cell(0,$h,date("d m Y"),$borde);
			$pdf->ln(10);
			$u_salida=$row->espacio_fisico_id;
			$u_entrada=$row->espacio_fisico_recibe_id;
			$total_pares=0;
			$total=0;
			$pdf->SetFont('Times','B',10);
			$pdf->SetFillColor(200,0,0);
			$pdf->Row(array("Lote","Id Salida","Cant.","Producto","Fecha Salida" ,"Precio Compra", "Id Entrada" , "Fecha Entrada"));

		}
		$pdf->SetFont('Times','',8);
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);
		$pdf->Row(array($row->lote_id, $row->salida_id, round($row->cantidad,0), utf8_decode("$row->descripcion ". ($row->numero_mm/10)),  $row->fecha_salida, number_format($row->costo_unitario,2,".",","), "".$row->entrada_id, "".$row->fecha_entrada));
		$total_pares+=$row->cantidad;
		$total+=$row->costo_unitario*$row->cantidad;
		$espacio_salida=$row->espacio_salida;
		$espacio_recibe=$row->espacio_recibe;
		$i+=1;
		//	echo $row->fecha_entrada."%";


	}
	$pdf->Row(array('','', $total_pares, "", "",number_format($total,2,".",","),"",""));
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
	$pdf->Cell(50,5,"SUCURSAL $espacio_salida",0,0,'C');
	$pdf->Cell(10);
	$pdf->Cell(50,5,"SUCURSAL $espacio_recibe",0,1,'C');
} else {
	$pdf->Cell(50);
	$pdf->Cell(50,5,"SIN DATOS",0,0,'C');
}

$pdf->Output();
unset($pdf)
?>
