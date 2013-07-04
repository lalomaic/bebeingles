<?php
$pdf=new Fpdf_multicell($orientation,'mm','letter',1);
$pdf->SetTopMargin(5);
$pdf->SetLeftMargin(10);

$ancho_global=210;
if(count($sucursales_tag)>0){
	foreach ($sucursales_tag as $k=>$v){
		$etiquetas=array(); $anchos=array();
		//CAlcular anchos dinamicos en base al maximo y minimo
		$etiquetas[0]="DESCRIPCION"; $anchos[0]=70; $align[0]="L";
		$max=$sucursales_max[$k];
		// 		echo $max;
		$min=$sucursales_min[$k];
		// 		echo "<br/>-".$min;
		if($max==$min)
			$rango=2;
		else
			$rango=round((10+$max-$min)/5,0);

		// 		echo "<br/>".$rango."%%%%%%<br/>";

		for($x=$min;$x<$max;$x=$x+5){
			$align[]='C';
			/*			if(floor($ancho_global/$rango)>30)
				$anchos[]=20;*/
			$anchos[]=floor($ancho_global/$rango);
			$etiquetas[]=$x/10;
		}
		$align[]='C';
		$anchos[]=$ancho_global/$rango;
		$etiquetas[]="T/E";
		$pdf->AddPage();
		$pdf->Image(base_url().'images/logo_pdf.jpg',5,5,50);
		$pdf->SetFont('Times','B',12);
		$pdf->Cell(50,5,'',0,0,'L');
		$pdf->Cell(0,5,"GRUPO PAVEL",0,1,'L');
		$pdf->Cell(50,5,'',0,0,'L');
		$pdf->Cell(0,5,$title,0,1,'L');
		$pdf->SetFont('Times','',12);
		$pdf->Cell(50,5,'',0,0,'L');
		$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
		$pdf->Cell(50,5,'',0,0,'L');
		$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y"),0,1,'L');
		$pdf->ln(5);

		$pdf->SetFont('Times','B',12);
		$pdf->Cell(0,5,"$v",'B',1,'L');
		$pdf->ln(5);

		//Cabeceras de la tabla
		$pdf->SetFont('Times','',6);
		//print_r($datos);
		$pdf->SetAligns($align);
		$pdf->SetWidths($anchos);
		$pdf->SetFillColor(140,136,149);
		$pdf->Row($etiquetas);
		$y=1;
		$t_importe=0; $t_pares=0;
		$pdf->SetFont('Times','',6);
		$t=0; $renglon=array(); $total=0; $pares_renglon=0; $suma_existencia=0; $total_existencia=0;
		//echo $v."<br/>";

		if(isset($datos[$k])){
			$i=1;
			foreach($datos[$k] as $detalle){
				if(!($i%2))
					$pdf->SetFillColor(200,255,255);
				else
					$pdf->SetFillColor(255,255,255);
				$renglon[]=utf8_decode($detalle['descripcion']);
				for($x=$min;$x<$max;$x=$x+5){
					if(isset($detalle['numero_'.$x])){
						$renglon[]=round($detalle['numero_'.$x],0)."\n".$detalle['existencia_'.$x];
						$pares_renglon+=round($detalle['numero_'.$x],0);
						$suma_existencia+=$detalle['existencia_'.$x];
					} else
						$renglon[]="- \n-";
				}
				$renglon[]=$pares_renglon."\n".$suma_existencia;
				$total_existencia+=$suma_existencia;
				$pdf->Row($renglon);
				$total+=$pares_renglon;
				$pares_renglon=0;
				$renglon=array();
				$suma_existencia=0;
				$i+=1;
			}
			$renglon[]='TOTAL';
			for($x=$min;$x<$max;$x=$x+5){
				$renglon[]='';
			}
			$renglon[]=$total."\n".$total_existencia;
			$pdf->SetFillColor(140,136,149);
			$pdf->Row($renglon);
			unset($etiquetas); unset($align); unset($anchos);
		}

	}
} else {
	$pdf->Cell(0,5,"No hay datos para los criterios seleccionados",0,1,'L');
}
$pdf->Output();
?>