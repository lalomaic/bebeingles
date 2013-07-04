<?php
echo "<h2>$title</h2>";
//Link de traspasos
$baseLinks = base_url()."index.php/".$ruta."/".$controller."/".$funcion;
echo "<div style='width:920px;margin:5px auto;padding-bottom: 5px;' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/tienda/menu\"  title=\"Regresar al Menu Tienda\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"25px\"> Menu Tienda</a>";
echo "<a href=\"".$baseLinks."/list_salida_traspasos\" title=\"Listado de traspasos enviados\"><img src=\"".base_url()."images/factura.png\" width=\"25px\"  > Traspasos enviados </a>";
echo "<a href=\"".$baseLinks."/list_transferencias\" title=\"Listado de traspasos recibidos\"><img src=\"".base_url()."images/factura.png\" width=\"25px\"  > Traspasos recibidos </a></div>";
echo $this->pagination->create_links();
?>
<table  class="listado" border="0" width="800" >
  <tr>
    <th>Id Traspaso</th>
    <th>Fecha Envio</th>
    <th>Fecha Recepcion</th>
    <th>Envia</th>
    <th>Recibe</th>
    <th>Estatus</th>
    <th>Capturista</th>
    <th>Edición</th>
 </tr>
<?php
//Botones de Edicion
$img_row="".base_url()."images/table_row.png";
$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión de la Orden de Traspaso\" border=\"0\">";
$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Dar entrada al Pedido de Compra\" border=\"0\">";
$altaLink=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/alta_traspaso/";
$pdf_url = base_url()."index.php/tienda/tienda_reportes/rep_pedido_traspaso/";


if($traspasos==false){
	echo "Sin Registros";
} else {

    foreach($traspasos->all as $row) {
        $pdf="<a href=\"".$pdf_url.$row->id."\">$photo</a>";
		//Permiso de Borrado en el 2 Byte X1X
		echo "<tr background=\"$img_row\">".
                        "<td align=\"center\">$row->id</td>".
                        "<td align=\"center\">$row->fecha_envio</td>".
                        "<td align=\"center\">$row->fecha_recibe</td>".
                        "<td align=\"center\">$row->espacio_envia</td>".
                        "<td align=\"center\">$row->espacio_recibe</td>".
                        "<td align=\"center\">$row->estatus</td>".
                        "<td align=\"center\">$row->usuario</td>".
                        "<td><a href=\"".$altaLink.$row->id."\">$ok</a>$pdf</td>".
                     "</tr>";

    }
}
echo "</table></center>";
echo $this->pagination->create_links();
?>
