<?php
$pdf = new optimizar_memoria('P','mm', array(71,25));
$name="etiquetas_anteriores.pdf";
$pdf->Open($name);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false);
for($x=0;$x<count($detalles);$x++){
	for($y=0;$y<$detalles[$x]['cantidad'];$y++){
		$pdf->AddPage();
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(0,2,"Zapaterías Pavel", 0, 1, 'C', 0);
		$pdf->ln(1);
		$pdf->Image($detalles[$x]['ruta'],10,5,0);
		//$x=$pdf->GetX();
		//$y=$pdf->GetY()+11;
		$pdf->SetXY(1,18);
		$pdf->MultiCell(0,2,utf8_decode($detalles[$x]['descripcion']), 0, 'C',0);
	}
}
$pdf->Output();
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>