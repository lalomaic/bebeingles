<?php
function fecha_imp($date){
	if($date!='0000-00-00'){
		$fecha=substr($date, 0, 10);
		$hora=substr($date, 11, strlen($date));
		$new = explode("-",$fecha);
		$a=array ($new[2], $new[1], $new[0]);
		if(strlen($hora)>2){
			return $n_date=implode("-", $a) . " Hora: ".$hora;
		} else {
			return $n_date=implode("-", $a);
		}
	} else {
		return "Sin fecha";
	}
}

$n = count($generales->all);
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,$title,'B',1,'L');
//$pdf->ln(3);
$pdf->SetFont('Times','',8);
$h = 5;// altura
$pdf->ln(2);
$x1 = $pdf->GetX();
$x2 = $x1+25;
$y = $pdf->GetY();
//201
$generalesw= array(7,16,35,35,20,20,20,16,30);
$detallesw= array(7,20,100,20,20,20);
$i = 1;
$borde = 1;
foreach($generales->all as $row) {
	$pdf->SetXY($x1,$y);
	$pdf->SetWidths($generalesw);
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"ID Pedido Venta:",$borde,1, 'C');
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->id,$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"ALTA:",$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,fecha_imp($row->fecha_alta),$borde,1, 'C');
	$pdf->ln(3);
	$pdf->Cell(32,$h,"EMPRESA:");
	$pdf->Cell(0,$h,$row->empresa,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"CLIENTE:");
	$pdf->Cell(0,$h,$row->cliente,$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"FORMA DE COBRO:");
	$pdf->Cell(40,$h,$row->forma_cobro,$borde);
	$pdf->Cell(16,$h,"ESTATUS:");
	$pdf->Cell(30,$h,$row->estatus,$borde);
	$pdf->Cell(19,$h,"VENDEDOR:");
	$pdf->Cell(0,$h,$row->usuario,$borde);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detallesw);
	$pdf->SetAligns(array("C","C","L", "R", "R", "R"));
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0);
	$pdf->Cell(5);
	$pdf->Row(array("#","Cantidad","Producto", "Precio U.", "IVA", "Subtotal"));
	$pdf->SetFont('Times','',8);
	$i=1;
	$iva=0;
	$iva_t=0;
	$subtotal_t=0;
	foreach($detalles->all as $lista){
		$iva=$lista->cantidad*$lista->costo_unitario*$lista->tasa_impuesto/(100+$lista->tasa_impuesto);
		$subtotal=($lista->costo_unitario*$lista->cantidad)-$iva;
		$iva_t+=$iva;
		$subtotal_t+=$subtotal;
		$pdf->Cell(5);
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);
		$pdf->Row(array($i, $lista->cantidad, "$lista->producto $lista->presentacion", $lista->costo_unitario,  number_format($iva, 3, ".", ","), number_format($subtotal, 4, ".", ",")));
		$i+=1;
	}
	$pdf->Cell(5);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(147,$h,'', 'T',0);
	$pdf->Cell(20,$h,"Subtotal",1,0, "R");
	$pdf->Cell(20,$h,number_format($subtotal_t, 4, ".", ","),1,1,"R");
	$pdf->Cell(152,$h,'', 0,0);
	$pdf->Cell(20,$h,"IVA",1,0, "R");
	$pdf->Cell(20,$h,number_format($iva_t, 4, ".", ","),1,1,"R");
	$pdf->Cell(152,$h,'', 0,0);
	$pdf->Cell(20,$h,"Total",1,0, "R");
	$pdf->Cell(20,$h,number_format(($subtotal_t+$iva_t), 4, ".", ","),1,1,"R");

	if(strlen($row->observaciones)>0){
		$pdf->Cell(32,5,"OBSERVACIONES:",0,1);
		$pdf->SetFont('Times','',8);
		$pdf->MultiCell(0,4,"$row->observaciones",1,1);
	}
	$pdf->Cell(10);
	$pdf->Cell(0,7,"Morelia, Mich. a ".date("d-m-Y"),0,1,'C');

	if($i++ < $n) $pdf->AddPage();
}
$detalles->clear();
$pdf->Output();
?>
