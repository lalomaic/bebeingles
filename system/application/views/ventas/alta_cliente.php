<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php
//Construir Municipios
$select1[0]="Elija";
foreach($municipios->all as $row){
	$y=$row->id;
	$select1[$y]=$row->tag;
}

//Construir Estatus
$select2 = array();
foreach($estatus->all as $row){
	$y=$row->id;
	$select2[$y]=$row->tag;
}

//Inputs
$razon_social=array(
		'name'=>'razon_social',
		'size'=>'35',
		'value'=>'',
                 'id'=>'razon_social',
);

$nombre_corto=array(
		'name'=>'nombre_corto',
		'size'=>'35',
		'value'=>'',
                 'id'=>'nombre_corto',
);


$estado=array(
		'name'=>'estado',
		'size'=>'30',
		'value'=>'',
                'id'=>'estado,'
);
$municipio=array(
		'name'=>'municipio',
		'size'=>'30',
		'value'=>'',
                 'id'=>'municipio',
);
$limite_credito=array(
		'name'=>'limite_credito',
		'size'=>'10',
		'value'=>'',
                'id'=>'limite_credito'
);
$dias_credito=array(
		'name'=>'dias_credito',
		'size'=>'10',
		'value'=>'',
                 'id'=>'dias_credito',
);

$rfc=array(
		'name'=>'rfc',
		'size'=>'15',
		'value'=>'',
                'id'=>'rfc',
                );
$curp=array(
		'name'=>'curp',
		'size'=>'18',
		'value'=>'',
                'id'=>'curp',
);
$email=array(
		'name'=>'email',
		'size'=>'30',
		'value'=>'',
                 'id'=>'email'
);
$colonia=array(
		'name'=>'colonia',
		'size'=>'35',
		'value'=>'',
                'id'=>'colonia'
);
$codigo_postal=array(
		'name'=>'codigo_postal',
		'size'=>'13',
		'value'=>'',
                 'id'=>'codigo_postal',
);
$domicilio=array(
		'name'=>'domicilio',
		'value'=>'',
		'size'=>'20',
                'cols'=>'28',
                'rows'=>'8', 
                'id'=>'domicilio',
);
$calle=array(
		'name'=>'calle',
		'value'=>'',
		'size'=>'35',
                 'id'=>'calle'
);
$numero_exterior=array(
		'name'=>'numero_exterior',
		'value'=>'',
		'size'=>'15',
                'id'=>'numero_exterior'
);
$numero_interior=array(
		'name'=>'numero_interior',
		'value'=>'',
		'size'=>'15',
                'id'=>'numero_interior',
);
$localidad=array(
		'name'=>'localidad',
		'value'=>'',
		'size'=>'30',
                'id'=>'localidad',
);
$lada=array(
		'name'=>'lada',
		'size'=>'10',
		'value'=>'',
                 'id'=>'lada',
);
$fax=array(
		'name'=>'fax',
		'size'=>'10',
		'value'=>'',
                 'id'=>'fax',
);
$telefono=array(
		'name'=>'telefono',
		'size'=>'20',
		'value'=>'',
                'id'=>'telefono',
);

//Titulo
echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"". base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_clientes\" target='_blank'><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Productos\" target='_blank'>Lista de Clientes </a>";
echo "<a href=\"". base_url() . "index.php/ventas/ventas_reportes/formulario/rep_clientes\" target='_blank'><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Clientes\" target='_blank'>Reporte de Clientes </a></div>";

$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');

echo form_open($ruta.'/trans/alta_cliente', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales</b>') . "\n";
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table  class='form_table' >";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"razon_social\">Nombre o Razon Social: </label><br/>"; echo form_input($razon_social);
echo "<br/><span id='validate_razon_social'></span></td><td class='form_tag'><label for=\"rfc\">RFC:</label><br/>"; echo form_input($rfc);  
echo "<br/><span id='validate_rfc'></span></td><td class='form_tag'><label for=\"curp\">CURP:</label><br/>"; echo form_input($curp);  
echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"calle\">Calle: </label><br/>"; echo form_input($calle);
echo "</td><td class='form_tag'><label for=\"numero_exterior\">No. Exterior:</label><br/>"; echo form_input($numero_exterior);  
echo "</td><td class='form_tag'><label for=\"numero_interior\">No. Interior:</label><br/>"; echo form_input($numero_interior);  
echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"coloni\">Colonia: </label><br/>"; echo form_input($colonia);
echo "</td><td class='form_tag'><label for=\"codigo_postal\">Codigo Postal:</label><br/>"; echo form_input($codigo_postal);    
echo "</td></tr>";

echo "<tr><td class='form_tag' ><label for=\"localidad\">Localidad:</label><br/>"; echo form_input($localidad); 
echo "</td><td class='form_tag'><label for=\"municipio\">Municipio:</label><br/>"; echo form_input($municipio);    
echo "</td><td class='form_tag'><label for=\"estado\">Estado:</label><br/>"; echo form_input($estado); 
echo "</td></tr>";

echo "</td><td class='form_tag'><label for=\"lada\">Lada: </label><br/>"; echo form_input($lada);
echo "</td><td class='form_tag'><label for=\"telefono\">Teléfono:</label><br/>"; echo form_input($telefono);  
echo "</td><td class='form_tag'><label for=\"fax\">Fax:</label><br/>"; echo form_input($fax);  
echo "</td></tr>";

echo "</td><td class='form_tag'><label for=\"email\">Correo Electronico: </label><br/>"; echo form_input($email);
echo "</td><td class='form_tag'><label for=\"limite_credito\">Limite de Credito:</label><br/>"; echo form_input($limite_credito);  
echo "</td><td class='form_tag'><label for=\"dias_credito\">Dias de Credito:</label><br/>"; echo form_input($dias_credito);  
echo "</td></tr>";

echo "</td><td class='form_tag'><label for=\"nombre_corto\">Nombre corto: </label><br/>"; echo form_input($nombre_corto);
echo "</td><td class='form_tag'><label for=\"domicilio\">Domicilio a Entregar:</label><br/>"; echo form_textarea($domicilio);  
echo "</td></tr>";

echo "<tr><td class='form_tag' ><label for=\"estatus_general_id\">Estatus:</label></td><td class='form_tag'>"; echo form_dropdown('estatus_general_id', $select2,0, "disabled='disabled'"); 
echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='4' class=\"form_buttons\">";
echo form_reset('form1', 'Limpiar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" >Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_close();
echo form_fieldset_close();

?>

<script>
	$(document).ready(function() {
		$('#razon_social').keyup(function(){
                    get_razon_social($(this).val());
		});

		$('#rfc').keyup(function(){
			get_rfc($(this).val(), 'razon_social');
		});

	});

	function get_rfc(valor, table){
		if(valor.length > 6) {
			$.post("<? echo base_url(); ?>index.php/ajax_pet/rfc_cliente",{ enviarvalor: valor, obj: table },
									 function(data){
										 $('#validate_rfc').html(data);
									 });
		}
	}

	function get_razon_social(valor){
		//      $('#validate_proveedor').html(valor);
		if(valor.length > 0) {
			$.post("<? echo base_url(); ?>index.php/ajax_pet/razon_social",{ enviarvalor: valor},
									 function(data){
										 $('#validate_razon_social').html(data);
										 //      $('#subfamilia_productos').focus();
									 });
		}
	}

	</script>
