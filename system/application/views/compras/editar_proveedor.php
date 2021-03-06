<?php
//Construir Empresas
foreach ($proveedor->all as $data1){

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
			'size'=>'40',
			'value'=>''.$data1->razon_social,
	);
	$clave=array(
			'name'=>'clave',
			'size'=>'30',
			'value'=>''.$data1->clave,
	);
	$estado=array(
			'name'=>'estado',
			'size'=>'30',
			'value'=>''.$data1->estado,
	);
	$municipio=array(
			'name'=>'municipio',
			'size'=>'30',
			'value'=>''.$data1->municipio,
	);
	$limite_credito=array(
			'name'=>'limite_credito',
			'size'=>'10',
			'value'=>''.$data1->limite_credito,
	);
	$dias_credito=array(
			'name'=>'dias_credito',
			'size'=>'10',
			'value'=>''.$data1->dias_credito,
	);
	$observaciones=array(
			'name'=>'observaciones',
			'columns'=>'60',
			'rows'=>'4',
			'value'=>''.$data1->observaciones,
	);
	$rfc=array(
			'name'=>'rfc',
			'size'=>'15',
			'value'=>''.$data1->rfc,
	);
	$curp=array(
			'name'=>'curp',
			'size'=>'15',
			'value'=>''.$data1->curp,
	);
	$email=array(
			'name'=>'email',
			'size'=>'30',
			'value'=>''.$data1->email,
	);
	$colonia=array(
			'name'=>'colonia',
			'size'=>'60',
			'value'=>''.$data1->colonia,
	);
	$codigo_postal=array(
			'name'=>'codigo_postal',
			'size'=>'13',
			'value'=>''.$data1->codigo_postal,
	);
	$domicilio=array(
			'name'=>'domicilio',
			'value'=>''.$data1->domicilio,
			'size'=>'60',
	);
	$calle=array(
			'name'=>'calle',
			'value'=>''.$data1->calle,
			'size'=>'60',
	);
	$numero_exterior=array(
			'name'=>'numero_exterior',
			'value'=>''.$data1->numero_exterior,
			'size'=>'15',
	);
	$numero_interior=array(
			'name'=>'numero_interior',
			'value'=>''.$data1->numero_interior,
			'size'=>'15',
	);
	$localidad=array(
			'name'=>'localidad',
			'value'=>''.$data1->localidad,
			'size'=>'30',
	);
	$lada=array(
			'name'=>'lada',
			'size'=>'10',
			'value'=>''.$data1->lada,
	);
	$fax=array(
			'name'=>'fax',
			'size'=>'10',
			'value'=>''.$data1->fax,
	);
	$telefono=array(
			'name'=>'telefono',
			'size'=>'20',
			'value'=>''.$data1->telefono,
	);
	//Titulo
	echo "<h2>$title</h2>";


	//Abrir Formulario
	$atrib=array('name'=>'form1');
	echo form_open($ruta.'/trans/alta_proveedor', $atrib) . "\n";
	echo form_fieldset('<b>Datos Generales</b>') . "\n";
	echo "<table width=\"80%\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";


	//Campos del Formulario

	echo "<tr><td class='form_tag'><label for=\"razon_social\">Razón Social:</label></td><td class='form_input'>"; echo form_input($razon_social); echo "</td><td class='form_tag'><label for=\"clave\">Clave:</label></td><td class='form_input'>"; echo form_input($clave);echo "<span id='validate_user'></span></td></tr>";

	echo "<tr><td class='form_tag' colspan='2'><label for=\"calle\">Calle: </label><br/>"; 
        echo form_input($calle); echo "<br/><br/><label for=\"numero_exterior\">No. Exterior: </label>"; 
        echo form_input($numero_exterior); 
        echo "<label for=\"numero_interior\">No. Interior: </label>"; 
        echo form_input($numero_interior); echo "<br/><label for=\"colonia\">Colonia: </label><br/>"; 
        echo form_input($colonia); 
        echo "<td class='form_tag'><label for=\"localidad\">Localidad:</label><br/>";
        echo form_input($localidad); echo "<br/><label for=\"municipio\">Municipio:</label><br/>";
        echo form_input($municipio); echo "<br/><label for=\"estado\">Estado: </label><br/>";
        echo form_input($estado);echo "</td>";
       
	echo "<tr><td class='form_tag' ><label for=\"codigo_postal\">Código Postal:</label><br/>"; 
        echo form_input($codigo_postal);
        echo "</td><td class='form_tag'><label for=\"email\">Correo electrónico: </label><br/>"; 
        echo form_input($email);
        echo "</td><td class='form_tag'><label for=\"telefono\">Teléfono:</label><br/>"; 
        echo form_input($telefono);  
        echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"lada\">LADA:</label><br/>"; 
        echo form_input($lada); echo "<br/><label for=\"lada\">FAX:</label><br/>"; 
        echo form_input($fax); 
        echo "</td><td class='form_tag'><label for=\"limite_credito\">Limite de Crédito: </label><br/>"; 
        echo form_input($limite_credito);
        echo "<br/><label for=\"dias_credito\">Días de Crédito: </label><br/>"; 
        echo form_input($dias_credito);
        echo "</td><td class='form_tag'><label for=\"rfc\">RFC:</label><br/>"; 
        echo form_input($rfc); 
        echo "<br/><label for=\"rfc\">CURP:</label><br/>"; 
        echo form_input($curp); echo "</td></tr>";
        
        echo "<tr><td><b><h3>Cuentas Bancarias</h3></b></td></tr>";
        if($cbancarias !=false){
            
 foreach ($cbancarias->all as $data2){
        
        echo "<tr><td><label for=\"municipio\"><b>Banco:</b><font color='blue'> $data2->banco</font></label></td>";
        echo "<td><label for=\"estado\"><b>Numero Cuenta:</b><font color='blue'> $data2->numero_cuenta </font></label></td>";
        
        echo "<td><label for=\"estado\"><b>Clabe Interbamcaria:</b><font color='blue'> $data2->clabe </font></label></td>";
        
        echo "</tr>";
        }
        }
        echo'<tr><td><b>Sin Cuentas Bancarias</b></td></tr>';
	//Cerrar el Formulario
	echo "<tr><td colspan='4' class=\"form_buttons\">";
	echo form_hidden('id', "$data1->id");
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
	echo form_close();

	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit">Guardar Cambios</button>';
	}
	echo '</td></tr></table>';
	echo form_fieldset_close();

	//Link al listado
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";
	// echo base_url();
}
?>