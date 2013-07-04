<?php
$pdf=new Fpdf_multicell();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,5,utf8_decode($title),'B',1,'L');
$pdf->ln(1);
$pdf->Cell(0,5,'Impresión :'.date("d-m-Y").' Resultados: '.count($pedidos->all),'B',1,'L');
//$pdf->ln(5);
$pdf->SetFont('Times','',8);
//201

$pdf->SetAligns(array('L','L','L','C','C','C','R','C','L',));
$pdf->SetWidths(array(7,40,40,16,16,16,18,17,13,20));
$pdf->Row(array("Id", "Empresa", "Proveedor", "Fecha de alta", "Fecha de entrega","Fecha de pago", "Monto total", "Forma de pago", "Estatus", "Capturista"));

foreach($pedidos->all as $row) {
	$pdf->Row(array($row->id, utf8_decode($row->empresa), utf8_decode($row->proveedor), $row->fecha_alta, substr($row->fecha_entrega,0,10), $row->fecha_pago,"$ ".number_format($row->monto_total, 2, ".",","), $row->forma_pago, $row->estatus, utf8_decode($row->usuario)));
}
$pdf->Output();
?>