<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php
//Inputs
$tag=array(
		'name'=>'tag',
		'size'=>'40',
		'value'=>'',
		'id'=>'tag',
);

//Titulo
echo "<h2>$title</h2>";
//Link al listado
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo  "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_marcas\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de Marcas de Productos\">Listado de Marcas</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_marcas\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Marcas\">Reporte de Marcas</a></div>";


?>
<script>
  $(document).ready(function() {
$('#tag').keyup(function(){
  get_ajax($(this).val());
});
});

function get_ajax(valor){
   if(valor.length > 3) {
    $.post("<? echo base_url(); ?>index.php/ajax_pet/marca",{ enviarvalor: valor},
    function(data){
      $('#validation_result').html(data);
    });
  }
}

</script>
<?php
//Load validacion
$this->load->view('validation_view');
//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_marca', $atrib) . "\n";
echo form_fieldset('<b>Nueva Marca de Producto</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"tag\">Nombre de la Marca:</label></td><td class='form_input'>"; echo form_input($tag); echo "</td></tr>";

//echo "<td class='form_tag'><label for=\"cproveedores_id\">Proveedor: </label></td><td class='form_input'>"; echo form_dropdown('proveedor_id', $select2, 0);echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

?>