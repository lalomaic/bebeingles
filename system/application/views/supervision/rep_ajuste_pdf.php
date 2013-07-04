<?php
function esPar($num){
	return !($num%2);
}
$pdf=new Fpdf_multicell();
$name='reporte_arqueo_'. date("d_m_Y") .'.pdf';
$pdf->Open($name);
$pdf->SetTopMargin(20);

if($arqueo!=false){
	foreach($arqueo->all as $row) {
		$pdf->AddPage("L");
		$pdf->SetFont('Times','B',10);
		$pdf->Cell(0,5,utf8_decode($title).": Faltantes",'B',1,'L');
		$pdf->ln(3);
		$pdf->SetFont('Times','',8);
		$h = 5;// altura
		$pdf->ln($h);
		$x1 = $pdf->GetX();
		$x2 = $x1+25;
		$y = $pdf->GetY();
		$arqueo1= array(7,16,35,35,20,20,20,16,30);
		$arqueo_detalle1 = array(8, 10,120,14,17,14,40,18,18,18);
		$i = 1;
		$borde = 1;
		$pdf->SetXY($x1,$y);
		$pdf->SetWidths($arqueo1);
		$pdf->Cell(15,$h,"Id Arqueo:");
		$pdf->Cell(23,$h,$row->id,$borde);
		$pdf->Cell(4);
		//$pdf->SetTextColor(100);
		$pdf->Cell(12,$h,"FECHA:");
		$pdf->Cell(17,$h,$row->fecha_final,$borde);
		$pdf->Cell(4);
		$pdf->Cell(12,$h,"HORA:");
		$pdf->Cell(17,$h,$row->hora_final,$borde);
		$pdf->Cell(4);
		$pdf->Cell(16,$h,"ESTATUS:");
		$pdf->Cell(0,$h,$row->estatus,$borde);
		$pdf->ln($h+1);
		$pdf->Cell(41,$h,"UBICACION - CAPTURISTA:");
		$pdf->Cell(0,$h,$row->espacio." - ". utf8_decode($row->usuario),$borde);
		$pdf->ln($h+1);
		$pdf->ln($h+3);
		// detalles de la factura
		$pdf->SetWidths($arqueo_detalle1);
		$pdf->SetAligns(array("L","C","L","R","R","R","L","R", "R", "R"));
		$pdf->SetFont('Times','B',8);
		$pdf->SetFillColor(255,0,0);
		$pdf->SetDrawColor(1);
		$pdf->Row(array("#", "Id", "Producto", "FISICO", "SISTEMA", "DIF", utf8_decode("ACCIÓN"), "P. UNIT", "TOTAL"));
		$pdf->SetFont('Times','',7.5);
        if(count($datos)>0){
                $r=0;
                $total_salidas=0;
                $total_diferencia=0;
                $total_fisico = 0;
                $total_sistema = 0;
                foreach($datos as $lista){

                    if ($lista['accion_id']==5 or $lista['accion_id']==4){
                        if(esPar($r))
                            $pdf->SetFillColor(220,0,0);
                        else
                            $pdf->SetFillColor(255,0,0);

                        $pdf->Row(array($r+1,
                            $lista['id'],
                            $lista['producto']." # ".$lista['numero']/10, 
                            number_format($lista['cantidad_real'], 2, ".", ","),
                            number_format($lista['cantidad_sistema'], 2, ".", ","),
                            number_format($lista['diferencia'], 2, ".", ","), 
                            $lista['accion'],
                            number_format($lista['precio_unitario'], 2, ".", ","), 
                            number_format($lista['precio_unitario']*$lista['diferencia'], 2, ".", ",")));
                        $r+=1;
                        $total_diferencia=$total_diferencia+$lista['diferencia'];
                        $total_salidas+=$lista['precio_unitario']*$lista['diferencia'];
                        $total_sistema += $lista['cantidad_sistema'];
                        $total_fisico += $lista['cantidad_real'];
                    }
                        unset($lista);
                }
                $pdf->SetFillColor(220,0,0);
                $pdf->Row(array('',
                                '',
                                '',
                                number_format($total_fisico,2,".",","),
                                number_format($total_sistema,2,".",","),
                                number_format($total_diferencia,2,".",","),
                                '',
                                'Total', number_format($total_salidas, 2,".",",")));

                $r=0;
                $pdf->AddPage('L');
                $pdf->SetFont('Times','B',10);
                $pdf->Cell(0,5,utf8_decode($title)." Sobrantes",'B',1,'L');
                $pdf->ln(3);
                $pdf->SetFont('Times','',8);
                $h = 5;// altura
                $pdf->ln($h);
                $x1 = $pdf->GetX();
                $x2 = $x1+25;
                $y = $pdf->GetY();
                $arqueo1= array(7,16,35,35,20,20,20,16,30);
                $arqueo_detalle1 = array(8, 10,80,14,17,14,40,12);
                $i = 1;
                $borde = 1;
                $pdf->SetXY($x1,$y);
                $pdf->Cell(15,$h,"Id Arqueo:");
                $pdf->Cell(23,$h,$row->id,$borde);
                $pdf->Cell(4);
                //$pdf->SetTextColor(100);
                $pdf->Cell(12,$h,"FECHA:");
                $pdf->Cell(17,$h,$row->fecha_final,$borde);
                $pdf->Cell(4);
                $pdf->Cell(12,$h,"HORA:");
                $pdf->Cell(17,$h,$row->hora_final,$borde);
                $pdf->Cell(4);
                $pdf->Cell(16,$h,"ESTATUS:");
                $pdf->Cell(0,$h,$row->estatus,$borde);
                $pdf->ln($h+1);
                $pdf->Cell(41,$h,"UBICACION - CAPTURISTA:");
                $pdf->Cell(0,$h,"$row->espacio - $row->usuario",$borde);
                $pdf->ln($h+1);
                $pdf->ln($h+3);
                $pdf->SetFont('Times','B',8);
                $pdf->SetFillColor(255,0,0);
                $pdf->SetDrawColor(1);
                $pdf->Row(array("#","Id","Producto","FISICO","SISTEMA","DIF", utf8_decode("ACCIÓN"), "P. UNIT", "TOTAL"));
                $pdf->SetFont('Times','',7.5);
                $total_salidas=0;
                $total_diferencia=0;
                $total_fisico = 0;
                $total_sistema = 0;
                foreach($datos as $lista){

                    if ($lista['accion_id'] == 2 or $lista['accion_id'] == 3){
                        if(esPar($r))
                            $pdf->SetFillColor(220,0,0);
                        else
                            $pdf->SetFillColor(255,0,0);

                        $pdf->Row(array($r+1,
                            $lista['id'],
                            $lista['producto']." # ".$lista['numero']/10, 
                            number_format($lista['cantidad_real'], 2, ".", ","),
                            number_format($lista['cantidad_sistema'], 2, ".", ","),
                            number_format($lista['diferencia'], 2, ".", ","), 
                            $lista['accion'],
                            number_format($lista['precio_unitario'], 2, ".", ","), 
                            number_format($lista['precio_unitario']*$lista['diferencia'], 2, ".", ",")));
                        $r+=1;                        
                        $total_diferencia=$total_diferencia+$lista['diferencia'];
                        $total_salidas+=$lista['precio_unitario']*$lista['diferencia'];
                        $total_sistema += $lista['cantidad_sistema'];
                        $total_fisico += $lista['cantidad_real'];
                    }
                        unset($lista);
                }
                $pdf->SetFillColor(220,0,0);
                $pdf->Row(array('',
                                '',
                                '',
                                number_format($total_fisico,2,".",","),
                                number_format($total_sistema,2,".",","),
                                number_format($total_diferencia,2,".",","),
                                '',
                                'Total', number_format($total_salidas, 2,".",",")));
                }

       }
               
        }
$pdf->Output();
unset($pdf);
exit();
?>
