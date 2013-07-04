<script src="<?php echo base_url();?>css/md5.js"></script>
<script>
$(document).ready(function() {
	
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
	function limpiar(){
		window.location="<?=base_url()?>index.php/almacen/almacen_c/formulario/list_inventario_inicial";
	}

        
        

</script>
<?php
echo "<h2>$title</h2>";
echo "<form method='post' accept-charset='utf-8' action=".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_inventario_inicial/1/buscar />";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Sucursal</b></td>";
echo "<td align=\"center\"><b>Proveedor</b></td>";
echo "<td align=\"center\"><b>Id Factura</b></td>";

echo "<tr><td align=\"center\"><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='20'></b><br/></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br></td>";

echo "<td align=\"center\"><b><input id='factura_id' class='factura_id' value='' name='factura_id' size='10'></b><br/></td>";

echo "<td><input type='submit' value='Buscar' /> <button  type='button' onclick = 'javascript:limpiar()'> Limpiar </button></td></form>";
echo "</tr></table>";


echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

?>
<table class="listado" border="0" width="900">
	<tr>
		<th>Id Factura</th>
		<th>Fecha</th>
		<th>Proveedor</th>
		<th>Folio</th>
		<th>Ubicación de Entrada</th>
		<th>Monto</th>
		<th>Estatus</th>
		<th width="110">Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";

	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"15px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"15px\" title=\"Deshabilitar Entrada\" border=\"0\">";
	$delete="";
	$edit="<img src=\"".base_url()."images/edit.png\" width=\"15px\" title=\"Agregar Productos\" border=\"0\">";

	if($entradas!=false){
		foreach($entradas->all as $row) {
			$auth=""; $pass="";
	  $codbar="<img src=\"".base_url()."images/codbar.jpeg\" width=\"20px\" title=\"Generar Etiquetas de Códigos de Barras\" border=\"0\">";
	  $edit_r="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_factura_entrada/".$row->pr_facturas_id." \" onclick=\"return confirm('¿Estas seguro que deseas Agregar mas Producto?');\">$edit</a>";

	  if(substr(decbin($permisos), 1, 1)==1 ){
	  	$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_entrada/$row->pr_facturas_id\" onclick=\"return confirm('¿Estas seguro que deseas cancelar la Entrada por Compra?');\">$trash</a>";

	  	//$auth="<a href=\"javascript:pre_auth($row->pr_facturas_id)\"  >$validar</a>";
	  	//$pass="<div id='$row->pr_facturas_id' class='aut'><input type='password' value='' id='passwd$row->pr_facturas_id' size='8'><a href='javascript:auth($row->pr_facturas_id)'>Autorizar</a></div>";

	  }
	  //Estatus Ubicacion
	  if($row->estatus_traspaso=="Entrada Almacen")
	  	$row->estatus_traspaso="Almacén León";

	  echo "<tr background=\"$img_row\" align=\"center\"><td>$row->pr_facturas_id</td><td>$row->fecha</td><td>$row->proveedor</td><td>$row->folio_factura</td><td>$row->espacio_fisico</td><td align='right'>$". number_format($row->importe_factura, 2,".",",")."</td><td>$row->estatus</td><td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_facturas_id\" target=\"_blank\" class='modal_pdf1'>$photo</a> $edit_r $delete <a href=\"".base_url()."index.php/compras/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->pr_pedido_id."\" target='_blank'></a> $auth $pass </td></tr>";
		}
	}
	echo "</table></center>";

		echo $this->pagination->create_links();
	?>