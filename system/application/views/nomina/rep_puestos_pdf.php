
<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->Cell(0,5,$title.date(' (d-m-Y)'),0,1,'C');
$pdf->ln();
$pdf->Cell(0,5,'Resultados: '.count($puestos),0,1,'C');
$pdf->SetFont('Times','',8);
//201
$widths1=array(20,180);
$aligns1=array('R','L');

$widths2=array(100,100);
$aligns2=array('C','C');

$widths3=array(80,20,80,20);
$aligns3=array('L','R','L','R');

$widths4=array(80,20,80,20);
$aligns4=array('R','R','R','R');

$widths5=array(180,20);
$aligns5=array('R','R');

foreach($puestos as $puesto)
	{
	// parsear datos de prestaciones del puesto
	$total_prestaciones=(float)0;
	$prestaciones_puesto=array();
	if(strlen($puesto->prestaciones)>0)
		{
		$ps=explode(',',$puesto->prestaciones);
		if(count($ps)>0)
			{
			foreach($ps as $p)
				{
				list($pid,$cantidad)=explode(':',$p);
				$pid=(int)$pid;
				if($pid>0)
					{
					$total_prestaciones+=(float)$cantidad;
					$prestaciones_puesto[]=array($prestaciones[$pid],'$ '.number_format($cantidad,2));
					}
				}
			}
		}
	// parsear datos de deducciones del puesto
	$total_deducciones=(float)0;
	$deducciones_puesto=array();
	if(strlen($puesto->deducciones)>0)
		{
		$ds=explode(',',$puesto->deducciones);
		if(count($ds)>0)
			{
			foreach($ds as $d)
				{
				list($did,$cantidad)=explode(':',$d);
				$did=(int)$did;
				if($did>0)
					{
					$total_deducciones+=(float)$cantidad;
					$deducciones_puesto[]=array($deducciones[$did],'$ '.number_format($cantidad,2));
					}
				}
			}
		}
	// crear renglones de prestaciones y deducciones
	$datos=array();
	$i=0;
	while($i<50 && (count($prestaciones_puesto)+count($deducciones_puesto))>0 )
		{
		if(count($prestaciones_puesto)>0)
			list($datos[$i][0],$datos[$i][1])=array_pop($prestaciones_puesto);
		else
			$datos[$i][0]=$datos[$i][1]="";
		if(count($deducciones_puesto)>0)
			list($datos[$i][2],$datos[$i][3])=array_pop($deducciones_puesto);
		else
			$datos[$i][2]=$datos[$i][3]="";
		$i++;
		}
	// imprimir
	$pdf->ln();
	
	$pdf->SetFillColor(192,192,192);
	$pdf->SetWidths($widths1);
	$pdf->SetAligns($aligns1);
	$pdf->Row(array('ID: '.$puesto->id, strtoupper($puesto->tag)));
	
	$pdf->SetFillColor(255,255,255);
	$pdf->SetWidths($widths2);
	$pdf->SetAligns($aligns2);
	$pdf->Row(array('PRESTACIONES', 'DEDUCCIONES'));
	
	$pdf->SetWidths($widths3);
	$pdf->SetAligns($aligns3);
	foreach($datos as $dato)
		$pdf->Row($dato);
		
	$pdf->SetFillColor(220,220,220);
	$pdf->SetWidths($widths4);
	$pdf->SetAligns($aligns4);
	$pdf->Row(array("SUBTOTAL",'$ '.number_format($total_prestaciones,2),"SUBTOTAL",'$ '.number_format($total_deducciones,2)));

	$pdf->SetFillColor(192,192,192);
	$pdf->SetWidths($widths5);
	$pdf->SetAligns($aligns5);
	$pdf->Row(array('TOTAL', '$ '.number_format(($total_prestaciones- $total_deducciones),2) ));
	}
$pdf->Output();

// detener la ejecucion para que no salga error
exit();
?>
