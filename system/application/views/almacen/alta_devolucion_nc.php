<?php
$select2[0]="Elija";
if($clientes!= false){
	foreach($clientes->all as $row){
		$y=$row->id;
		$select2[$y]=$row->razon_social. " - ". $row->clave;
	}
}
//Titulo
$this->load->view("almacen/js_alta_devolucion_nc");
echo "<h2>$title</h2>";
$atrib=array('name'=>'form1', 'target'=>"detalles_factura");
echo form_hidden('subfuncion', "$subfuncion");
$img_row="".base_url()."images/table_row.png";
?>
<table width="100%" class='form_table'>
	<tr>
		<th colspan='4' id="frame_header"><b>Paso 1. SelecciÃ³n de la Factura
				a Cancelar</b></th>
	</tr>
	<tr>
		<td width="30%" valign="top" align="center">
			<center>
				<?php
				echo form_open($ruta."/almacen_c/formulario/alta_devolucion_nc/detalles_factura/", $atrib) . "\n";
				echo form_fieldset('') . "\n";
				?>
				<table border="1">
					<tr>
						<td colspan="0">Empresa:</td>
						<td colspan="3"><?php echo $empresa->razon_social ;?></td>
					</tr>
					<tr>
						<td colspan="0">Clientes:</td>
						<td colspan="3"><?php echo form_dropdown('cliente', $select2, "0", 'id="cliente"');?>
						</td>
					</tr>
					<tr>
						<td colspan="0">Facturas del Cliente:</td>
						<td colspan="3"><select name="facturas" id="facturas"></select></td>
					</tr>
					<tr>
						<td colspan="4" align="right"><button type="submit">Ver Detalles
								de la Factura</button>
							</form></td>
					</tr>
				</table>
				<?php echo form_fieldset_close(); echo form_close();?>
			</center>
		</td>
	</tr>
	<tr>
		<th colspan='4' id="frame_header"><strong>Paso 2. Seleccionar los
				detalles de la Factura que se cancelarÃ¡n con la devoluciÃ³n<strong>
		
		</th>
	</tr>
	<tr>
		<td colspan='4'><iframe src='' name="detalles_factura" width="100%"
				height="500" id="frame"></iframe></td>
	</tr>
</table>
