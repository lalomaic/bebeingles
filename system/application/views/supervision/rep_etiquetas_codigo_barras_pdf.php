<?php
$pdf = new optimizar_memoria('P','mm', array(71,25));
$name="etiquetas_".$lote.".pdf";
$pdf->Open($name);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false);
for($x=1;$x<=count($detalles);$x++){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(0,2,"Zapater�as Pavel ($espacio)", 0, 1, 'C', 0);
	$pdf->ln(1);
	//	$pdf->Cell(0,2,"-$espacio-", 0, 1, 'C', 0);
	//$pdf->Cell(0,2,substr($fecha[2],2,4)."MX".$fecha[1]."SOL".$fecha[0], 0, 1, 'R', 0);
	$pdf->Image($detalles[$x]['ruta'],0,5,0);
	//$x=$pdf->GetX();
	//$y=$pdf->GetY()+11;
	$pdf->SetXY(1,18);
	$pdf->MultiCell(0,2,utf8_decode($detalles[$x]['descripcion']), 0, 'C',0);
}
$pdf->Output();
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>