<?php
$ruta_auth=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/validar_contablemente/";
?>
<script
	src="<?php echo base_url();?>css/md5.js"></script>
<script>
$(document).ready(function() {
	$('.aut').fadeOut(001);
	$($.date_input.initialize);	
	 $('#espacio_fisico_id').val('');
	  $('#espacio_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_espacios_ajax/', {
			//extraParams: {pid: function() { return $("#proveedor").val(); } },
			minLength: 3,
			multiple: false,
			noCache: true,
		  parse: function(data) {
			  return $.map(eval(data), function(row) {
				  return {
					  data: row,
					  value: row.id,
					  result: row.descripcion
				  }
			  });
		  },
		  formatItem: function(item) {
			  return format(item);
		  }
	  }).result(function(e, item) {
		  $("#espacio_fisico_id").val(""+item.id);
	  });

	  $('#cproveedor_id').val('');
	  $('#proveedor_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
		  extraParams: {pid: 0 },
		  minLength: 3,
		  multiple: false,
		  noCache: true,
		  parse: function(data) {
			  return $.map(eval(data), function(row) {
				  return {
					  data: row,
					  value: row.id,
					  result: row.descripcion
				  }
			  });
		  },
		  formatItem: function(item) {
			  return format(item);
		  }
	  }).result(function(e, item) {
		  $("#cproveedor_id").val(""+item.id);
	  });

	  $('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
		  extraParams: {pid: 0 },
		  minLength: 3,
		  multiple: false,
		  noCache: true,
		  parse: function(data) {
			  return $.map(eval(data), function(row) {
				  return {
					  data: row,
					  value: row.id,
					  result: row.descripcion
				  }
			  });
		  },
		  formatItem: function(item) {
			  return format(item);
		  }
	  }).result(function(e, item) {
		  $("#cmarca_id").val(""+item.id);
	  });	
	
	
});

function format(r) {
	return r.descripcion;
}
	
function pre_auth(id){
		$('#'+id).fadeIn(2000);
		$('#passwd'+id).focus();
}
function auth(id){
	key=$('#passwd'+id).val();
	if(key.length>0 && key!= ''){
		llave=hex_md5(key);
		path="<? echo $ruta_auth;?>"+id+"/"+llave;
		location.href=''+path;
	} else {
		alert("No se ha ingresado la contraseña de autorización");
	}
}

function fil_sucursal(){
	pag=<? echo $pag;?>;
	ped=$("#espacio_fisico_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/sucursal/"+ped;
}
function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/marcas/"+ped;
}

function fil_factura_id(){
	pag=<? echo $pag;?>;
	ped=$("#factura_id").val();
	location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/factura_id/"+ped;
}

function filtrado(){
	document.form1.submit();
}
</script>
<?php 
echo "<h2>$title Sin Validar</h2>";
echo "<div style='width:920px;margin:5px auto;border-bottom: 1px solid #CCC;padding-bottom: 5px;' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/contabilidad/menu\"  title=\"Regresar al Menu Almacen\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"25px\"> Menu Almacen</a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_entrada\" title=\"Agregar\"><img src=\"".base_url()."images/new_reg.png\" width=\"25px\"  > Agregar entrada </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_general_compras_validadas\" title=\"Listado de Entradas validadas\"><img src=\"".base_url()."images/factura.png\" width=\"25px\"  > Compras validadas </a></div>";

$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta."/$controller/$funcion/$subfuncion/filtrado", $atrib) . "\n";

echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal</b></td>";
echo "<td align=\"center\"><b>Proveedor</b></td>";

echo "<td align=\"center\"><b>Fecha Inicio</b></td>";
echo "<td align=\"center\"><b>Fecha Final</b></td>";
echo "<td align=\"center\"><b>Id Factura</b></td></tr>";

echo "<tr><td align=\"center\"><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='20'></b><br/><button type='button' onclick='javascript:fil_sucursal()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br><button type='button' onclick='javascript:fil_proveedor()'>Filtrar</button></td>";



echo "<td align=\"center\" valign='top' colspan='2'><b><input id='fecha_inicio' name='fecha_inicio' class='date_input' value='$fecha1' size='12'><input id='fecha_final' name='fecha_final' class='date_input' value='$fecha2' size='12'><br/><button type='button' onclick='javascript:filtrado()'>Filtrado Múltiple</button></td>";

echo "<td align=\"center\"><b><input id='factura_id' class='factura_id' value='' size='10'></b><br/><button type='button' onclick='javascript:fil_factura_id()'>Filtrar</button></td><tr>";

echo "</tr></table></form>";


echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="90%">
	<tr>
		<th>Id Factura</th>
		<!--<th>Id Pedido</th>
		--><th>Fecha</th>
		<th>Folio Factura</th>
		<th>Vencimiento</th>
		<th>Tienda</th>
		<th>Proveedor</th>
		<th>Marca</th>
		<th>Monto</th>
		<!--<th>Ubicación</th>
		--><th>Estatus</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$validar="<img src=\"".base_url()."images/bien.png\" width=\"20px\" title=\"Validar Compra\" border=\"0\">";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	if($entradas!=false){
		foreach($entradas->all as $row) {
			$row->estatus="Sin validar";
			$auth="<a href=\"javascript:pre_auth($row->pr_facturas_id)\" >$validar</a>";
			$pass="<div id='$row->pr_facturas_id' class='aut'><input type='password' value='' id='passwd$row->pr_facturas_id' size='8'><a href='javascript:auth($row->pr_facturas_id)'>Autorizar</a></div>";


			//Vencimiento
			$vencimiento=round((strtotime($row->fecha_pago)-strtotime($row->fecha))/ (24 * 3600),0);

			//Estatus Ubicacion
			if($row->estatus_traspaso=="Entrada Almacen")
				$row->estatus_traspaso="Almacén León";

			echo "<tr background=\"$img_row\" align=\"center\"><td>$row->pr_facturas_id</td><td>$row->fecha</td><td>$row->folio_factura</td><td>$vencimiento</td><td>$row->espacio_fisico</td><td>$row->proveedor</td><td>$row->marca</td><td>". number_format($row->importe_factura,2,".",",")."</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_facturas_id\" target=\"_blank\" class='modal_pdf1'>$photo</a> $auth $pass </td></tr>";

		}
	}
	echo "</table></center>";
	if($paginacion==true)
		echo $this->pagination->create_links();
	echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_general_compras_validadas\"><img src=\"".base_url()."images/factura.png\" width=\"50px\" title=\"Listado de Compras Validadas\"></a></div>";
	?>