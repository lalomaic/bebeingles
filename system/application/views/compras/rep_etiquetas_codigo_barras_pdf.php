<?php
$pdf = new optimizar_memoria('P','mm', array(32,22));
$name="etiquetas_".$pr_factura.".pdf";
$encabezado=utf8_decode("EL BEBÉ INGLÉS ®");
$pdf->Open($name);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false);
for($x=1;$x<=count($detalles);$x++){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',5.5);
	$pdf->Cell(0,1,"$encabezado", 20, 15, 'C', 1);
	$pdf->ln();
    $pdf->Multicell(0,1.5,utf8_decode($detalles[$x]['descripcion']),0,'C',1);
	$pdf->Image($detalles[$x]['ruta'],0,9,31,8);
    $pdf->SetXY(4,14);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,10,"PRECIO:$".  number_format($detalles[$x]['precio'],2,'.',','), 0,12, 'C');
}
$pdf->Output();
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>
