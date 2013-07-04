<style>
    fieldset{
        width: 920px;
        margin: auto;
        margin-bottom: 10px;
    }
</style>

<?php
foreach($cliente->all as $line){
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
			'size'=>'30',
			'value'=>''.$line->razon_social,
	);
	$clave=array(
			'name'=>'clave',
			'size'=>'30',
			'value'=>''.$line->clave,
	);
	$estado=array(
			'name'=>'estado',
			'size'=>'30',
			'value'=>''.$line->estado,
	);
	$municipio=array(
			'name'=>'municipio',
			'size'=>'30',
			'value'=>''.$line->municipio,
	);
	$limite_credito=array(
			'name'=>'limite_credito',
			'size'=>'10',
			'value'=>''.$line->limite_credito,
	);
	$dias_credito=array(
			'name'=>'dias_credito',
			'size'=>'10',
			'value'=>''.$line->dias_credito,
	);

	

	$rfc=array(
			'name'=>'rfc',
			'size'=>'15',
			'value'=>''.$line->rfc,
	);
	$curp=array(
			'name'=>'curp',
			'size'=>'15',
			'value'=>''.$line->curp,
	);
	$email=array(
			'name'=>'email',
			'size'=>'30',
			'value'=>''.$line->email,
	);
	$colonia=array(
			'name'=>'colonia',
			'size'=>'30',
			'value'=>''.$line->colonia,
	);
	$codigo_postal=array(
			'name'=>'codigo_postal',
			'size'=>'13',
			'value'=>''.$line->codigo_postal,
	);
	$domicilio=array(
		'name'=>'domicilio',
		'value'=>''.$line->domicilio,
		'size'=>'20',
                'cols'=>'28',
                'rows'=>'8', 
                'id'=>'domicilio',
);
	$calle=array(
			'name'=>'calle',
			'size'=>'30',
			'value'=>''.$line->calle,
	);
	$numero_exterior=array(
			'name'=>'numero_exterior',
			'size'=>'15',
			'value'=>''.$line->numero_exterior,
	);
	$numero_interior=array(
			'name'=>'numero_interior',
			'size'=>'15',
			'value'=>''.$line->numero_interior,
	);
	$localidad=array(
			'name'=>'localidad',
			'size'=>'30',
			'value'=>''.$line->localidad,
	);
	$lada=array(
			'name'=>'lada',
			'size'=>'4',
			'value'=>''.$line->lada,
	);
	$fax=array(
			'name'=>'fax',
			'size'=>'10',
			'value'=>''.$line->fax,
	);

	$telefono=array(
			'name'=>'telefono',
			'size'=>'20',
			'value'=>''.$line->telefono,
	);
        $nombre_corto=array(
		'name'=>'nombre_corto',
		'size'=>'35',
		'value'=>''.$line->nombre_corto,
                 'id'=>'nombre_corto',
);


	//Titulo
	echo "<h2>Administrar $title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"". base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_clientes\" target='_blank'><img src=\"".base_url()."images/icons/icons_03.png\" width=\"30px\" title=\"Listado de Productos\" target='_blank'>Lista de Clientes </a>";
echo "<a href=\"". base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/rep_clientes\" target='_blank'><img src=\"".base_url()."images/adobereader.png\" width=\"30px\" title=\"Reporte de Clientes\" target='_blank'>Reporte de Clientes </a></div>";

	//Abrir Formulario
	$atrib=array('name'=>'form1');
	echo form_open($ruta.'/trans/alta_cliente', $atrib) . "\n";
	echo form_fieldset('<b>Editar Datos Generales del Cliente</b>') . "\n";
	echo "<table width=\"80%\" class='form_table'>";
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

        echo "<tr><td><label for=\"localidad\"><b><h3>Cuentas Bancarias</h3></b></label></td></tr>";
    if($cbancarias != false){
 foreach ($cbancarias->all as $data2){
        
        echo "<tr><td><label for=\"municipio\"><b>Banco:</b><font color='blue'> $data2->banco</font></label></td>";
        echo "<td><label for=\"estado\"><b>Numero Cuenta:</b><font color='blue'> $data2->numero_cuenta </font></label></td>";
        
        echo "<td><label for=\"estado\"><b>Clabe Interbamcaria:</b><font color='blue'> $data2->clabe </font></label></td>";
 
        echo "</tr><br/>";
 }
}
     echo'<tr><td><b>Sin Cuentas Bancarias</b></td></tr>';
	//Cerrar el Formulario
	echo "<br/><tr><td colspan='4' class=\"form_buttons\">";
	echo form_hidden('id', "$line->id");
	echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
	echo form_close();

	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1){
		echo '<button type="submit">Guardar Cambios</button>';
	}
	echo '</td></tr></table>';
	echo form_fieldset_close();

	}
?>
