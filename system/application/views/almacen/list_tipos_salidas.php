
<?php 
echo "<h2>$title</h2>"; 
echo "<div style='width:620px;margin:auto' align=\"right\">";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_tipo_salida\"><img src=\"".base_url()."images/boleta.png\" width=\"30px\" title=\"Nuevo Tipo de Salida\">Nuevo Tipo de Salida</a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<?php //echo $this->pagination->create_links();?>
<table  class="listado" border="0">
  <tr>
    <th>Id</th>
    <th>Nombre</th>
    <th>Edicion</th>
 </tr>
<?php
//Botones de Edicion
$img_row="".base_url()."images/table_row.png";
$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresion\" border=\"0\">";
$photo="";
$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar el Tipo de Salida\" border=\"0\">";
$delete="";
$edit="";

if($tipos_salidas!=false){
  foreach($tipos_salidas->all as $row) {
  //Permiso de Borrado en el 2 Byte X1X
  if(substr(decbin($permisos), 1, 1)==1 ){
    $delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_tipo_salida/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar el Tipo de Salida?');\">$trash</a>";
  }
//echo "--".decbin($permisos);
  if(substr(decbin($permisos), 2, 1)==1){
    $edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
  }

    echo "<tr background=\"$img_row\"><td>$row->id</td><td>$row->tag</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_tipo_salida/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";	      

  }
}
echo "</table></center>";
echo $this->pagination->create_links();
?>
