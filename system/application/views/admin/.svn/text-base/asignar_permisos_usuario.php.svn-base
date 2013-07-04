<script>
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
$tran=array(); $rep=array(); $admin=array();$m_prev=0; $r=1; $a=""; $b=""; $e="";
if($usuario_permisos==false){
	$usuario_permisos[0]='';
}
foreach($asm->all as $line){
	//Buscar en permisos asignados
	if (array_key_exists($line->id, $usuario_permisos)) {
		$checked="checked=true";
		$permiso=$usuario_permisos[$line->id];
		//		echo "<br/>$line->id - ".decbin(5);
		if(substr(decbin($permiso), 2, 1)==1 ){
			//Acceso en el 1er byte
			$a="checked=true";
		} else
			$a="checked=false";

		if(substr(decbin($permiso), 1, 1)==1 ){
			//Borrado en el 2do byte
			$b="checked=true";
		} else
			$b="checked=false";
		if(substr(decbin($permiso), 0, 1)==1 ){
			//Edicion en el 3do byte
			$e="checked=true";
		} else
			$e="checked=false";


	} else {
		$checked="";
		$a="";
		$b="";
		$e="";
	}

	if($line->tipo_accion_id==1){
		if(isset($tran[$line->modulo_id]))
			$tran[$line->modulo_id].="<div class='contenedor_top'><label class='etiqueta_accion'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked >$line->id - $line->nombre</label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$tran[$line->modulo_id]="<div class='contenedor_top'><label class='etiqueta_accion'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked>$line->id - $line->nombre<label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";


	} else if($line->tipo_accion_id==2){
		if(isset($rep[$line->modulo_id])==true)
			$rep[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked ><label class='etiqueta_accion'>$line->id - $line->nombre</label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";
		else
			$rep[$line->modulo_id]="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre</label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $checked onClick='javascript:marcar_c(this.checked, $r)'>Acceso</div></div>";


	} else if($line->tipo_accion_id==3){
		if(isset($admin[$line->modulo_id]))
			$admin[$line->modulo_id].="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre</label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' name='permiso$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso$r' $e>Edición</div></div>";
		else
			$admin[$line->modulo_id]="<div class='contenedor_top'><input type='checkbox' value='$line->id' name='accion$r' id='accion$r' $checked><label class='etiqueta_accion'>$line->id - $line->nombre</label><br/><div id='contenedor$r' class='contenedor_permisos'><input type='checkbox' value='1' name='permiso_a$r' id='permiso$r' $a>Acceso<input type='checkbox' value='2' name='permiso_b$r' id='permiso$r' $b>Borrado<input type='checkbox' value='4' name='permiso_e$r' id='permiso$r' $e>Edición</div></div>";
	}
	$r+=1; $a=""; $b=""; $e="";

}

echo "<h2>$title</h2>";
$modulo_prev="";
//Generar las filas de las acciones
foreach($modulos->all as $row){
	$atrib=array('name'=>'form1','id'=>'form1');
	echo form_open($ruta.'/trans/asignar_permisos_usuario', $atrib) . "\n";
	echo "<table id=\"permiso\" class=\"listado\" border=\"0\" width=\"90%\" valign=\"top\">";
	echo "<tr><th colspan=\"3\"><strong>$row->id - $row->nombre</strong><br><a href=\"javascript:mostrar($row->id)\">Mostrar</a> - <a href=\"javascript:ocultar($row->id)\">Ocultar</a></th></tr> </table>\n";
	echo "<table id=\"permiso_".$row->id."\" class=\"listado\" border=\"0\" width=\"90%\" valign=\"top\">";
	echo "<tr><th>Transacciones</th><th>Reportes</th><th>Administración</th></tr>\n";
	//Generar fila con el nombre del submodulo, accion y radio buttons para los permisos
	if(isset($tran["$row->id"])==false)
		$tran["$row->id"]="";
	if(isset($rep["$row->id"])==false)
		$rep["$row->id"]="";
	if(isset($admin["$row->id"])==false)
		$admin["$row->id"]="";
	echo "<tr valign=\"top\"><td>".$tran["$row->id"]."</td><td>".$rep["$row->id"]."</td><td>".$admin["$row->id"]."</td></tr></table>
	\n";
}
echo "<div valign=\"top\" align='center'><button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar sin guardar</button><button type='submit'>Guardar</button></div>";
echo form_hidden('filas', $r);
echo form_hidden('usuario_id', $usuario->id);
echo form_close();
?>

