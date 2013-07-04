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
		'id'=>'proveedor',
		'size'=>'60',
		'value'=>'',
);
$clave=array(
		'name'=>'clave',
		'size'=>'30',
		'value'=>'',
);
$estado=array(
		'name'=>'estado',
		'size'=>'30',
		'value'=>'',
);
$municipio=array(
		'name'=>'municipio',
		'size'=>'30',
		'value'=>'',
);
$limite_credito=array(
		'name'=>'limite_credito',
		'size'=>'10',
		'value'=>'',
);
$dias_credito=array(
		'name'=>'dias_credito',
		'size'=>'10',
		'value'=>'',
);
$observaciones=array(
		'name'=>'observaciones',
		'columns'=>'60',
		'rows'=>'4',
		'value'=>'',
);
$rfc=array(
		'name'=>'rfc',
		'size'=>'15',
		'value'=>'',
);
$curp=array(
		'name'=>'curp',
		'value'=>'',
);
$email=array(
		'name'=>'email',
		'size'=>'30',
		'value'=>'',
);
$colonia=array(
		'name'=>'colonia',
		'size'=>'60',
		'value'=>'',
);
$codigo_postal=array(
		'name'=>'codigo_postal',
		'size'=>'13',
		'value'=>'',
);
$domicilio=array(
		'name'=>'domicilio',
		'value'=>'',
		'size'=>'60',
);
$calle=array(
		'name'=>'calle',
		'value'=>'',
		'size'=>'60',
);
$numero_exterior=array(
		'name'=>'numero_exterior',
		'value'=>'',
		'size'=>'15',
);
$numero_interior=array(
		'name'=>'numero_interior',
		'value'=>'',
		'size'=>'15',
);
$localidad=array(
		'name'=>'localidad',
		'value'=>'',
		'size'=>'30',
);
$lada=array(
		'name'=>'lada',
		'size'=>'10',
		'value'=>'',
		'maxlength'=>'5'
);
$fax=array(
		'name'=>'fax',
		'size'=>'20',
		'value'=>'',
);
$telefono=array(
		'name'=>'telefono',
		'size'=>'20',
		'value'=>'',

);
?>
<script>
	$(document).ready(function() {
		$('#proveedor').keyup(function(){
			get_proveedor($(this).val());
		});

		$('#rfc').keyup(function(){
			get_rfc($(this).val(), 'proveedor');
		});

	});

	function get_rfc(valor, table){
		if(valor.length > 6) {
			$.post("<? echo base_url(); ?>index.php/ajax_pet/rfc",{ enviarvalor: valor, obj: table },
									 function(data){
										 $('#validate_rfc').html(data);
									 });
		}
	}

	function get_proveedor(valor){
		//      $('#validate_proveedor').html(valor);
		if(valor.length > 0) {
			$.post("<? echo base_url(); ?>index.php/ajax_pet/proveedor",{ enviarvalor: valor},
									 function(data){
										 $('#validate_proveedor').html(data);
										 //      $('#subfamilia_productos').focus();
									 });
		}
	}

	</script>
<?php
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1');
echo form_open($ruta.'/trans/alta_proveedor', $atrib) . "\n";
echo form_fieldset('<b>Datos Generales</b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"razon_social\">Razón Social:</label></td>
    <td class='form_input' colspan='3'>"; echo form_input($razon_social); echo "<br/><span id='validate_proveedor'></span></td>
    <td class='form_tag'>
        <label for=\"nombre_corto\">Nombre:</label><br/>
        <input type=\"text\" value=\"\" name=\"nombre_corto\" size='30' id=\"nombre_corto\"/>
    </td></tr>";

echo "<tr><td class='form_tag' colspan='4'><label for=\"calle\">Calle: </label><br/>"; echo form_input($calle); echo "<br/><br/><label for=\"numero_exterior\">No. Exterior: </label>"; echo form_input($numero_exterior); echo "<label for=\"numero_interior\">No. Interior: </label>"; echo form_input($numero_interior); echo "<br/><label for=\"colonia\">Colonia: </label><br/>"; echo form_input($colonia); echo "<td class='form_tag'><label for=\"localidad\">Localidad:</label><br/>";echo form_input($localidad); echo "<br/><label for=\"municipio\">Municipio:</label><br/>";echo form_input($municipio); echo "<br/><label for=\"estado\">Estado: </label><br/>";echo form_input($estado);echo "</td></tr>";

echo "<tr>
        <td class='form_tag' ><label for=\"codigo_postal\">Código Postal:</label><br/>";
            echo form_input($codigo_postal); echo "</td>
        <td class='form_tag' colspan='3'><label for=\"email\">Correo electrónico: </label><br/>";
        echo form_input($email);echo "</td>
        <td class='form_tag'>";  echo "</td></tr>";
?>
    <tr>
        <td class='form_tag'>
            <label for=\"lada\">LADA:</label><br/>
            <?=form_input($lada); ?>
         </td>
         <td class='form_tag'>
            <label for=\"telefono\">Teléfono:</label><br/>
            <?= form_input($telefono);?><br/>
         </td>
        <td class='form_tag'>
            <label for=\"celular\">Celular:</label><br/>
            <input type="text" value="" name="celular" id="celular"/>
        </td>
        <td class='form_tag'>
            <label for=\"nextel\">Nextel:</label><br/>
            <input type="text" value="" name="nextel" id="nextel"/>
        </td>
        <td class='form_tag'>
            <label for=\"lada\">FAX:</label><br/>
            <?= form_input($fax); ?>
         </td>
    </tr>
<?php
echo "<tr>
    <td class='form_tag'>
        <label for=\"dias_credito\">Días de Crédito: </label><br/>";
        echo form_input($dias_credito); echo "</td>
        <td class='form_tag'>
        <label for=\"limite_credito\">Limite de Crédito: </label><br/>";
            echo form_input($limite_credito);echo "<br/>";
        echo "</td>

        <td class='form_tag'>
        <label for=\"rfc\">RFC:</label><br/>";
         echo form_input($rfc); echo "<br/>
        </td>
        <td class='form_tag'>
        <label for=\"rfc\">CURP:</label><br/>";
        echo form_input($curp); echo "</td>
    </tr>";



//Cerrar el Formulario
echo "<tr><td colspan='5' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
echo form_close();

//Permisos de Escritura byte 1
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit">Guardar</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

?>
