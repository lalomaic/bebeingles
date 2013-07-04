<?php
//Cuentas filtradas
$polizas_fil[0]="";
foreach($poliza->all as $linea){
	if($cuentas != false){
		foreach($cuentas->all as $row){
			$y=$row->cta;
			$polizas_fil[$y]=$row->cta. " - ".$row->tag;
		}
	}

	//Inputs
	$folio_poliza=array(
			'name'=>'folio_poliza',
			'size'=>'10',
			'value'=>''.$linea->folio_poliza,
	);
	if($linea->fecha!='0000-00-00'){
		$fecha=explode("-", $linea->fecha);
		$fecha_i=$fecha[2]." ".$fecha[1]." ".$fecha[0];
	}else
		$fecha_i="";
	$fecha=array(
			'class'=>'date_input',
			'name'=>'fecha',
			'id'=>'fecha',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>"$fecha_i"
	);

	$concepto=array(
			'name'=>'concepto',
			'id'=>'concepto',
			'size'=>'80',
			'value'=>"$linea->concepto"
	);
	$url_poliza=base_url()."index.php/".$ruta."/trans/alta_poliza_detalle_diario";
	//Titulo
	$this->load->view('contabilidad/js_alta_poliza_diario.php');
	echo "<h2>$title</h2>";
	//Abrir Formulario
	echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
	$atrib=array('name'=>'form1', 'id'=>"form1");
	echo form_open($ruta.'/trans/alta_poliza_diario', $atrib) . "\n";
	echo form_fieldset('<b>Editar Póliza de Diario</b>') . "\n";
	echo "<table width=\"950\" class='form_table'>";
	$img_row="".base_url()."images/table_row.png";

	//Campos del Formulario
	echo "<tr><td class='form_tag' colspan='2'><label for=\"empresas_id\">Empresa:</label>$empresa->razon_social</td></tr>";

	echo "<tr><td class='form_tag' colspan='2'><label for=\"concepto\">Concepto Global:</label><br/>"; echo form_input($concepto); echo "</td></tr>";

	echo "<tr><td class='form_tag'><label for=\"fecha\">Fecha:</label>"; echo form_input($fecha); echo "</td><td class='form_tag'><label for=\"folio_poliza\">Folio Póliza Diario:</label>"; echo form_input($folio_poliza); echo "</td></tr>";

	//Cerrar el Formulario
	echo "<tr><td class=\"form_buttons\" colspan='2' align=\"right\">";
	echo "<div id=\"out_form1\">";
	echo form_hidden('id', $linea->id);
	//Permisos de Escritura byte 1
	if(substr(decbin($permisos), 0, 1)==1)
		echo '<button type="submit" id="boton1" style="display:none;">Paso 1. Generar Póliza</button>';

	echo "</div>";
	echo '</td></tr></table>';
	echo form_fieldset_close();
	echo form_close();

	?>
<div id="subform_detalle">
	<table width="900" class="row_detail" id="header">
		<tr>
			<th class='detalle_header'>Estatus</th>
			<th class='detalle_header'>Cuenta</th>
			<th class='detalle_header'>Sub Sub Cuenta</th>
			<th class='detalle_header'>Debe</th>
			<th class='detalle_header'>Haber</th>
		</tr>
		<?php
		$r=0;
		foreach($poliza_detalles->all as $reng){

			echo "<tr id='r$r'><td class='detalle' width=\"100\">".($r+1).".- <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_poliza/$r\" ><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", "$reng->id"); echo form_hidden("poliza_id$r", "$reng->poliza_id"); echo "</div></td>".

	    "<td class='detalle' width=\"200\">"; echo form_dropdown("cta$r", $polizas_fil, $reng->cta, "id='cta$r' class='cuentas_fil'"); echo "</td>".

	    "<td class='detalle'><select name=\"subcuenta$r\" id=\"subcuenta$r\" style='width:200px;'>
                <option value='$reng->cuenta_contable_id'>$reng->subcuenta</option></select>";  echo "</td>".

	    "<td class='detalle'><input class=\"subtotal\" value=\"$reng->debe\" name=\"debe$r\" id=\"debe$r\" size=\"12\" ></td>".

	    "<td class='detalle'><input class=\"subtotal\" value=\"$reng->haber\" name=\"haber$r\" id=\"haber$r\" size=\"12\" ></form></td>".

	    "</tr>";
			$r+=1;
		}
		for($r;$r<$lineas_totales;$r++){

			echo "<tr id='r$r'><td class='detalle' width=\"50\">".($r+1).".- <form id=\"fdetail$r\" method=\"post\" name=\"fdetail$r\" action=\"$url_poliza/$r\" ><button type=\"submit\" id=\"b$r\" style=\"display:none;\">Guardar Registro</button><div id=\"content$r\">"; echo form_hidden("id$r", '0'); echo form_hidden("poliza_id$r", ''); echo "</div></td>".

					"<td class='detalle' width=\"200\">"; echo form_dropdown("cta$r", $polizas_fil, 0, "id='cta$r' class='cuentas_fil'"); echo "</td>".

					"<td class='detalle'><select name=\"subcuenta$r\" id=\"subcuenta$r\" style='width:200px;'><option value='0'>Elija una cuenta</option></select>";  echo "</td>".

					"<td class='detalle'><input class=\"subtotal\" value=\"0\" name=\"debe$r\" id=\"debe$r\" size=\"12\" ></td>".

					"<td class='detalle'><input class=\"subtotal\" value=\"0\" name=\"haber$r\" id=\"haber$r\" size=\"12\" ></form></td>".

					"</tr>";
		}

		echo "<tr><th colspan='3' align='right'>Totales</th><th align='right'><input type='text' size='12' id='debe_t' name='debe_t' value='0' class='subtotal'></th><th align='right'><input type='text' size='12' id='haber_t' name='haber_t' value='0' class='subtotal'></th></tr>";

		echo "<tr><th class='detalle_pie' colspan='5'> \n <button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Salir al Menú</button>";
		if(substr(decbin($permisos), 0, 1)==1){
			echo '<button type="button" onclick="javascript:send_principal()">Guardar Póliza</button>';
		}
		echo "<br/><div id='fin'></div></th></tr>";

		?>
	</table>
</div>
<?php
}
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_ventas\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Venta\"></a><a href=\"".base_url()."index.php/".$ruta."/clientes_c/".$funcion."/alta_cliente\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Alta de Cliente\"></a></div>";

?>