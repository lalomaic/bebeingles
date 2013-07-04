<?php
function fecha_imp($date){
	if($date!='0000-00-00'){
		$new = explode("-",$date);
		$a=array ($new[2], $new[1], $new[0]);
		return $n_date=implode("/", $a);
	} else {
		return "Sin fecha";
	}
}
$pdf= new Fpdf_multicell();
//$name='entrega_inventario_'. date("d_m_Y") .'.pdf';
//$pdf->Open($name);
//$pdf=new PDF($orientation='L');
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Cell(0,7,'RESPONSIVA DE INVENTARIO',0,1,'C');
$pdf->SetFont('Times','',10);
$pdf->Cell(20,7,'TIENDA: ',1,0,'L');
$pdf->Cell(60,7,utf8_decode($espacio->tag),1,0,'L');

$pdf->Cell(30,7,'RAZ�N SOCIAL: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($espacio->razon_social),1,1,'L');
$pdf->MultiCell(0,4,utf8_decode("DOMICILIO:\n".$espacio->calle. " # ".$espacio->numero_exterior ." Col. ". $espacio->colonia ." C.P. ".$espacio->codigo_postal. ", $espacio->localidad, $espacio->estado "),1,'L');

$pdf->Cell(30,7,'R.F.C. : ',1,0,'L');
$pdf->Cell(50,7,"$espacio->rfc",1,0,'L');
$pdf->Cell(30,7,'FECHA : ',1,0,'L');
$pdf->Cell(0,7,"$fecha",1,1,'L');

$pdf->SetFont('Times','',8);
$pdf->ln(10);

//Table with  columns
$pdf->SetWidths(array(20,50,30));
$pdf->SetAligns(array('R','L','R'));
if(count($inventario)>0){
	$saldo=0;
	$saldo_t=0;
	$pdf->Row(array("CANTIDAD", "MARCA", "MONTO_TOTAL"));
	$total_pares=0; $total_dinero=0;
	foreach($inventario as $row) {
		if($row['existencias']>0){
			$pdf->SetAligns(array('R','L','R'));
			$pdf->Row(array($row['existencias'],utf8_decode($row['descripcion']),number_format($row['precio_venta'],2,".",",")));
			$total_pares+=$row['existencias'];
			$total_dinero+=$row['precio_venta'];
		}
		unset($row);
	}
	$pdf->SetFont('Times','',12);
	$pdf->Row(array($total_pares,"TOTALES","$".number_format($total_dinero,2,".",",")));

	// 		$pdf->Row(array('','','','','','Total', number_format($saldo_t, 2, ".",",")));
}
$pdf->AddPage();
$pdf->Cell(0,7,'RESPONSIVA DE INVENTARIO',0,1,'C');
$pdf->SetFont('Times','',10);
$pdf->Cell(20,7,'TIENDA: ',1,0,'L');
$pdf->Cell(60,7,utf8_decode($espacio->tag),1,0,'L');

$pdf->Cell(30,7,'RAZ�N SOCIAL: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($espacio->razon_social),1,1,'L');
$pdf->MultiCell(0,4,utf8_decode("DOMICILIO:\n".$espacio->calle. " # ".$espacio->numero_exterior ." Col. ". $espacio->colonia ." C.P. ".$espacio->codigo_postal. ", $espacio->localidad, $espacio->estado "),1,'L');

$pdf->Cell(30,7,'R.F.C. : ',1,0,'L');
$pdf->Cell(50,7,"$espacio->rfc",1,0,'L');
$pdf->Cell(30,7,'FECHA : ',1,0,'L');
$pdf->Cell(0,7,"$fecha",1,1,'L');

$pdf->ln(10);

$pdf->Cell(30,7,"",0,0,'L');
$pdf->Cell(50,7,"ENTREGA:",0,0,'C');
$pdf->Cell(50,7,"",0,0,'L');
$pdf->Cell(50,7,"FECHA:",0,1,'C');
$pdf->ln(30);
$pdf->Cell(30,7,"",0,0,'L');
$pdf->Cell(50,7,"","B",0,'L');
$pdf->Cell(50,7,"",0,0,'L');
$pdf->Cell(50,7,"","B",1,'L');
$pdf->ln(20);
$pdf->Cell(0,5,"","B",1,'L');
$pdf->Cell(0,5,"","B",1,'L');
$pdf->Cell(0,5,"","B",1,'L');
$pdf->Cell(0,5,"","B",1,'L');

$pdf->ln(20);
$pdf->Cell(0,7,"RECIBEN:",0,1,'C');
$pdf->ln(30);
$pdf->Cell(10,7,"",0,0,'L');
$pdf->Cell(40,7,"","B",0,'L');
$pdf->Cell(20,7,"",0,0,'L');
$pdf->Cell(40,7,"","B",0,'L');
$pdf->Cell(20,7,"",0,0,'L');
$pdf->Cell(40,7,"","B",1,'L');


$pdf->Output();
?>
