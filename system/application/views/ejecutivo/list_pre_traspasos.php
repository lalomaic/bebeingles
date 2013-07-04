<script type="text/javascript">
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
});
	function format(r) {
		return r.descripcion;
	}

function fil_sucursal(){
	pag=<? echo $pag;?>;
	ped=$("#espacio_fisico_id").val();
	location.href="<?php echo base_url(); ?>index.php/<?=$ruta?>/<?=$controller?>/formulario/list_pre_traspasos/"+pag+"/sucursal/"+ped;
}
</script>
<?php
echo "<h2>$title</h2>";
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_compra\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Nuevo Pedido de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/list_proveedor\"><img src=\"".base_url()."images/proveedor.png\" width=\"50px\" title=\"Listado de Proveedores\"></a></div>";

echo "<div align=\"center\"><b>Filtrado por Sucursal <input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='40'></b><button onclick='javascript:fil_sucursal()'>Filtrar</button></div>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="600">
	<tr>
		<th>Id PreT</th>
		<th>Fecha</th>
		<th>Sucursal Origen</th>
		<th>Sucursal Destino</th>
		<th>Capturista</th>
		<th>Edición</th>
	</tr>
	<?php
	//Botones de Edicion
	$img_row="".base_url()."images/table_row.png";
	$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\"  border=\"0\">";
	$trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Cancelar Pedido\" border=\"0\">";
	$ok="<img src=\"".base_url()."images/bien.png\" width=\"25px\" title=\"Enviar Traspaso a la Sucursal\" border=\"0\">";
	$edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Corregir Factura0\" border=\"0\">";
	if($traspasos==false){
		echo "Sin Registros";
	} else {
		foreach($traspasos->all as $row) {
			$pdf="<a href=\"".base_url()."index.php/$ruta/ejecutivo_reportes/rep_pre_traspaso/$row->id\" target=\"_blank\"  title='Ver el reporte del pre traspaso $row->id'>$photo</a>";

			//Permiso de Borrado en el 2 Byte X1X
			echo "<tr background=\"$img_row\"><td align=\"center\">$row->id</td><td align=\"center\">$row->fecha</td><td align=\"center\">$row->origen</td><td align=\"center\">$row->destino</td><td align=\"center\">$row->usuario</td><td>$pdf</td></tr>";
		}
	}
	echo "</table></center>";

	if($paginacion==true)
		echo $this->pagination->create_links();
?>