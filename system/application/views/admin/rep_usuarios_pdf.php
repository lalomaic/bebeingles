<?php
$pdf=new Fpdf_multicell($orientation="P");
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,utf8_decode($title),0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($usuarios),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','B',8);
$pdf->SetWidths(array(7,60,25,35,35,35));
$pdf->Row(array("Id","Nombre","Empresa","�rea","Puesto","Correo Electr�nico"));
if(count($usuarios)!=false){
	$pdf->SetFont('Times','',7);
	$i=0;
	foreach($usuarios->all as $row) {
		$empresa=substr($row->empresa, 0, 10)."...";
		if(!($i%2))
			$pdf->SetFillColor(200,0,0);
		else
			$pdf->SetFillColor(255,0,0);

		$pdf->Row(array($row->id,utf8_decode($row->nombre),utf8_decode($empresa),utf8_decode($row->espacio_fisico),utf8_decode($row->puesto),$row->email));
		$i+=1;

	}
} else {
	echo "Sin resultados";
}
$pdf->Output();
?>