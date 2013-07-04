<script>
$(document).ready(function() {
        $($.date_input.initialize);
	$('.aut').fadeOut(001);
	$('#estatus').change(function() {
		pag=<? echo $pag;?>;
		location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/estatus/"+$(this).val();
	});
	 $('#banco').val('');
	  $('#banco_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_cuentas_bancarias_ajax/', {
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
		  $("#banco").val(""+item.descripcion);
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
	  $('#num_cuenta').val('');
	  $('#num_cuenta_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_num_cuentas_ajax/', {
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
		  $("#num_cuenta").val(""+item.descripcion);
	  });
          

          $('#clave').val('');
	  $('#clave_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_clave_bancaria_ajax/', {
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
		  $("#clave").val(""+item.descripcion);
	  });


          $('#empresa').val('');
	  $('#empresa_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_empresa_ajax/', {
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
		  $("#empresa").val(""+item.id);
	  });

          
          
});
        
        function format(r) {
		return r.descripcion;
	}


	function limpiar(){
		window.location="<?=base_url()?>index.php/contabilidad/contabilidad_c/formulario/list_cuentas_bancarias";
	}



</script>


<?php 
echo "<h2>$title</h2>";

//formulario para Buscar
echo "<form method='post' accept-charset='utf-8' action=".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_bancarias/1/buscar />";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Banco</b></td>";
echo "<td align=\"center\"><b>Proveedor</b></td>";
echo "<td align=\"center\"><b>Num. De Cuenta</b></td>";
echo "<td align=\"center\"><b>Clave Interbancaria</b></td>";
echo "<td align=\"center\"><b>Empresa</b></td>";

echo "<tr><td align=\"center\"><input type='hidden' name='banco' id='banco' value='' size=\"3\"><input id='banco_drop' class='espacio' value='' size='20'></b><br/>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br>";

echo "<td align=\"center\"><b><input type='hidden' name='num_cuenta' id='num_cuenta' value='' size=\"3\"><input id='num_cuenta_drop' class='marca' value='' size='20'></b><br/>";

echo "<td align=\"center\"><b><input type='hidden' name='clave' id='clave' value='' size=\"3\"><input id='clave_drop' class='marca' value='' size='20'></b><br/>";

echo "<td align=\"center\"><b><input type='hidden' name='empresa' id='empresa' value='' size=\"3\"><input id='empresa_drop' class='marca' value='' size='20'></b><br/></td>";
echo "<td><input type='submit' value='Buscar' /> <button  type='button' onclick = 'javascript:limpiar()'> Limpiar </button></td>";

echo "</tr></table>";



echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_cuenta_bancaria\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nueva Cuenta Bancaria\"></a></div>";
echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";
?>
<?php //echo $this->pagination->create_links();?>
<table class="listado" border="0">
	<tr>
		<th>Id</th>
		<th>Tipo de Cuenta</th>
		<th>Nombre del Banco</th>
		<th>Numero de Cuenta</th>
		<th>Clabe</th>
		<th>Empresa</th>
		<th>Proveedor</th>
		<th>Cliente</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"Impresión\" border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
	$delete="";
	$edit="";
	foreach($cuentas_bancarias->all as $row) {
		//Permiso de Borrado en el 2 Byte X1X
		if(substr(decbin($permisos), 1, 1)==1 ){
			$delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_cuenta/".$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas borrar la cuenta Bancaria?');\">$trash</a>";
		}
		//echo "--".decbin($permisos);
		if(substr(decbin($permisos), 2, 1)==1){
			$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
		}

		echo "<tr background=\"$img_row\"><td>$row->id</td>
		<td>$row->tipo_cuenta</td><td>$row->banco_nombre</td>
		<td>$row->numero_cuenta</td><td>$row->clabe</td>
		<td>$row->empresa</td><td>$row->proveedor</td>
		<td>$row->cliente</td><td><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_cuenta_bancaria/".$row->id."\">$edit</a>$delete<a href=\"".base_url()."index.php/welcome/sespolice/quejas_pdf/".$row->id."\" target=\"_blank\">$photo</a></td></tr>";

	}
	echo "</table></center>";
	echo $this->pagination->create_links();
	?>