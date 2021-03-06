<?php
function esPar($num){
	return !($num%2);
}
//$pdf=new Fpdf_multicell();
$pdf= new Optimizar_memoria();
$name='reporte_arqueo_'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
if($arqueo!=false){
	foreach($arqueo->all as $row) {
		$pdf->AddPage("P");
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(0,5,utf8_decode($title).": Faltantes",'B',1,'L');
		$pdf->ln(3);
		$pdf->SetFont('Times','',8);
		$h = 5;// altura
		$pdf->ln($h);
		$x1 = $pdf->GetX();
		$x2 = $x1+25;
		$y = $pdf->GetY();
		$arqueo1= array(7,16,35,35,20,20,20,16,30);
		$arqueo_detalle1 = array(10,80,18,18,18, 20);
		$i = 1;
		$borde = 1;
		$pdf->SetXY($x1,$y);
		$pdf->SetWidths($arqueo1);
		$pdf->Cell(15,$h,"Id Arqueo:");
		$pdf->Cell(23,$h,$row->id,$borde);
		$pdf->Cell(4);
		//$pdf->SetTextColor(100);
		$pdf->Cell(12,$h,"FECHA:");
		$pdf->Cell(55,$h,  date('d-m-Y', strtotime($row->fecha_inicio))." ".date('H:I', strtotime($row->hora_inicio)).
				" - ".date('d-m-Y', strtotime($row->fecha_inicio))." ".date('H:I', strtotime($row->hora_inicio)),$borde);
		$pdf->Cell(4);
		$pdf->Cell(16,$h,"ESTATUS:");
		$pdf->Cell(0,$h,$row->estatus,$borde);
		$pdf->ln($h+1);
		$pdf->Cell(41,$h,"UBICACION - CAPTURISTA:");
		$pdf->Cell(0,$h,utf8_decode("$row->espacio - $row->usuario"),$borde);
		$pdf->ln($h+1);
		$pdf->ln($h+3);
		// detalles de la factura
		$pdf->SetWidths($arqueo_detalle1);
		$pdf->SetAligns(array("C","C","C","C","C","C"));
		$pdf->SetFont('Times','B',8);
		$pdf->SetFillColor(255,0,0);
		$pdf->SetDrawColor(1);
		$pdf->Row(array("Lote","Producto","FISICO","SISTEMA","DIF", "TOTAL"));
		$pdf->SetAligns(array("R","L","R","R","R","R"));
		$pdf->SetFont('Times','',7.5);
		if(count($datos)>0){
			$r=0;
			$total_salidas=0;
			foreach($datos as $lista){

				if($lista['accion_id']==5 or $lista['accion_id']==4){
			  if(esPar($r))
			  	$pdf->SetFillColor(220,0,0);
			  else
			  	$pdf->SetFillColor(255,0,0);

			  $pdf->Row(array($lista['lote_id'], $lista['producto']." # ".$lista['numero']/10,
			  		number_format($lista['cantidad_real'], 0, ".", ","),
			  		number_format($lista['cantidad_sistema'], 0, ".", ","),
			  		number_format($lista['diferencia'], 0, ".", ","),
			  		number_format(
			  				abs($lista['precio_unitario']*$lista['diferencia']), 2, ".", ",")));
			  $r+=1;
			  $total_salidas+=$lista['precio_unitario']*$lista['diferencia']*(-1);
				}
				unset($lista);
			}
			$pdf->SetFillColor(220,0,0);
			$pdf->Row(array('','', '','','','','','', 'Total', number_format($total_salidas, 4,".",",")));

			$r=0;
			$pdf->AddPage('L');
			$pdf->SetFont('Times','B',10);
			$pdf->Cell(0,5,utf8_decode($title)." Sobrantes",'B',1,'L');
			$pdf->ln(3);
			$pdf->SetFont('Times','',8);
			$h = 5;// altura
			$pdf->ln($h);
			$x1 = $pdf->GetX();
			$x2 = $x1+25;
			$y = $pdf->GetY();
			$arqueo1= array(7,16,35,35,20,20,20,16,30);
			$arqueo_detalle1 = array(8, 10,80,14,17,14,40,12);
			$i = 1;
			$borde = 1;
			$pdf->SetXY($x1,$y);
			$pdf->Cell(15,$h,"Id Arqueo:");
			$pdf->Cell(23,$h,$row->id,$borde);
			$pdf->Cell(4);
			//$pdf->SetTextColor(100);
			$pdf->Cell(12,$h,"FECHA:");
			$pdf->Cell(55,$h,  date('d-m-Y', strtotime($row->fecha_inicio))." ".date('H:I', strtotime($row->hora_inicio)).
					" - ".date('d-m-Y', strtotime($row->fecha_inicio))." ".date('H:I', strtotime($row->hora_inicio)),$borde);
			$pdf->Cell(4);
			$pdf->Cell(16,$h,"ESTATUS:");
			$pdf->Cell(0,$h,$row->estatus,$borde);
			$pdf->ln($h+1);
			$pdf->Cell(41,$h,"UBICACION - CAPTURISTA:");
			$pdf->Cell(0,$h,"$row->espacio - $row->usuario",$borde);
			$pdf->ln($h+1);
			$pdf->ln($h+3);
			$pdf->SetFont('Times','B',8);
			$pdf->SetFillColor(255,0,0);
			$pdf->SetDrawColor(1);
			$pdf->SetAligns(array("C","C","C","C","C","C"));
			$pdf->Row(array("Lote","Producto","FISICO","SISTEMA","DIF", "TOTAL"));
			$pdf->SetAligns(array("R","L","R","R","R","R"));
			$pdf->SetFont('Times','',7.5);
			$total_entradas=0;
			//P gina de Entradas
			foreach($datos as $lista){

				if($lista['accion_id']==2 or $lista['accion_id']==3){
			  if(esPar($r))
			  	$pdf->SetFillColor(220,0,0);
			  else
			  	$pdf->SetFillColor(255,0,0);
			  $pdf->Row(array($lista['lote_id'], $lista['producto']." # ".$lista['numero']/10,
			  		number_format($lista['cantidad_real'], 0, ".", ","),
			  		number_format($lista['cantidad_sistema'], 0, ".", ","),
			  		number_format($lista['diferencia'], 0, ".", ","),
			  		number_format(
			  				abs($lista['precio_unitario']*$lista['diferencia']), 2, ".", ",")));
			  $r+=1;
			  $total_entradas+=$lista['precio_unitario']*$lista['diferencia'];
				}
			}
			$pdf->SetFillColor(220,0,0);
			$pdf->Row(array('','', '','','','','','', 'Total', number_format($total_entradas, 4,".",",")));
		}
	}
	$pdf->Output();
	?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);
} else {
	echo "No existe Captura de Existencias Reales en dicho periodo de tiempo";
}
?>
