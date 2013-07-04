<script>
	function enviar(){
		if($("#stock_id").val()>0){
			$('#form1').submit();
		}
	}
</script>
<?php
$select[0]="";
foreach($plantillas->all as $row){
	$y=$row->stock_id;
	$select[$y]=$row->nombre;
}
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/almacen_c/formulario/alta_traspaso_stock_almacen/generar_pedido/', $atrib) . "\n";
echo form_fieldset('<b>Elija la Sucursal a la que va destinado el Traspaso</b>') . "\n";
echo "<table class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresas_id\">Comprador:</label> $empresa->razon_social </td>";

echo "<tr><td class='form_tag'><label for=\"stock_id\">Paso 1.- Elija la plantilla: </label><br/>"; echo form_dropdown('stock_id', $select, 0, "id='stock_id' size='10'");echo "</td><td class='form_tag' valign='top'><label for=\"b1\">Paso 2.-</label><br/><button type='button' onclick='javascript:enviar()' name='b1' >Generar Traspaso</button></td></tr>";

echo '</table>';
echo form_fieldset_close();
echo form_close();

?>