<script type="text/javascript">
    function mostrar(mod){
        $('#permiso_'+mod).show();
    }
    function ocultar(mod){
        $('#permiso_'+mod).hide();
    }
    function marcar_c(chk, linea){
        if(chk==true){
            $('#accion'+linea).attr('checked', true);
        } else {
            $('#accion'+linea).attr('checked', false);
        }
    }    
</script>
<?php
//Inputs
$nombre=array(
		'name'=>'nombre',
		'size'=>'40',
		'value'=>'',
		'id'=>'nombre',
);

$descripcion=array(
		'name'=>'descripcion',
		'size'=>'50',
		'value'=>'',
		'id'=>'descripcion',
);

//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/alta_grupo', $atrib) . "\n";
echo form_fieldset('<b>Grupos</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"nombre\">Nombre del Grupo:</label></td><td class='form_input'>";
echo form_input($nombre);
echo "</td><td class='form_tag'><label for=\"descripcion\">Descripción: </label></td><td class='form_input'>";
echo form_input($descripcion);
echo "</td></tr>";

//Permisos para el grupo
echo "<tr><td colspan='4'><label class='form_tag'>Permisos para el grupo: </label>";

$tran = array();
$rep = array();
$admin = array();
$m_prev = 0;
$r = 1;
$a = "";
$b = "";
$e = "";

foreach ($asm->all as $line) {
	//Buscar en permisos asignados

	$checked = "";
	$a = "";
	$b = "";
	$e = "";


	if ($line->tipo_accion_id == 1) {
		if (isset($tran[$line->modulo_id]))
			$tran[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked ><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$tran[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
	} else if ($line->tipo_accion_id == 2) {
		if (isset($rep[$line->modulo_id]) == true)
			$rep[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked ><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$rep[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
	} else if ($line->tipo_accion_id == 3) {
		if (isset($admin[$line->modulo_id]))
			$admin[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' name='permiso$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso$r' $e>Edición</div></div>";
		else
			$admin[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' id='permiso$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso$r' $e>Edición</div></div>";
	}
	$r+=1;
}
foreach ($modulos->all as $row) {
	echo "<table id=\"permiso\" class=\"listado\" style=\"text-align: center;\" border=\"0\" width=\"100%\" valign=\"top\">";
	echo "<tr><th colspan=\"3\"><strong>$row->id - $row->nombre</strong><br><a href=\"javascript:mostrar($row->id)\">Mostrar</a> - <a href=\"javascript:ocultar($row->id)\">Ocultar</a></th></tr> </table>\n";
	echo "<table id=\"permiso_" . $row->id . "\" class=\"listado\"  hidden=\"true\" style=\"text-align: center;\" border=\"0\" width=\"100%\" valign=\"top\">";
	echo "<tr><th>Transacciones</th><th>Reportes</th><th>Administración</th></tr>\n";
	//Generar fila con el nombre del submodulo, accion y radio buttons para los permisos
	if (isset($tran["$row->id"]) == false)
		$tran["$row->id"] = "";
	if (isset($rep["$row->id"]) == false)
		$rep["$row->id"] = "";
	if (isset($admin["$row->id"]) == false)
		$admin["$row->id"] = "";
	echo "<tr valign=\"top\"><td>" . $tran["$row->id"] . "</td><td>" . $rep["$row->id"] . "</td><td>" . $admin["$row->id"] . "</td></tr></table>
	\n";
}
echo form_hidden('filas', $r);

echo '</td></tr>';


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

//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_grupos\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Grupos\"></a></div>";

?>