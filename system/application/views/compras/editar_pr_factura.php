<?php
foreach($pr_factura->all as $line) {

	//Construir Empresas
	$select1[0]="Elija";
	foreach($empresas->all as $row){
		$y=$row->id;
		$select1[$y]=$row->razon_social;
	}
	//print_r(array_values($select1));

	//Construir Proveedores
	$select2[0]="Elija";
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social;
	}

	//Inputs
	$fecha1=explode("-", $line->fecha);
	$fecha_db="".$fecha1[2]." ".$fecha1[1]." ".$fecha1[0];

	$fecha=array(
			'class'=>'date_input',
			'name'=>'fecha',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>''.$fecha_db,
	);

	$conceptos=array(
			'name'=>'conceptos',
			'size'=>'2',
			'value'=>''.$line->conceptos,
	);

	$folio_factura=array(
			'name'=>'folio_factura',
			'size'=>'10',
			'value'=>''.$line->folio_factura,
	);
	$monto_total=array(
			'name'=>'monto_total',
			'size'=>'10',
			'value'=>''.$line->monto_total,
	);

	//Titulo
	echo "<h2>$title</h2>";
	?>
<script type="text/javascript"> 
$($.date_input.initialize);
 $(document).ready(function() { 
// changing date format to DD/MM/YYYY
    $(".form_table").hide();
    $(".form_table").fadeOut("4000");
    $(".form_table").fadeIn("4000");
    $("#empr").focus();
  });
</script>
<?php
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_pr_factura', $atrib) . "\n";
echo form_fieldset('<b>Editar los Datos Generales de la Factura</b>') . "\n";
echo "<table  class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag' colspan='3'><label for=\"empresas_id\">Empresa:</label><br/>"; echo form_dropdown('empresas_id', $select1, "$line->empresas_id", "id='empr'");echo "</td></tr>";

echo "<td class='form_tag' colspan='3'><label for=\"cproveedores_id\">Proveedor: </label><br/>"; echo form_dropdown('cproveedores_id', $select2, "$line->cproveedores_id");echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"nombre\">Folio de la Factura:</label><br/>"; echo form_input($folio_factura); echo "</td><td class='form_tag'><label for=\"fecha\">Fecha de la Factura: </label><br/>"; echo form_input($fecha);echo "</td><td class='form_tag'><label for=\"monto_total\">Monto Total: </label>"; echo form_input($monto_total);echo "</td></tr>";

echo "<tr><td colspan='3' class=\"form_buttons\">";
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Cambios</button>';
}
echo form_close();
echo '</td></tr></table>';
echo form_fieldset_close();

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pr_facturas\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Listado de Facturas de Proveedores\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";
}

?>