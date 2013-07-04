<h2>Listado de Comisiones</h2>
<?
echo "<div><a href=\"".base_url()."index.php/$ruta/$controller/$funcion/alta_comision\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nueva Comisión\" ></a></div>";
?>
<div align="center">
	<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<?
if($comisiones==false){
		echo "<h2>Sin Comisiones registradas</h2>"; exit();
}
?>
<table  class="listado" border="0" >
  <tr>
    <th>Id</th>
    <th>Nombre de la Comisión</th>
    <th>Tipo de Comision</th>
      <th>Tienda</th>
    <th>Estatus</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";

foreach($comisiones as $deduccion) {
	if($deduccion->ef_id==0){
		$deduccion->espacio="TODAS";
		}
	$link_del=$link_edit="";
	$link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_comision/".$deduccion->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	$link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_comision/".$deduccion->id).'" title="editar"><img src="'.$img_del.'" height="25px" width="25px"></a>';

	echo "<tr background='$img_row'>\n";
	echo "<td>$deduccion->id</td>\n";
	echo "<td>$deduccion->tag</td>\n";
	echo "<td>$deduccion->tipo</td>\n";
	echo "<td>$deduccion->espacio</td>\n";	
	echo "<td>$deduccion->estatus</td>\n";
	echo "<td>$link_edit &nbsp; $link_del</td>\n";
	echo "</tr>\n";
}
echo "</table></div>";
echo $this->pagination->create_links();
?>