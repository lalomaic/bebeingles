<?php
$pdf=new Fpdf_multicell($orientation);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(5);
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,$title,0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y"),0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',5);

//Obtener el numero de columnas del reporte
$columnas=count($sucursales_id);
//Fijar las alineaciones
$align[0]='L'; $c=1; $anchos[0]=35; $etiquetas[0]="MARCA";
foreach ($sucursales_tag as $suc=>$v){
	$align[$c]='C';
	$anchos[$c]=10;
	$etiquetas[$c]=$v;
	$c+=1;

}
$align[$c]='R';
$anchos[$c]=12;
$etiquetas[$c]="TOTAL";
$pdf->SetAligns($align);
$pdf->SetWidths($anchos);
//Cabeceras de la tabla
$pdf->Row($etiquetas);

$y=1;
$t_importe=0; $t_pares=0; $pre_empleado=0;$colect=array();
$pdf->SetFont('Times','',5);

$t=0; $renglon=array(); $total=0; $pares_renglon=0;
foreach($datos as $key){
	$s=0;
	$renglon[]=utf8_decode($key['tag']); $total_renglon=0;
	for($s;$s<$columnas;$s++){
		if(isset($key['costo_'.$s])) {
			$renglon[]="$ ".number_format($key['costo_'.$s],0,".",",")."\n". round($key['pares_'.$s],0);
			$total_renglon+=$key['costo_'.$s];
			$pares_renglon+=$key['pares_'.$s];

		} else
			$renglon[]=0;

	}
	$renglon[]="$ ".number_format($total_renglon,0,".",","). "\n". $pares_renglon;
	$total+=$total_renglon;
	$pdf->Row($renglon);
	$renglon=array();
	$total_renglon=0;
	$pares_renglon=0;
}


/*	foreach($datos->result() as $row) {

$pdf->Row(array($y,utf8_decode($row->marca),number_format($row->pares, 0, ".",","),number_format($row->costo_total, 2, ".",",")));
$t_importe+=$row->costo_total;
$t_pares+=$row->pares;
$y+=1;
}
$pdf->SetFont('Times','B',8);
$dev_r=$dev->row();
$pdf->Row(array('','SUBTOTAL',number_format($t_pares,0,".",","),number_format($t_importe, 2, ".",",")));
$pdf->Row(array('','DEVOLUCIONES EN EL PERIODO','-'.number_format($dev_r->cantidad,0,".",","),'-'.number_format($dev_r->costo_unitario, 2, ".",",")));
$pdf->Row(array('','TOTAL',number_format($t_pares-$dev_r->cantidad,0,".",","),number_format(($t_importe-$dev_r->costo_unitario), 2, ".",",")));*/
$pdf->Output();
?>