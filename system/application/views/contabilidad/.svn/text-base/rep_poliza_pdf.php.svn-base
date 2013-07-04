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
$pdf->ln(2);
$x1 = $pdf->GetX();
$x2 = $x1+25;
$y = $pdf->GetY();
//201
$generalesw= array(7,16,35,35,20,20,20,16,30);
$detallesh= array(7,45,100,20,20);
$detallesw= array(7,15,15,15,100,20,20);
$i = 1;
$borde = 1;
foreach($generales->all as $row) {
	$pdf->SetXY($x1,$y);
	$pdf->SetWidths($generalesw);
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"ID P�liza:",$borde,1, 'C');
	$pdf->SetFillColor(255,0,0);
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->id,$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(45,$h,"FECHA:",$borde,1, 'C');
	$pdf->Cell(150);
	$pdf->SetFont('Times','',9);
	$pdf->Cell(45,$h,$row->fecha,$borde,1, 'C');
	$pdf->ln(3);
	if(isset($row->pago))
		$p1="(Id Pago; $row->pago, Id Factura Proveedor: $row->factura)";
	else if(isset($row->cobro))
		$p1="";
	else
		$p1="";
	// 	$pdf->MultiCell(0,4,"$row->concepto $p1",1,1);
	$pdf->Cell(32,$h,"Concepto:");
	$pdf->Cell(0,$h,utf8_decode("$row->concepto $p1"),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"EMPRESA:");
	$pdf->Cell(0,$h,utf8_decode($row->empresa),$borde);
	$pdf->ln($h+1);
	$pdf->Cell(32,$h,"Espacio F�sico:");
	$pdf->Cell(0,$h,utf8_decode($row->espacio),$borde);
	$pdf->ln($h+1);
	$pdf->ln($h+3);
	// detalles de la factura
	$pdf->SetWidths($detallesh);
	$pdf->SetAligns(array("L","C","L", "R", "R"));
	$pdf->SetFont('Times','B',9);
	$pdf->SetFillColor(200,0,0);
	$pdf->Cell(5);
	$pdf->Row(array("#","Cuenta","Nombre", "Debe.", "Haber"));
	$pdf->SetFont('Times','',7);
	$i=1;
	$iva=0;
	$iva_t=0;
	$subtotal_t=0;
	$pdf->SetAligns(array("L","L","L","L", "L", "R", "R",));
	$pdf->SetWidths($detallesw);
	if($detalles!=false){
		$debe_t=0;
		$haber_t=0;
		foreach($detalles->all as $lista){
			$pdf->Cell(5);
			$pdf->border=0;
			$pdf->SetFillColor(255,0,0);
			if($lista->debe==0)
				$lista->debe1="";
			else
				$lista->debe1=number_format($lista->debe, 2, ".", ",");
			if($lista->haber==0)
				$lista->haber1="";
			else
				$lista->haber1=number_format($lista->haber, 2, ".", ",");
			//echo "%".$lista->deposito."%";
			if($lista->cobro_id>0 or $lista->cobro_id!='')
				$info=$lista->tag." $lista->detalle Factura Id: $lista->cl_factura_id ";
			else if ($lista->deposito!='')
				$info=$lista->tag." Deposito Id: $lista->deposito";
			else
				$info=$lista->tag. "$lista->detalle ($row->fecha)";
			$pdf->Row(array($i, $lista->cta, $lista->scta, $lista->sscta, utf8_decode($info),  $lista->debe1, $lista->haber1));
			$debe_t+=$lista->debe;
			$haber_t+=$lista->haber;

			$i+=1;
		}
		$pdf->Cell(5);
		$pdf->SetFillColor(200,0,0);
		$pdf->Row(array("", "", "", "", "",  number_format($debe_t, 2, ".", ","), number_format($haber_t, 2, ".", ",")));

	} else {
		$pdf->Cell(0,5,"Ocurri� un error con la p�liza, no se pueden encontrar los detalles");
	}

	$pdf->Cell(5);
	$pdf->SetFont('Times','B',9);
	$pdf->Cell(32,5,"",0,1);
	$pdf->SetFont('Times','',8);

	$pdf->Cell(10);
	$pdf->Cell(0,7,"Morelia, Mich. a ".date("d-m-Y"),0,1,'C');

	if(isset($facturas)==true){
		//Imprimir listado de facturas de poliza de ingresos
		$pdf->SetWidths(array(20,130,40));
		$pdf->SetAligns(array("C","L","R"));
		$pdf->SetFont('Times','B',9);
		$pdf->SetFillColor(200,0,0);
		$pdf->Cell(5);
		$pdf->Row(array("Factura Id","Cliente/Proveedor","Folio"));
		$pdf->SetFont('Times','',7);
		foreach($facturas->all as $row66){
			$pdf->Cell(5);
			$pdf->border=0;
			$pdf->SetFillColor(255,0,0);
			$pdf->Row(array($row66->id, utf8_decode($row66->razon_social)." Monto: $". number_format($row66->monto_total,2,".",","), $row66->folio_factura));
		}
		$pdf->Cell(5);
		$pdf->SetFillColor(200,0,0);
		$pdf->Row(array("", "", ""));

	}
}
$pdf->Output();
unset($pdf);
?>
