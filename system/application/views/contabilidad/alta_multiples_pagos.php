<?php
//Construir Facturas
$select1[0]="Elija ";

//Construir Formas de Pago
$select2[0]="Elija";
if($formas_pagos!=false){
	foreach($formas_pagos->all as $row){
		$y=$row->id;
		$select2[$y]=$row->tag;
	}
}
//Construir Cuentas Bancarias
$select3[0]="Elija";
$select6[0]="Elija";
if($cuentas_bancarias!=false){

	foreach($cuentas_bancarias->all as $row){
		$y=$row->id;
		if($row->empresa_id>0)
	  $select3[$y]=$row->banco_nombre. " - ".$row->numero_cuenta;
		else if ($row->cproveedor_id>0)
	  $select6[$y]=$row->banco_nombre. " - ".$row->numero_cuenta;

	}
}
//Construir Tipos de Pago
$select4[0]="Elija";
if($tipos_pagos!=false){
	foreach($tipos_pagos->all as $row){
		$y=$row->id;
                    $select4[$y]=$row->tag;
	}
}
//Construir Proveedores
$select5[0]="Elija";
if($proveedores!=false){
	foreach($proveedores->all as $row){
		$y=$row->id;
		$select5[$y]=$row->razon_social;
	}
}
//Inputs
$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'value'=>'',
		'id'=>'fecha',
		'readonly'=>'readonly'
);
$numero_referencia=array(
		'name'=>'numero_referencia',
		'size'=>'15',
		'value'=>'',
);
$monto_pagado=array(
		'name'=>'monto_pagado',
		'size'=>'10',
		'value'=>'',
		'id'=>'monto_pagado',
);
$this->load->view('validation_view');
$url_pago=base_url()."index.php/".$ruta."/trans/act_multiple_pago";
//Titulo
$this->load->view('contabilidad/js_alta_multiple_pago');
echo "<h2>$title</h2>";
//Load validacion

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta.'/trans/act_pago', $atrib) . "\n";
echo form_fieldset('<b>Datos del Pago</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"900\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"proveedor\">Proveedor:</label><br/>"; 
echo form_dropdown('proveedor', $select5, 0, "id='proveedor'"); 
echo "</td><td class='form_tag'><label for=\"fecha\">Fecha: </label><br/>"; 
echo form_input($fecha);echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"formas_pagos\">Forma de Pago: </label><br/>"; 
echo form_dropdown('cpr_forma_pago_id', $select2, 4, "id='formas_pagos'");
echo "</td><td class='form_tag'><label for=\"cuenta_origen_id\">Numero de Cuenta Origen:</label><br/>"; 
echo form_dropdown('cuenta_origen_id', $select3, 0, "id='cuentas_bancarias'"); 
echo "</td><td class='form_tag'><label for=\"cuenta_destino_id\">Numero de Cuenta Destino:</label><br/>"; echo form_dropdown('cuenta_destino_id', $select6, 0, "id='cuenta_destino'"); 
echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Limpiar');
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Menú</button>";
//echo "<button type='button' id='boton1' onclick=\"javascript:mostrar_devoluciones_lote0()\">1.- Revisar Devoluciones Lote 0</button>";
echo "<button type='button' id='boton2' onclick=\"javascript:mostrar_detalle()\">2.- Agregar detalles y abonar devoluciones</button>";
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
?>
<div
	id="subform_devoluciones_lote0" style="margin-bottom: 20px;"></div>
<div
	id="subform_devoluciones_lote0_trans" style="margin-bottom: 20px;"></div>
<div
	id="subform_devoluciones" style="margin-bottom: 20px;"></div>
<div id="subform_detalle">
	<table width="900" class="row_detail" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Factura</th>
			<th width='500px' class='detalle_header'>Detalles</th>
			<th class='detalle_header'>Referencia</th>
			<th class='detalle_header'>Monto</th>
		</tr>
		<?php
		for($r=1;$r<=$rows;$r++){
			if($r<2)
				$clase="visible";
			else
				$clase="invisible";
echo "<tr id='r$r' class='$clase'><td class='detalle' width=\"50\">".$r.".- <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_pago/$r\" >
                            <button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; 
                        echo form_hidden("id$r", '0'); echo "</div>"; 
                        echo form_hidden("cpr_forma_pago_id$r", '0'); 
                        echo form_hidden("cuenta_origen_id$r", '0'); 
                        echo form_hidden("cuenta_destino_id$r", '0'); 
                        echo form_hidden("fecha$r", '0'); 
                        echo form_hidden("cproveedor_id$r", ''); echo "</td>".

	"<td class='detalle' width=\"200\"><span id=\"fact\">"; 
                     echo form_dropdown("pr_factura_id$r", $select1, 0, "id='pr_factura_id$r'");
                     echo "</span></td>".

					"<td class='detalle'><span id='detalles_pagos$r'></span></td>".
					"<td class='detalle'><input class=\"subtotal\" value=\"\" name=\"no_autorizacion$r\" id=\"no_autorizacion$r\" size=\"12\" ></td>".
					"<td class='detalle'><input class=\"subtotal\" value=\"0\" name=\"monto_pagado$r\" id=\"monto_pagado$r\" size=\"12\" ></form></td>".
					"</tr>";
		}
		echo "<tr><th colspan='4' align='right'>Totales</th><th align='right'><input type='text' size='12' id='total' name='total' value='0' class='subtotal'></th></tr>";

		echo "<tr><th class='detalle_pie' colspan='5'> \n <button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Salir al Menú</button>";
		if(substr(decbin($permisos), 0, 1)==1){
			echo '<button type="button" onclick="javascript:send_detalle()">Guardar Pagos</button>';
		}
		echo "<br/><div id='fin'></div></th></tr>";
		?>
	</table>
</div>
<?php
$atrib=array('name'=>'form2','id'=>'form2');
echo form_open($ruta.'/contabilidad_reportes/rep_pago_multiple_pdf', $atrib) . "\n";
//echo "<input type='text' id='pagos' name='pagos' value=''></form>";
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pagos\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Listado de Pagos\"></a></div>";
?>