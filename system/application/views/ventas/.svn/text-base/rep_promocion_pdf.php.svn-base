<?php

$pdf = new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 5, "Reporte de Promoci�n", 'B', 1, 'L');

$pdf->SetFont('Times', '', 9);
$pdf->Ln(1);
$pdf->Cell(20, 5, 'Id: ' . $promocion->id, 0, 0, 'L');
$pdf->Cell(0, 5, 'Fecha de captura: ' . date_format(date_create($promocion->fecha_captura), 'd-m-Y'), 0, 1, 'R');

$promocion->cproductos->get();
;
$pdf->SetWidths(array(85, 25, 30, 25, 30));
$pdf->Row(array("Producto", "Fecha inicio", "Fecha terminaci�n", "Limite cantidad", "Precio promocional"));
$pdf->Row(array($promocion->cproductos->descripcion, $promocion->fecha_inicio,
		$promocion->fecha_final, $promocion->limite_cantidad,
		"$" . $promocion->precio_venta));

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(0, 10, 'Dias v�lidos para la promoci�n:', 0, 1, 'L');
$pdf->SetFont('Times', '', 9);
$pdf->ln(1);
$pdf->SetWidths(array(65,65,65));
//$pdf->Row(array("dia 1","dia 2","dia 3"));

$separadias = explode(",", $promocion->dias_horas);
for ($o = 0; $o < sizeof($separadias); $o++) {
	$separados[$o] = explode("&", $separadias[$o]);
}
$cols=array("","","");
$cont=0;
for ($l = 0; $l < sizeof($separados); $l++) {
	switch ($separados[$l][0]) {
		case '1':
			$cols[$cont]="Lunes de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;
		case '2':
			$cols[$cont]="Martes de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;
		case '3':
			$cols[$cont]="Miercoles de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;

		case '4':
			$cols[$cont]="Jueves de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;

		case '5':
			$cols[$cont]="Viernes de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;

		case '6':
			$cols[$cont]="Sabado de ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;

		case '7':
			$cols[$cont]="Domingo ".$separados[$l][1]." horas a las ".$separados[$l][2];
			break;
	}
	$cont++;
	if(($cont==3)||($l==(sizeof($separados)-1))){
		$cont =0;
		$pdf->Row(array($cols[0],$cols[1],$cols[2]));
		$cols=array ("","","");
	}

}

$pdf->SetFont('Times', 'B', 9);
$pdf->Cell(0, 10, 'Sucursales donde es valida la promoci�n para la promoci�n:', 0, 1, 'L');
$pdf->SetFont('Times', '', 9);
$pdf->ln(1);
$pdf->SetWidths(array(65,65,65));
$spaces= explode(",", $promocion->espacios_fisicos);
$cols=array("","","");
$cont=0;
for($i=0;$i<sizeof($spaces);$i++){
	foreach($sf->all as $lugar){
		if($spaces[$i]==$lugar->id){
			$cols[$cont]=$lugar->tag;
			$cont++;
		}
	}
	if(($cont==3)||($i==(sizeof($spaces)-1))){
		$cont =0;
		$pdf->Row(array($cols[0],$cols[1],$cols[2]));
		$cols=array ("","","");
	}
}


$pdf->Cell(0, 5, 'Promoci�n dada de alta por: ' . $usuariop->nombre, 0, 1, 'R');
$pdf->Output();
exit();
?>