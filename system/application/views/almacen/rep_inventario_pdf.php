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

//$pdf=new Fpdf_multicell($orientation="P");
$pdf= new Optimizar_memoria();
$name='inventario_sin_saldo_'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
//$pdf=new PDF($orientation='L');
$pdf->AddPage();
$pdf->SetTopMargin(20);
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,7,utf8_decode($empresa),'B',1,'L');
//$pdf->Cell(50,7,'TIPO SUCURSAL: ',1,0,'L');
//$pdf->Cell(0,7,utf8_decode($tipo),'B',1,'L');
$pdf->Cell(50,7,'TIENDA: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($tienda),'B',1,'L');
$pdf->Cell(50,7,'FAMILIA: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($familia),'B',1,'L');
$pdf->Cell(50,7,'SUBFAMILIAS.: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($subfamilia),'B',1,'L');
$pdf->Cell(50,7,'Fecha Corte: ',1,0,'L');
$pdf->Cell(0,7,"$fecha",'B',1,'L');
$pdf->SetFont('Times','',8);
$pdf->ln(10);

//Table with  columns
$pdf->SetWidths(array(10,90,20,18,20));
$pdf->SetAligns(array('L','L','R', 'R','R'));
if(count($inventario)>0){
	$saldo=0;
	$saldo_t=0;
	$total_pares=0; $total_entradas=0; $total_salidas=0;
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array("ID","DESCRIPCI�N","EXISTENCIA", "COSTO", "SALDO"));
	$pdf->SetFont('Times','',8);
	foreach($inventario as $row) {
		if($row['entradas']=='')
			$row['entradas']=0;
		if($row['salidas']=='')
			$row['salidas']=0;
		if($row['saldo']<0)
			$row['saldo']=0;
		$saldo=$row['saldo']*$row['existencias'];
		$saldo_t+=$saldo;
		if($row['entradas']!=0 or $row['salidas']!=0)
			$pdf->Row(array($row['id'], utf8_decode($row['descripcion'])." # ".$row['talla'],$row['existencias'],number_format($row['saldo'], 2, ".",","), number_format($saldo, 2, ".",",")));

		$total_pares+=$row['existencias'];
		$total_entradas+=$row['entradas'];
		$total_salidas+=$row['salidas'];
		unset($row);
	}

	$pdf->Row(array(number_format($total_pares,2,".",","), number_format($saldo_t, 2, ".",",")));
}
$pdf->Cell(0,7,"*Nota - Los valores negativos representa que no existen entradas por compra para dicho concepto en el sistema para dichos productos, y el precio tomado es el Precio No. 1",1,1,'L');

 $pdf->AddPage();
 $pdf->Cell(0,7,'RESPONSIVA DE INVENTARIO',0,1,'C');
$pdf->SetFont('Times','',10);
$pdf->Cell(20,7,'TIENDA: ',1,0,'L');
$pdf->Cell(60,7,utf8_decode($tienda),1,0,'L');

$pdf->Cell(30,7,'RAZ�N SOCIAL: ',1,0,'L');
$pdf->Cell(0,7,utf8_decode($razon_social),1,1,'L');
if($tienda=="TODAS"){
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
}  else {
$pdf->MultiCell(0,4,utf8_decode("DOMICILIO:\n".$calle. " # ".$num_exterior ." Col. ". $colonia ." C.P. ".$codigo. ", $localidad, $estado "),1,'L');    

$pdf->Cell(30,7,'R.F.C. : ',1,0,'L');
$pdf->Cell(50,7,"$rfc",1,0,'L');
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
}
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>
