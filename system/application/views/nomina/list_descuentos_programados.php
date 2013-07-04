<h2><?=$title?></h2>
<div align="center">
	<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<?
if($descuentos==false){
		echo "<h2>Sin Descuentos Registrados</h2>"; exit();
}
?>
<table  class="listado" width="900">
  <tr>
    <th>Id</th>
    <th>Empleado</th>
    <th>Fecha<br/>Inicio</th>
    <th>Concepto</th>
    <th>Adeudo<br/>Total</th>
    <th>Descuento<br/>Semanal</th>
    <th># Pagos</th>
    <th>Estatus</th>
    <th>Capturista</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";

foreach($descuentos as $row) {
	$link_del=$link_edit="";
	$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_descuento/".$row->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	$link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_descuento/".$row->id).'" title="editar"><img src="'.$img_del.'" height="25px" width="25px"></a>';
	
	echo "<tr background='$img_row' style='height:30px;' align='middle'>\n";
	echo "<td>$row->id</td>\n";
	echo "<td>$row->nombre $row->apaterno $row->amaterno</td>\n";
	echo "<td>$row->fecha_inicio</td>\n";
	echo "<td>$row->tipo</td>\n";
	echo "<td align='right'>".number_format($row->deuda_total,2,".",",")."</td>\n";
	echo "<td align='right'>".number_format($row->monto_descuento_semanal,2,".",",")."</td>\n";
	echo "<td align='center'>$row->no_pagos</td>\n";
	echo "<td>$row->estatus</td>\n";
	echo "<td>$row->usuario</td>\n";
	echo "<td> $link_edit &nbsp; $link_del</td>\n";
	echo "</tr>\n";
}
echo "</table></div>";
echo $this->pagination->create_links();
?>