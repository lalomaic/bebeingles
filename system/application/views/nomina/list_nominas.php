<h2><?php echo $title;?></h2>
<?php
if($nominas==false){
	echo "<h2>Sin registros</h2>";
	exit();
}
?>
<div align="center">
<b>Total de registros: <?php echo $total_registros;?></b>
</div>
<table  width="80%" class="listado" border="0">
  <tr>
    <th>Id</th>
    <th>Prenomina Id</th>
    <th>Fecha Inicio</th>
    <th>Fecha Final</th>
    <th>Sucursal</th>
    <th>Dias Lab.</th>
    <th>Estatus</th>
    <th>Capturista</th>
    <th>Fecha Captura</th>
    <th>Edición</th>
 </tr>
<?php
$img_row=base_url()."images/table_row.png";
$img_edit=base_url()."images/edit.png";
$img_pdf=base_url()."images/adobereader.png";
$img_del=base_url()."images/trash.png";

foreach($nominas as $nomina)
	{
	$link_del=$link_edit="";
	if(substr(decbin($permisos), 0, 1)==1)
		$link_pdf='<a href="'.site_url("/$ruta/nomina_reportes/rep_nomina_pdf/".$nomina->id).'" title="Imprimir Nómina"><img src="'.$img_pdf.'" height="25px" width="25px"></a>';
	if(substr(decbin($permisos), 1, 1)==1)
		$link_del='<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_nomina/".$nomina->id).'" title="Cancelar Nómina"><img src="'.$img_del.'" height="25px" width="25px"></a>';
	$estatus="Ejecutada";
	if($nomina->estatus_general_id==2)
	  $estatus="Cancelada";
		
	echo "<tr background='$img_row'>\n";
	echo "<td>{$nomina->id}</td>\n";
	echo "<td align='center'>{$nomina->prenomina_id}</td>\n";
	echo "<td>{$nomina->fecha_inicial}</td>\n";
	echo "<td>{$nomina->fecha_final}</td>\n";
	echo "<td>{$nomina->espacio}</td>\n";
	echo "<td align='center'>{$nomina->dias_semana}</td>\n";
	echo "<td>$estatus</td>\n";
	echo "<td>{$nomina->usuario}</td>\n";
	echo "<td>{$nomina->fecha_captura}</td>\n";
	echo "<td>$link_pdf &nbsp; $link_del</td>\n";
	echo "</tr>\n";
	}
echo "</table></div>";
echo $this->pagination->create_links();
?>
