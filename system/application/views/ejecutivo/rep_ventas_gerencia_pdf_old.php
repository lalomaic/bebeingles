<?php
$pdf=new Fpdf_multicell($orientation="P");
$pdf->AddPage();
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,5,"REPORTE DE VENTAS POR EMPLEADO",0,1,'L');
$pdf->Cell(0,5,"SUCURSAL: $espacio_nombre",0,1,'L');
$pdf->SetFont('Times','',12);
$pdf->Cell(0,5,"Periodo: $fecha1 - $fecha2",0,1,'L');
$pdf->Cell(0,5,"Impresion: ".date("d-m-Y"),0,1,'L');
$pdf->ln(5);
$pdf->SetFont('Times','',8);

if($ventas_empleados!=false){
	$g=1;
        
      
	//Table with 20 rows and 4 columns
	$pdf->SetAligns(array('L','L','R','R','R','R',"R",'R'));
	$pdf->SetWidths(array(10,40,25,25,25,25,25,25,25));
	$pdf->Row(array("#","Tienda","Cantidad Productos", "Ventas","APartados","Gastos","Flete","Utilidad"));
	$t_importe=0; $t_pares=0; $espacio=0;$colect=array();$nota_re=0;$comision_t=0;$flete_t=0; $t_apartado=0;$t_gasto=0; $total_gasto=0;$utilidad=0;
	foreach($ventas_empleados->all as $row) {
		 if($espacio!=$row->espacio_nota){
			$g+=1;
			$colect[$g]["pares"]=0;
			$colect[$g]["importe_neto"]=0;
                        $colect[$g]['abonos']=0;
                        $colect[$g]["cri"]=0;
                        $colect[$g]["espacio"]=0; 
                        $colect[$g]["espacio_tag"]=0; 
                        $colect[$g]["espacio_tag"]=$row->tag;
                         
                       

		}
                 
                $colect[$g]["espacio"]=$row->espacio_nota;    
              
                          
                //$colect[$g]["nombre"]=$row->empleado;
               // $colect[$g]["empleado_id"]=$row->empleado_id;
              
		if($row->cobro_vales==0){
                      // $colect[$g]["abonos"]=$row->monto_abono;
			//$colect[$g]["cri"]=$row->criterio1;
			$colect[$g]["pares"]+=$row->cantidad;
			$colect[$g]["importe_neto"]+=$row->importe_total;
		}
		else if($row->cobro_vales>0){
			//$colect[$g]["abonos"]=$row->monto_abono;
                        $cantidad=$row->cantidad;
			$vale=ceil($row->cobro_vales);
			$importe=$row->importe_total;
			$cantidad_calc=round($cantidad-round(($cantidad*$vale/$importe),0),0);
			$colect[$g]['pares']+=$cantidad_calc;
			$colect[$g]["importe_neto"]+=$importe-$row->cobro_vales;
		}
            
                                
                //$colect[$g]["abonos"]=$row->monto_abono;
		//$pre_empleado=$row->empleado_id;
                $espacio=$row->espacio_nota;
                $nota_re=$row->rem;
	}
	$y=1;
	
	foreach($colect as $row1) {
            
$importe=number_format($row1['importe_neto'],2,".","");
 $flete=0;$t_flete=0;
foreach ($fletes as $f){
    if($row1['espacio']==$f->espacio_no){
         $flete+=$f->importe_total;
         $importe=$row1['importe_neto']-$f->importe_total; 
    }else{
        $flete+=0;
    }
}
$bono_vales=0;
foreach ($abonos_vales as $xx){
    if($row1['espacio']==$xx->espacio_vales){
        $bono_vales+=$xx->monto;
         $importe=$row1['importe_neto']+$xx->monto;
       }else{
          $bono_vales+=0; 
       }
}
$monto=0;
  foreach ($abonos as $x){
            if($row1['espacio']==$x->espacio_abonos){
           $monto+=$x->monto; 
            $importe=$row1['importe_neto']+$x->monto;   
            }else{
               $monto+=0;   
            }
            
            }
  $gasto_espacio=0;  
foreach ($gastos as $ga){
    if($row1['espacio']==$ga->espacio_gasto){
        $gasto_espacio+=$ga->monto;
        break;
    }else{
        $gasto_espacio+=0;        
            }
}    
$t_gasto+=$gasto_espacio;
$t_apartado=$monto+$bono_vales;           
$importe_t=number_format($row1['importe_neto']+$monto+$bono_vales-$flete,2,".",",");
$flete_t+=$flete;

$pdf->Row(array($y,$row1['espacio_tag'],number_format($row1['pares'], 0, ".",","),$importe_t,number_format($monto+$bono_vales,2,".",","), number_format($gasto_espacio,2,".",",") ,"$".number_format($flete,2,".",","),  number_format($row1['importe_neto']+$monto+$bono_vales-$flete-$gasto_espacio,2,".",",")));
		//$t_importe+=$row1['importe_neto'];
                $t_importe+=$row1['importe_neto']+$monto+$bono_vales-$flete;
                $utilidad+=$row1['importe_neto']+$monto+$bono_vales-$flete-$gasto_espacio;
		$t_pares+=$row1['pares'];
   $comision_t+=$t_apartado;
   $t_flete+=$flete_t;
   $total_gasto=$t_gasto;
		
                
	}
	$pdf->SetFont('Times','B',8);
     $pdf->Row(array('','TOTAL',  number_format($t_pares,2,".",","),number_format($t_importe, 2, ".",","),number_format($comision_t, 2, ".",","),number_format($total_gasto, 2, ".",","),number_format($t_flete, 2, ".",","),number_format($utilidad, 2, ".",",")));
        
} else
	$pdf->Cell(0,5,"Sin registros",0,1,'L');

$pdf->Output();
?>