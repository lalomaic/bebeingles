<link href="<?php echo base_url();?>css/default.css" rel="stylesheet"
	type="text/css">
<link
	href="<?php echo base_url();?>css/date_input.css" rel="stylesheet"
	type="text/css">
<script
	src='<? echo base_url(); ?>css/jquery.js'></script>
<script
	src='<? echo base_url(); ?>css/jquery.date.js'></script>
<table class="listado" border="0" width="900">
	<tr>
		<th>Devolucion</th>
		<th>Id Salida</th>
		<th>Cantidad devuelta</th>
		<th>Concepto</th>
		<th>Precio Unit.</th>
		<th>IVA</th>
		<th>Total</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	if($salidas!=false){
		$total=0; $r=0; $iva_total=0;
		$cadena_id="";
		foreach($salidas->all as $row) {
	  $cl_factura_id=$row->cl_facturas_id;
	  $cadena_id.=$row->id1."&".$row->cantidad.",";
	  $iva=($row->cantidad * $row->costo_unitario * $row->tasa_impuesto)/(100 + $row->tasa_impuesto);
	  $costo_siva=$row->costo_unitario - ($row->costo_unitario * $row->tasa_impuesto)/(100 + $row->tasa_impuesto);
	  $total+=$row->costo_total;
	  $iva_total+=$iva;
	  echo "<tr background=\"$img_row\" align=\"center\"><td><input type=\"checkbox\" name=\"detalle$r\" value='$row->id1' id=\"chk$r\" class=\"chk\" checked='checked' onclick=\"javascript:check(this.name, this.value)\"></td><td>$row->id1</td><td align='right'><input id='cantidad$r' value='$row->cantidad' name='cantidad$r' size='14' style='text-align:right;'></td><td align='left'>$row->descripcion - $row->unidad_medida</td><td align='right'>$costo_siva</td><td align='right'>$iva</td><td align='right'>$row->costo_total</td></tr>";
	  $r+=1;
		}
		$total_siva=$total-$iva_total;
		echo "<tr><td colspan='6' align='right'><strong>Subtotal</strong></td><td align='right'>$total_siva</td></tr>";
		echo "<tr><td colspan='6' align='right'><strong>IVA</strong></td><td align='right'>$iva_total</td></tr>";
		echo "<tr><td colspan='6' align='right'><strong>Total</strong></td><td align='right'>$total</td></tr>";
	}
	echo "</table></center>";
	$fecha=array(
			'name'=>'fecha',
			'class'=> 'date_input',
			'id'=>'fecha',
			'size'=>'10',
			'readonly'=>'readonly',
			'value'=>'',
	);
	$folio=array(
			'name'=>'folio',
			'id'=>'folio',
			'size'=>'10',
			'value'=>'',
	);
	?>
	<script>
  function envio(){
    cadena='';
    for(c=0;c<<? echo $r ?>;c++){
      if ($('#chk'+c+':checked').val() !== null) {
	cadena+=$('#chk'+c+':checked').val()+'&'+ $('#cantidad'+c).val()+",";
      }
    }
    //alert(''+cadena);
    $('#detalles').val(cadena);
    $('#form2').submit();
  }

  function check(obj, val){
    if(val>0){
	cadena=$('#detalles').val();
	$('#detalles').val(''+cadena+val+',');
    }
  }
  $($.date_input.initialize);
</script>
	<form name='form2' id='form2'
		action='<? echo base_url(); ?>index.php/almacen/trans/devolucion_nc/'
		method='post'>
		<input type='hidden' id='cl_factura'
			value='<? echo $cl_factura_id; ?>' name='cl_factura'> <input
			type='hidden' id='pr_factura' value='' name='pr_factura'> <input
			type='hidden' id='detalles' value='<? echo $cadena_id; ?>'
			name='detalles'>
		<table class="listado" border="1" width="500">
			<tr>
				<th colspan='4' id="frame_header"><strong>Paso 3. Ingresar los datos
						adicionales para generar la Nota de CrÃ©dito<strong>
				
				</th>
			</tr>
			<tr>
				<td><label>Folio de la Nota de CrÃ©dito</label></td>
				<td><?php echo form_input($folio); ?></td>
			</tr>
			<tr>
				<td><label>Fecha de EmisiÃ³n</label></td>
				<td><?php echo form_input($fecha); ?></td>
			</tr>
			<tr>
				<td colspan="4" align="right"><button type="button" id='boton1'
						onclick="javascript:envio()">Aplicar DevoluciÃ³n</button>
					</form></td>
			</tr>
		</table>
	</form>