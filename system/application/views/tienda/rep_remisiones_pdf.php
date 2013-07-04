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

$pdf=new Fpdf_multicell($orientation="P");
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,"$title",0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,"Impresi�n: ".date("d-m-Y")." Resultados: ".count($notas->all),0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',8);

//Table with 20 rows and 4 columns
$pdf->SetAligns(array('L','L','C','C','R','C','L'));
$pdf->SetWidths(array(15,50,16,16,30,20,50));
$pdf->Row(array("No. Remisi�n","Ubicaci�n","Fecha","Hora","Importe","Estatus","Usuario"));
if(count($notas)!=false){
	$gtotal=0;
	foreach($notas->all as $row) {
		$pdf->Row(array($row->numero_remision,$row->espacio_fisico,$row->fecha,$row->hora,number_format($row->importe_total, 2, ".",","),$row->estatus, $row->usuario));
		if($row->estatus=="Habilitado"){
			$gtotal+=$row->importe_total;
		}
	}
	$pdf->Row(array('TOTAL','', '', '',"$ ".number_format($gtotal, 2, ".", ","), ''));

} else {
	echo "Sin resultados";
}
$pdf->Output();
?>