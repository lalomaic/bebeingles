<?php
$pdf=new Fpdf_multicell($orientation="P");
$pdf->AddPage();
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,5,"$title",0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,'Resultados: '.count($usuarios),0,1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);

//Table with 20 rows and 4 columns
$pdf->SetWidths(array(7,40,35,19,40,62));
$pdf->Row(array("Id","Nombre","Empresa","Área","Puesto","Correo Electr�nico"));
if(count($usuarios)!=false){
	foreach($usuarios->all as $row) {
		$pdf->Row(array($row->id,$row->nombre,$row->empresa,$row->espacio_fisico,$row->username,$row->email));
	}
} else {
	echo "Sin resultados";
}
$pdf->Output();
?>