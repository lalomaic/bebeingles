<?php
echo "<h2>$title $tienda</h2>";
//Link de traspasos
$baseLinks = base_url()."index.php/".$ruta."/".$controller."/".$funcion;
echo "<div style='width:920px;margin:5px auto;border-bottom: 1px solid #CCC;padding-bottom: 5px;' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/tienda/menu\"  title=\"Regresar al Menu Tienda\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"25px\"> Menu Tienda</a>";
echo "<a href=\"".$baseLinks."/alta_entrada_local\" title=\"Alta entrada de traspaso\"><img src=\"".base_url()."images/new_reg.png\" width=\"25px\"  > Entrada a traspaso </a>";
echo "<a href=\"".$baseLinks."/list_salida_traspasos\" title=\"Listado de traspasos enviados\"><img src=\"".base_url()."images/factura.png\" width=\"25px\"  > Traspasos enviados </a></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

//Botones de Edicion
$img_row = "".base_url()."images/table_row.png";
$photo = "<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Detalles de Traspaso\" border=\"0\">";
$edit_url = base_url()."index.php/tienda/tienda_reportes/rep_pedido_traspaso/";

if($paginacion == true)
    echo $this->pagination->create_links();
?>
<table class="listado" border="0">
    <tr>
        <th> Id Traspaso </th>
        <th> Fecha Envio </th>
        <th> Fecha Recepcion </th>
        <th> Envia </th>
        <th> Recibe </th>
        <th> Estatus </th>
        <th> Capturista </th>
        <th> Edición </th>
    </tr>
<?php
if($traspasos == false){
    echo "Sin Registros";
} else {
    foreach($traspasos->all as $row) {
        $edit="<a href=\"".$edit_url.$row->id."\">$photo</a>";
        echo "<tr background=\"$img_row\">".
                "<td align=\"center\"> $row->id </td>".
                "<td align=\"center\"> $row->fecha_envio </td>".
                "<td align=\"center\"> $row->fecha_recibe </td>".
                "<td align=\"center\"> $row->espacio_envia </td>".
                "<td align=\"center\"> $row->espacio_recibe </td>".
                "<td align=\"center\"> $row->estatus </td>".
                "<td align=\"center\"> $row->usuario </td>".
                "<td>$edit</td>".
                "</tr>";
    }
}
echo "</table></center>";
if($paginacion == true)
    echo $this->pagination->create_links();
?>
