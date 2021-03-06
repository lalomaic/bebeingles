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
$pdf->Cell(0,5,utf8_decode($title),'B',1,'L');
//$pdf->ln(3);
$pdf->SetFont('Times','',8);
$h = 5;// altura
//$pdf->ln(2);

//201
$generalesw= array(7,16,35,35,20,20,20,16,30);
$detallesw= array(13,20,120,20,20);
$borde = 1;
foreach($generales->all as $row) {

	$pdf->SetWidths($generalesw);
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"ID FAC:",$borde,1, 'C');
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->id,$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"FOLIO FAC:",$borde,1, 'C');
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->folio_factura,$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"FECHA FAC.:",$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->fecha,$borde,1, 'C');
	$pdf->ln(3);
	$pdf->Cell(32,$h,"EMPRESA:");
	$pdf->Cell(0,$h,utf8_decode($row->empresa),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"SUCURSAL:");
	$pdf->Cell(0,$h,utf8_decode($row->espacio),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"PROVEEDOR:");
	$pdf->Cell(0,$h,utf8_decode($row->proveedor),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"Id Pedido Compra:");
	$pdf->Cell(30,$h,$row->pr_pedido_id,$borde);
	$pdf->Cell(19,$h,"AGENTE:");
	$pdf->Cell(0,$h,utf8_decode($row->usuario),$borde);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detallesw);
	$pdf->SetAligns(array("C","C","L", "R", "R"));
	$pdf->SetLeftMargin(10);
	$pdf->SetTopMargin(15);
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0);
	$pdf->Row(array("#","Cantidad","Producto", "Precio U.", "Subtotal"));
	$pdf->SetFont('Times','',8);
	$i=1;
	$iva=0;
	$iva_t=0;
	$subtotal_t=0; $total=0;
	foreach($detalles->all as $lista){
		//$iva=$lista->iva*$lista->costo_unitario*$lista->cantidad/($lista->iva+100);
		$subtotal=($lista->costo_total);
		//$iva_t+=$iva;
		//$subtotal_t+=$lista->costo_total;
		//		$subtotal_t+=$subtotal;
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);
			
		$productodecode=utf8_decode("$lista->descripcion"." $lista->colores # ". ($lista->numero_mm));
		//$pdf->Row(array($i, round($lista->cantidad), "$lista->descripcion # ". ($lista->numero_mm), $lista->costo_unitario,  number_format($subtotal, 2, ".", ",")));
		$pdf->Row(array($i, round($lista->cantidad), $productodecode, $lista->costo_unitario,  number_format($subtotal, 2, ".", ",")));
		
		$total+=$lista->cantidad;
		$i+=1;
	
	}

	//$pdf->Cell(5);
	$pdf->Row(array('', $total, "","", ""));

	$pdf->Cell(5);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(148,$h,'', 'T',0);
	$pdf->Cell(20,$h,"Subtotal",1,0, "R");
	$pdf->Cell(20,$h,number_format(($row->monto_total+$row->descuento)-$row->iva_total, 2, ".", ","),1,1,"R");
	$pdf->Cell(153,$h,'', 0,0);
	$pdf->Cell(20,$h,"Descuento",1,0, "R");
	$pdf->Cell(20,$h,number_format(($row->descuento), 2, ".", ","),1,1,"R");
	$pdf->Cell(153,$h,'', 0,0);
	$pdf->Cell(20,$h,"Subtotal 2",1,0, "R");
	$pdf->Cell(20,$h,number_format($row->monto_total-$row->iva_total, 2, ".", ","),1,1,"R");
	$pdf->Cell(153,$h,'', 0,0);
	$pdf->Cell(20,$h,"IVA",1,0, "R");
	$pdf->Cell(20,$h,number_format($row->iva_total, 2, ".", ","),1,1,"R");
	$pdf->Cell(153,$h,'', 0,0);
	$pdf->Cell(20,$h,"Total",1,0, "R");
	$pdf->Cell(20,$h,number_format($row->monto_total, 2, ".", ","),1,1,"R");

	if(strlen($row->observaciones)>0){
		$pdf->Cell(32,5,"OBSERVACIONES:",0,1);
		$pdf->SetFont('Times','',8);
		$pdf->MultiCell(0,4,"$row->observaciones",1,1);
	}
	//$pdf->Cell(10);
	$pdf->Cell(0,7,"Morelia Michoacan, a ".date("d-m-Y"),0,1,'C');

//	if($i++ < $n) 
//	$pdf->AddPage();
}
$pdf->Output();
?>
