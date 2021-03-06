<?php
$h=5;
$pdf=new Fpdf_multicell();
$pdf->AddPage('L');
$pdf->ln($h);
$pdf->Cell(0,$h,utf8_decode($title),0,1,'C');
$pdf->ln($h);
$pdf->Image(base_url()."tmp/ventas_familias.jpeg",10,25,250);
$pdf->AddPage('L');
$pdf->Cell(0,$h,"Tabla de Comportamiento de Ventas por Semana",0,1,'C');
//obtener el numero de semanas para calcular el ancho de las columnas
$n_semanas=count($semanas);
//print_r($semanas);
$wventas=165;
$n_columnas=10;
if(($n_semanas)<=$n_columnas){
	$caso=1;
	$n_columnas=$n_semanas;
	$wv=($wventas / ($n_semanas+1));
	// 	$wv=140/$n_columnas;
} else {
	$caso=2;
	$wv= ($wventas / $n_columnas);
}

$columna_header[0]="Producto";
$columna_header[1]="Familia";
$columna_header[2]="Subfamilia";
$columna_arr[0]=60;
$columna_arr[1]=20;
$columna_arr[2]=20;
$columnas_alin[0]='L';
$columnas_alin[1]='L';
$columnas_alin[2]='L';

for($r=3;$r<$n_semanas+3;$r++){
	$columna_header[$r]="Semana ".($r-2)." \n".$semanas[($r-3)]['fecha1']."\n".$semanas[($r-3)]['fecha2'];
	$columna_arr[$r]=round($wv,2);
	$columnas_alin[$r]='R';
}
$columna_header[$r]="Promedio \n Semanal";
$columna_arr[$r]=$wv;
$columnas_alin[$r]='R';

//echo $columnas_alin_str;
$h=4;
$costo_total=0;
$cantidad_total=0;
foreach($datos as $row){
	//Bucle para cada Sucursal
	$pdf->ln($h);
	$header=1;
	foreach($row as $srow){
		//Bucle para cada concepto
		if($header==1){
			$pdf->SetFont('Times','B',10);
			$pdf->SetWidths(array(100,165));
			$pdf->SetAligns(array('L','C','C','C'));
			$pdf->Row(array(utf8_decode($srow['espacio']), 'Ventas'));
			$pdf->SetFont('Times','B',6.5);
			$pdf->SetWidths($columna_arr);
			$pdf->SetAligns($columnas_alin);
			$pdf->Row($columna_header);
			$header=0;
		}
		//Imprimir los productos
		$pdf->SetFont('Times','',7);
		$total_cantidad=0;
		$total_costo=0;
		$array1[0]=utf8_decode($srow['descripcion']);
		$array1[1]=utf8_decode($srow['familia']);
		$array1[2]=utf8_decode($srow['subfamilia']);
		for($c=3;$c<$n_columnas+3;$c++){
			if(isset($srow['semana'.($c-2).'_cantidad'])==false){
				$srow['semana'.($c-2).'_cantidad']=0;
				$srow['semana'.($c-2).'_costo_total']=0;
			}
			$array1[$c]=number_format($srow['semana'.($c-2).'_cantidad'],2,".",",")."\n$ ".number_format($srow['semana'.($c-2).'_costo_total'],2, ".",",");
			$total_cantidad+=$srow['semana'.($c-2).'_cantidad'];
			$total_costo+=$srow['semana'.($c-2).'_costo_total'];
		}
		$prom_cantidad=$total_cantidad/$n_semanas;
		$cantidad_total+=$total_cantidad;
		$prom_costo=$total_costo/$n_semanas;
		$costo_total+=$total_costo;
		$array1[$c]=number_format($prom_cantidad,2,".",",")."\n$".number_format($prom_costo,2,".",",");
		$pdf->Row($array1);
	}
	//Pie de Cada tienda
	$pdf->SetFont('Times','B',12);
	$pdf->Cell(265,10,"Total Ventas: $ ".number_format($costo_total,2,".",","), 1,1,'R');
	$pdf->ln($h);
}
$pdf->Output();
unset($pdf);
?>