<?php
$precio=number_format($producto->precio,2,'.',',');
$tag=utf8_decode($producto->tag);
if($tam==3){
 $encabezado=utf8_decode("EL BEBÉ INGLÉS ®");
  $pdf = new optimizar_memoria('P','mm', array(32,22));
$name="etiquetas.pdf";
$pdf->Open($name);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false);   
for($x=1;$x<=$pages;$x++){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',5.5);
	$pdf->Cell(0,1,"$encabezado", 20, 15, 'C', 1);
   	$pdf->ln();
    $pdf->Multicell(0,1.5,"$tag #$producto->numeracion",0,'C',1);
    $pdf->ln();
	$pdf->Image(base_url()."tmp/cb_".$producto->id.".jpeg",0,9,31,8);
	$pdf->SetXY(4,14);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,10,"PRECIO:$$precio", 0,12, 'C');
        $pdf->Output();
}
} if($tam==5){ 
   $encabezado=utf8_decode("EL BEBÉ INGLÉS ®");
  $pdf = new optimizar_memoria('P','mm', array(50,25));
$name="etiquetas.pdf";
$pdf->Open($name);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false);   
for($x=1;$x<=$pages;$x++){
	$pdf->AddPage();
	$pdf->SetFont('Arial','',5);
	$pdf->Cell(0,1,"$encabezado", 20, 15, 'C', 1);
   	$pdf->ln();
        $pdf->Multicell(0,1.5,"$tag #$producto->numeracion",0,'C',1);
        $pdf->Image(base_url()."tmp/cb_".$producto->id.".jpeg",8,8,36,10);
	$pdf->SetXY(4,15);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(0,10,"PRECIO:$$precio", 0,12, 'R');
}
    

$pdf->Output();
}
?>
<script>location.href='<?=base_url()?>tmp/<?=$name?>'</script>
<? unset($pdf);?>
