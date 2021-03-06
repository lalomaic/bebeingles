<?php
$pdf=new Fpdf_multicell($orientation="P");
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,"REPORTE DE PARES VENDIDOS POR EMPLEADO",0,1,'L');
$pdf->Cell(0,5,"SUCURSAL: $espacio_nombre",0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
$pdf->Cell(0,5,"Impresión: ".date("d-m-Y"),0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',8);

if($ventas_empleados!=false){
	$g=1;
	//Table with 20 rows and 4 columns
	$pdf->SetAligns(array('L','L','R'));
	$pdf->SetWidths(array(15,100,30));
	$pdf->Row(array("#","Empleado","Pares de Zapatos"));
	$t_importe=0; $t_pares=0; $pre_empleado=0;$colect=array();
	foreach($ventas_empleados->all as $row) {
		if($pre_empleado!=$row->empleado_id){
			$g+=1;
			$colect[$g]["pares"]=0;
			$colect[$g]["importe_neto"]=0;

		}
		$colect[$g]["nombre"]=$row->empleado;

		if($row->cobro_vales==0){
			$colect[$g]["pares"]+=$row->cantidad;
			$colect[$g]["importe_neto"]+=$row->importe_total;
		}
		else if($row->cobro_vales>0){
			$cantidad=$row->cantidad;
			$vale=ceil($row->cobro_vales);
			$importe=$row->importe_total;
			$cantidad_calc=round($cantidad-round(($cantidad*$vale/$importe),0),0);
			$colect[$g]['pares']+=$cantidad_calc;
			$colect[$g]["importe_neto"]+=$importe-$row->cobro_vales;
		}
		$pre_empleado=$row->empleado_id;
	}
	$y=1;
	foreach($colect as $row) {

		$pdf->Row(array($y,$row['nombre'],number_format($row['pares'], 0, ".",",")));
		$t_pares+=$row['pares'];
	}
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array('','TOTAL',$t_pares));
} else
	$pdf->Cell(0,5,"Sin registros",0,1,'L');

$pdf->Output();
?>