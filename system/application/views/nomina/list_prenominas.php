<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>
<h2>Listado de Prenominas</h2>
<?
echo "<div style=\"width:920px;margin:auto\" align=\"right\">";
echo "<a href=\"".base_url()."index.php/$ruta/$controller/$funcion/generar_prenomina\"><img src=\"".base_url()."images/boleta.png\" width=\"50px\" title=\"Nueva Comisión\" ></a></div>";
?>
<div align="center">
	<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<?
if($prenominas==false){
		echo "<h2>Sin Prenominas registradas</h2>"; exit();
}
?>
<table  class="listado" border="0" width="500">
  <tr>
    <th>Id</th>
    <th>Fecha Inicial</th>
    <th>Fecha Final</th>
    <th>Sucursal</th>
    <!--<th>Estatus</th>
    --><th>Capturista</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_del=base_url()."images/trash.png";
$img_nom=base_url()."images/recargar.png";

foreach($prenominas as $row) {
	$link_del=$link_edit=""; $link_nom="";
	$estatus="Aplicada";
	if($row->aplicada==0){
	  $link_nom='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/generar_nomina/".$row->id).'" title="Generar Nomina"><img src="'.$img_nom.'" height="25px" width="25px"></a>';
	  $link_edit='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_prenomina/".$row->id).'" title="editar"><img src="'.$img_edit.'" height="25px" width="25px"></a>';
	  $link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_prenomina/".$row->id).'" title="editar"><img src="'.$img_del.'" height="25px" width="25px"></a>';
	  $estatus="Capturada";
	}
	
	echo "<tr background='$img_row'>\n";
	echo "<td>$row->id</td>\n";
	echo "<td>$row->fecha_inicial</td>\n";
	echo "<td>$row->fecha_final</td>\n";
	echo "<td>$row->espacio</td>\n";
	//echo "<td>$row->aplicada</td>\n";
	echo "<td>$row->usuario</td>\n";
	echo "<td>$link_edit &nbsp; $link_del &nbsp; $link_nom</td>\n";
	echo "</tr>\n";
}
echo "</table></div>";
echo $this->pagination->create_links();
?>