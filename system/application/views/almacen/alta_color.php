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
		'value'=>'',
		'size'=>'40',
		'id'=>'tag',
		'onsubmit'=>"validar()",
);
$clave=array(
		'name'=>'clave',
		'value'=>'',
		'size'=>'40',
		'id'=>'tag',
);
?>
<script>
  $(document).ready(function() {
	$('#tag').keyup(function(){
  		get_ajax($(this).val());
	});
});

function get_ajax(valor){
   if(valor.length > 3) {
    $.post("<? echo base_url(); ?>index.php/ajax_pet/color",{ enviarvalor: valor},
    function(data){
      $('#validation_result').html(data);
    });
  }
}

 function uppercase() {
   eval("document.form1.tag.value=document.form1.tag.value.toUpperCase()")

}

</script>
<?php

//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
//Link al listado
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_colores\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Listado de colores de Productos\">Listado de Colores</a>";
echo "<a href=\"".base_url()."index.php/almacen/almacen_reportes/formulario/rep_colores\"><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Colores\">Reporte de Colores</a></div>";

//$this->load->view('almacen/js_alta_color.php');
$this->load->view('validation_view');
//atributos del formulario
$atrib=array('name'=>'form1','id'=>'form1');

echo form_open($ruta.'/trans/alta_color', $atrib) . "\n";
echo form_fieldset('<b>Nuevo Color de Productos</b>') . "\n";
echo '<div id="validation_result" class="result_validator" align="center" width="200px" height="10px"></div>';

echo "<table width=\"45%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

$js = 'onChange="uppercase()"';

//campos
echo "<tr><td><label for=\"tag\">Nombre del Color:</label></td><td>"; echo form_input($tag,"",$js); echo "</td></tr>";
echo "<tr><td align='center' colspan='2'>".form_reset('form1','Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo "<button type='submit'>Guardar Registro</button>";
}//"submit"
echo '</td></tr></table>';
echo form_fieldset_close();


?>



