<?php
$pdf=new Fpdf_multicell($orientation);
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,$title,0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
$pdf->Cell(0,5,"Impresión: ".date("d-m-Y"),0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',8);

	$g=1;
	//Table with 20 rows and 4 columns
	$pdf->SetAligns(array('L','L','R','R'));
	$pdf->SetWidths(array(15,100,30,30));
	$pdf->Row(array("#","Marca","Pares", "Importe"));
	$t_importe=0; $t_pares=0; $pre_empleado=0;$colect=array();
	foreach($datos->result() as $row) {

		$pdf->Row(array($y,$row->marca,number_format($row->pares, 0, ".",","),number_format($row->costo_total, 2, ".",",")));
		$t_importe+=$row->costo_total;
		$t_pares+=$row->pares;
	}
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array('','TOTAL',$t_pares,number_format($t_importe, 2, ".",",")));
$pdf->Output();
?>