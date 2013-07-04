<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->Cell(0,7,utf8_decode($empresa->razon_social),0,1,'L');
$pdf->Cell(0,7,utf8_decode($title),'B',1,'L');
$pdf->ln(0.5);
$pdf->Cell(0,5,'Impresi�n: '.date("d-m-Y"),0,1,'C');
$pdf->ln(10);
//$pdf->ln(5);
$pdf->SetFont('Times','',9);
//263
$pdf->SetLeftMargin(20);
$pdf->SetAligns(array('L','C','C','C','C','L','L'));
$pdf->SetWidths(array(8,15,20,20,21,100,30));
$pdf->SetFillColor(200,0,0);
$pdf->Row(array('#','Id','Cuenta','SubCuenta','SubSubCuenta','Nombre','Estatus'));
$i=1;
foreach($cuentas->all as $row) {
	if(!($i%2))
		$pdf->SetFillColor(230,0,0);
	else
		$pdf->SetFillColor(255,0,0);

	if($row->estatus_general_id==1)
		$estatus="Habilitada";
	else
		$estatus="Cancelada";
	$pdf->Row(array($i, $row->id, $row->cta,$row->scta,$row->sscta,utf8_decode($row->tag),$estatus));
	$i+=1;
}
$pdf->SetFillColor(200,0,0);
$pdf->Output();
unset($pdf);
?>
