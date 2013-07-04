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
	$pdf->SetAligns(array('L','L','R','R','C','R',"R"));
	$pdf->SetWidths(array(10,75,20,20,20,20,20,20));
	$pdf->Row(array("#","Empleado","Cantidad Productos", "Ventas","Porcentaje","Comision","Flete"));
	$t_importe=0; $t_pares=0; $pre_empleado=0;$colect=array();  $comision_par=0;$nota=0;$comision_t=0;
	foreach($ventas_empleados->all as $row) {
		if($pre_empleado!=$row->empleado_id){
			$g+=1;
			$colect[$g]["pares"]=0;
			$colect[$g]["importe_neto"]=0;
                        $colect[$g]['abonos']=0;
                        $colect[$g]["cri"]=0;
                        //$colect[$g]["abonos"]+=$row->monto_abono; 
                        

		}
                $colect[$g]["nombre"]=$row->empleado;
               $colect[$g]["empleado_id"]=$row->empleado_id;
          
		if($row->cobro_vales==0){
                      // $colect[$g]["abonos"]=$row->monto_abono;
			$colect[$g]["cri"]=$row->criterio1;
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
		$pre_empleado=$row->empleado_id;
                $nota=$row->abonos_id;
	}
	$y=1;
	
	foreach($colect as $row1) {
            
$importe=number_format($row1['importe_neto'],2,".","");
$monto=0;$bono_vales=0;
foreach ($fletes as $f){
    if($row1['empleado_id']==$f->empleado_id){
         $flete+=$f->importe_total;
         $importe=$row1['importe_neto']-$f->importe_total; 
    }
}

foreach ($abonos_vales as $xx){
    if($row1['empleado_id']==$xx->empleado_id){
        $bono_vales+=$xx->monto;
         $importe=$row1['importe_neto']+$xx->monto;
       

    }
}
  foreach ($abonos as $x){
            if($row1['empleado_id']==$x->empleado_id){
           $monto+=$x->monto; 
            $importe=$row1['importe_neto']+$x->monto;
                
            }}

		if($row1['cri'] != false){
		 $matriz=explode("&", $row1['cri']);
	  $comision_par=0;
	  foreach($matriz as $lin){
		$det=explode("-", $lin);
		
//		  echo "----$pares_vendidos  & {$det[0]} {$det[1]} {$det[2]} <br/>";
		if($importe>=$det[0] and $importe<=$det[1]){
		  $comision_par=$det[2];
		  break;
		}else {
			$comision_par=0;
		}
	  }
	  $comi_total=$importe*$comision_par/100;
}        
$importe_t=number_format($row1['importe_neto']+$monto+$bono_vales-$flete,2,".",",");
		$pdf->Row(array($y,$row1['nombre'],number_format($row1['pares'], 0, ".",","),$importe_t,$comision_par."%", "$". number_format($comi_total,2,".",","),"$".$flete));
		$t_importe+=$row1['importe_neto']+$monto+$bono_vales;
		$t_pares+=$row1['pares'];
                $comision_t+=$comi_total;
		$cri=$row1['cri'];
                
	}
	$pdf->SetFont('Times','B',8);
	$pdf->Row(array('','TOTAL',$t_pares,number_format($t_importe, 2, ".",","),"",number_format($comision_t, 2, ".",",")));
		
	if($comision_tienda!= false){
		foreach($comision_tienda->all as $ctienda){
		 $matriz=explode("&", $ctienda->criterio1);
	  $comision_tienda=0;
	  foreach($matriz as $lin){
		$det=explode("-", $lin);
		if($t_importe>=$det[0] and $t_importe<=$det[1]){
		  $comision_tienda=$det[2];
		  break;
		}else {
			$comision_tienda=0;
		}
	  }
	 $ctienda_total=$t_importe*$comision_tienda/100;

	
	
$pdf->Cell(0,5,"Porcentaje Por Tienda:".$comision_tienda." %",0,1,'L');	
$pdf->Cell(0,5,"Comision : $".$ctienda_total,0,1,'L');	
}
}else {
$pdf->Cell(0,5,"No Existen Comisiones de Rango Registradas para la tienda",0,1,'L');
}
} else
	$pdf->Cell(0,5,"Sin registros",0,1,'L');

$pdf->Output();
?>