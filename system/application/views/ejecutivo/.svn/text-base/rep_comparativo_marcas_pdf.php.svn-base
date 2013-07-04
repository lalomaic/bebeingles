<?php
$orientation='L';
$pdf=new Fpdf_multicell('L','mm','letter',1);
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(7);
//print_r($espacios);
$ancho_global=220;
if(count($datos)>0){
	$etiquetas=array(); $anchos=array();
	$pdf->AddPage();
	$pdf->Image(base_url().'images/logo_pdf.jpg',5,5,50);
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(50,5,'',0,0,'L');
	$pdf->Cell(0,5,"GRUPO PAVEL",0,1,'L');
	$pdf->Cell(50,5,'',0,0,'L');
	$pdf->Cell(0,5,'Reporte Comparativo de Marcas en Sucursales',0,1,'L');
	$pdf->SetFont('Times','',12);
	$pdf->Cell(50,5,'',0,0,'L');
	$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
	$pdf->Cell(50,5,'',0,0,'L');
	$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y"),0,1,'L');
	$pdf->ln(5);

	//Cabeceras de la tabla
	$pdf->SetFont('Times','',4);
	$pdf->SetFillColor(140,136,149);
	$pdf->SetTextColor(255,255,255);
	//CAlcular anchos dinamicos en base al maximo y minimo
	$ancho_num=$ancho_global/(count($espacios)+1);
	$pdf->Cell(50,5,"Estilo",1,0,'L',true);
	foreach($espacios as $k=>$v){
		$pdf->Cell($ancho_num,5,$espacios_tag[$k],1,0,'C',true);
	}
	$pdf->Cell(15,5,"Total",1,1,'C',true);
	$y=1;
	$t_importe=0; $t_pares=0;
	$pdf->SetFont('Times','',6);
	$t=0; $renglon=array(); $total=0; $pares_renglon=0; $suma_existencia=0; $total_existencia=0;
	$i=1;
	$pdf->SetTextColor(0,0,0);
	foreach ($datos as $k=>$v) {
		if(!($i%2))
			$pdf->SetFillColor(200,255,255);
		else
			$pdf->SetFillColor(255,255,255);
		$pdf->Cell(50,5,$v['descripcion'],1,1,'L',true);
		// 			foreach($datos[$k] as $detalle){
		// 				$renglon[]=utf8_decode($detalle['descripcion']);
		// 				for($x=$min;$x<$max;$x=$x+5){
		// 					if(isset($detalle['numero_'.$x])){
		// 						$renglon[]=round($detalle['numero_'.$x],0)."\n".$detalle['existencia_'.$x];
		// 						$pares_renglon+=round($detalle['numero_'.$x],0);
		// 						$suma_existencia+=$detalle['existencia_'.$x];
		// 					} else
			// 						$renglon[]="- \n-";
		// 				}
		// 				$renglon[]=$pares_renglon."\n".$suma_existencia;
		// 				$total_existencia+=$suma_existencia;
		// 				$pdf->Row($renglon);
		// 				$total+=$pares_renglon;
		// 				$pares_renglon=0;
		// 				$renglon=array();
		// 				$suma_existencia=0;
		$i+=1;
		// 			}
		// 			$renglon[]='TOTAL';
		// 			for($x=$min;$x<$max;$x=$x+5){
		// 				$renglon[]='';
		// 			}
		// 			$renglon[]=$total."\n".$total_existencia;
		// 			$pdf->SetFillColor(140,136,149);
		// 			$pdf->Row($renglon);
		// 			unset($etiquetas); unset($align); unset($anchos);
		// 		}

}
} else {
	$pdf->Cell(0,5,"No hay datos para los criterios seleccionados",0,1,'L');
}
$pdf->Output();
?>