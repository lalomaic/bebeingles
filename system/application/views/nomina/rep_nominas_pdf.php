<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
h2{
  background-color:#ccc;
}
.izquierda {
  text-align:left;
}
.final_seccion{
  border-bottom:1px solid #ccc;
}
.fecha_impresion{
  font-size:5pt;
  text-align:left;
}
.total{
  border-top:2px solid #000;
  font-size:8pt;

}
</style>
<table style="table-layout: fixed; font-size: 7pt; text-align: center; vertical-align: middle; border: .5px solid #ccc;" width="510" id="table_tcp">
  <tr>
	<td width="20%" valign="top" class="izquierda"><h4>SIIAPRE El Bebe Ingles<br/></h4></td>
	<td width="60%" valign="middle"><h3><?=$nomina->tag." - ".$nomina->razon_social?></h3></td>
	<td width="20%" valign="middle">
	  <img src="images/logo_pdf.jpg" height="80"/><br/>
	  <span class="fecha_impresion">Fecha: <?=date("d-m-Y")?><br/>
	  Hora: <?=date("H:i:s")?></span>
	</td>
  </tr>
  <tr>
	<td colspan="3" class="final_seccion">
	  Lista de Nómina del <?=date("d m Y", strtotime($nomina->fecha_inicial))." - ".date("d m Y", strtotime($nomina->fecha_final))?><br/>
	  <?="$nomina->calle # $nomina->numero_exterior INT. $nomina->numero_interior COLONIA $nomina->colonia $nomina->localidad"?>
	</td>
  </tr>
</table>
<?
  foreach($empleado->all as $row){ ?>
<br/><table style="margin-top:20px; table-layout: fixed; font-size: 7pt; text-align: left; vertical-align: middle; border: .5px solid #ccc;" width="510" id="table_tcp">
  <tr>
	<td colspan="2">Empleado: <?="$row->nombre $row->apaterno $row->amaterno"?></td>
	<td>RFC: <?=$row->rfc?></td>
	<td>Fecha Ing. <?=$row->fecha_ingreso?></td>
  </tr><tr>
	<td >Salario Diario: <?=$row->salario?></td>
	<td>Dias Pagados: <? echo ($row->dias_semana - $row->dias_faltas - $row->dias_incapa)?></td>
	<td>Horas Trabajadas: <? echo (($row->dias_semana - $row->dias_faltas- $row->dias_incapa )*7)+$row->horas_extra;?></td>
	<td >CURP: <?=$row->curp?></td>
  </tr>
</table>

<table style="table-layout: fixed; font-size: 7pt; text-align: center; vertical-align: middle; border: .5px solid #ccc;" width="510" id="table_tcp">
  <tr>
	<td >Percepción</td>
	<td >Importe</td>
	<td >Deducción</td>
	<td >Importe</td>
  </tr>
  <?php
	foreach($detalles as $k=>$linea){ 
	  if($k==$row->id){
		$total_percepcion=$linea['sueldo'];
	?>
  <tr>
	<td align="right">
	  Sueldo Base:
	  <?
	  if(isset($linea['horas_extra'])){
		  echo "<br/>Horas Extra:";
		  $total_percepcion+=$linea['horas_extra'];
	  }
	  if($linea['comision']>0){
		  echo "<br/>Comisión:";
		  $total_percepcion+=$linea['comision'];
	  }
		if(isset($linea['prestacion'])) {
		  foreach($linea['prestacion'] as $desc){
			echo "<br/>".$desc['tipo'];
		  $total_percepcion+=$desc['monto'];

		  }
		}
	  
	  echo "<br/><span class='total'>Total Percepcion:</span>";
	  ?>
	</td>
	<td align="right" >
	  <? 
	  echo "$".number_format($linea['sueldo'],2,".",",");
	  if(isset($linea['horas_extra']))
		  echo "<br/> $".number_format($linea['horas_extra'],2,".",",");
	  if($linea['comision']>0)
		  echo "<br/> $".number_format($linea['comision'],2,".",",");
		if(isset($linea['prestacion'])) {
		  foreach($linea['prestacion'] as $desc){
			echo "<br/> $".number_format($desc['monto'],2,".",",");
		  }
		}
	  echo "<br/> $ <span class='total'>".number_format($total_percepcion, 2,".",",")."</span>";
	  ?>
	</td>
	<td align="right">
	  <?
		if(isset($linea['descuento_infonavit']))
		  echo "Préstamo Infonavit: ";
		if(isset($linea['descuento_prog'])) {
		  foreach($linea['descuento_prog'] as $desc){
			echo "<br/>".$desc['tipo'];
		  }
		}
		if(isset($linea['deduccion'])) {
		  foreach($linea['deduccion'] as $desc){
			echo "<br/>".$desc['tipo'];
			$total_deducciones+=$desc['monto'];
		  }
		}

		echo "<br/><span class='total'>Total Deducción:</span>";
	  ?>
	</td>
	<td >
	  <?
		$total_deducciones=0;
		if(isset($linea['descuento_infonavit'])) {
		  echo "$".number_format($linea['descuento_infonavit'],2,".",",");
		  $total_deducciones=$linea['descuento_infonavit'];
		}
		if(isset($linea['descuento_prog'])) {
		  foreach($linea['descuento_prog'] as $desc){
			echo "<br/> $".number_format($desc['monto'],2,".",",");
			$total_deducciones+=$desc['monto'];
		  }
		}
		if(isset($linea['deduccion'])) {
		  foreach($linea['deduccion'] as $desc){
			echo "<br/> $".number_format($desc['monto'],2,".",",");
		  }
		}

		echo "<br/> $ <span class='total'>".number_format($total_deducciones, 2,".",",")."</span>";
	  ?>
	</td>
  </tr>		
  <tr>
	<td align="right" ><span class='total'>Neto a pagar:</span></td>
	<td align="right" style="background-color:#ccc;">
	  <? 
		echo "$ <span class='total' ><strong>".number_format(($total_percepcion-$total_deducciones), 2,".",",")."</strong></span>";
	  ?>
	</td>
	<td></td>
	<td align="right" style="margin-top:30px;border-bottom:1px solid black;"></td>
	</tr>
	<?
	  }
	}
?>
 </table>
<?
}
?>


</body>
</html>