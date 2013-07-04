<script type="text/javascript">    
    $(document).ready(function(){
        $('.esconder').hide();
    })
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
	if ($grupo_permisos && array_key_exists($line->id, $grupo_permisos)) {
		$checked = "checked";
		$permiso = $grupo_permisos[$line->id];
		//		echo "<br/>$line->id - ".decbin(5);
		if (decbin($permiso) == "1" | decbin($permiso) == "11" | decbin($permiso) == "101" | decbin($permiso) == "111") {
			//Acceso en el 1er byte
			$a = "checked";
		} else
			$a="";

		if (decbin($permiso) == "10" | decbin($permiso) == "11" | decbin($permiso) == "110" | decbin($permiso) == "111") {
			//Borrado en el 2do byte
			$b = "checked";
		} else
			$b="";
		if (decbin($permiso) == "100" | decbin($permiso) == "101" | decbin($permiso) == "110" | decbin($permiso) == "111") {
			//Edicion en el 3do byte
			$e = "checked";
		} else
			$e="";
	} else {
		$checked = "";
		$a = "";
		$b = "";
		$e = "";
	}

	if ($line->tipo_accion_id == 1) {
		if (isset($tran[$line->modulo_id]))
			$tran[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked ><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$tran[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
	} else if ($line->tipo_accion_id == 2) {
		if (isset($rep[$line->modulo_id]) == true)
			$rep[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked ><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$rep[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
	} else if ($line->tipo_accion_id == 3) {
		if (isset($admin[$line->modulo_id]))
			$admin[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' id='permiso_b$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso_e$r' $e>Edición</div></div>";
		else
			$admin[$line->modulo_id] = "<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso_a$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' id='permiso_b$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso_e$r' $e>Edición</div></div>";
	}
	$r+=1;
	$a = "";
	$b = "";
	$e = "";
}

echo "<h2>$title</h2>";
$atrib = array('name' => 'form1', 'id' => 'form1');
echo form_open($ruta . '/trans/asignar_permisos_grupo', $atrib) . "\n";

$modulo_prev = "";
//Generar las filas de las acciones
foreach ($modulos->all as $row) {
	echo "<table id=\"permiso\" class=\"listado\" border=\"0\" width=\"90%\" valign=\"top\">";
	echo "<tr><th colspan=\"3\"><strong>$row->id - $row->nombre</strong><br><a href=\"javascript:mostrar($row->id)\">Mostrar</a> - <a href=\"javascript:ocultar($row->id)\">Ocultar</a></th></tr> </table>\n";
	echo "<table id=\"permiso_" . $row->id . "\" class=\"listado esconder\" hidden=\"true\" border=\"0\" width=\"90%\" valign=\"top\">";
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
echo "<div valign=\"top\" align='center'><button type='button' onclick=\"window.location='" . base_url() . "index.php/inicio/acceso/" . $ruta . "/menu'\">Cerrar sin guardar</button><button type='submit'>Guardar</button></div>";
$data = array(
		'type' => 'hidden',
		'name' => 'filas',
		'id' => 'filas',
		'value' => $r,
);
echo form_input($data);
echo form_hidden('grupos_id', $grupo->id);
echo form_close();
?>