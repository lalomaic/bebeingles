<script src="<?php echo base_url();?>css/md5.js"></script>
<script>
$(document).ready(function() {
    $($.date_input.initialize);
	$('.aut').fadeOut(001);
	$('#estatus').change(function() {
		pag=<? echo $pag;?>;
		location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/estatus/"+$(this).val();
	});

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
function fil_folio(){
    pag=<? echo $pag;?>;
    ped=$("#folio").val();
    location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/folio/"+ped;
}
function fil_by_date(){
    pag=<? echo $pag;?>;
    fd=$("#from_date").val().split(" ");
    td=$("#to_date").val().split(" ");
    if(fd.length > 1 && td.length > 1){
        d=+fd[0]+"-"+fd[1]+"-"+fd[2]+"/"+td[0]+"-"+td[1]+"-"+td[2];
        location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/by_date/"+d;
    }else{
        $("#byDateMessage").html("<div><b>Seleccione dos fechas para filtrar</b></div>");
    }    
}
function fil_sucursal(){
	pag=<? echo $pag;?>;
	ped=$("#espacio_fisico_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/sucursal/"+ped;
}
function fil_proveedor(){
	pag=<? echo $pag;?>;
	ped=$("#cproveedor_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/proveedor/"+ped;
}
function fil_marca(){
	pag=<? echo $pag;?>;
	ped=$("#cmarca_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/marcas/"+ped;
}
function fil_pedido_id(){
	pag=<? echo $pag;?>;
	ped=$("#pedido_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/pedido_id/"+ped;
}
function fil_factura_id(){
	pag=<? echo $pag;?>;
	ped=$("#factura_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/factura_id/"+ped;
}
function fil_lote_id(){
	pag=<? echo $pag;?>;
	ped=$("#lote_id").val();
	location.href="<?php echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas_validadas/"+pag+"/lote_id/"+ped;
}

</script>
<?php
echo "<h2>$title</h2>";
//Link al listado
echo "<div style='width:920px;margin:5px auto;border-bottom: 1px solid #CCC;padding-bottom: 5px;' align=\"right\">";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/almacen/menu\"  title=\"Regresar al Menu Almacen\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"25px\"> Menu Almacen</a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_entrada\" title=\"Agregar\"><img src=\"".base_url()."images/new_reg.png\" width=\"25px\"  > Agregar entrada </a>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_entradas\"><img src=\"".base_url()."images/bitacora.png\" width=\"25px\" title=\"Listado de Entradas sin Validar\" > Entradas sin validar </a></div>";


echo "<table >".
        "<tr>".
            "<td align=\"center\"><b>Folio</b></td>".
            "<td align=\"center\"><b>Sucursal</b></td>".
            "<td align=\"center\"><b>Proveedor</b></td>".
            "<td align=\"center\"><b>Id Factura</b></td>".
            "<td align=\"center\"><div id='byDateMessage'><b>Por fecha</b></div></td>".
        "</tr>";
echo "<tr><td align=\"center\"><input id='folio' class='folio' size='10'></b><br/><button onclick='fil_folio()'>Filtrar</button></td>";

echo "<td align=\"center\"><input type='hidden' name='espacio_fisico_id' id='espacio_fisico_id' value='' size=\"3\"><input id='espacio_drop' class='espacio' value='' size='20'></b><br/><button onclick='javascript:fil_sucursal()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b></br><button onclick='javascript:fil_proveedor()'>Filtrar</button></td>";

echo "<td align=\"center\"><b><input id='factura_id' class='factura_id' value='' size='10'></b><br/><button onclick='javascript:fil_factura_id()'>Filtrar</button></td>";
$date = date("d m Y");
$date2 = date("Y-m-d");// current date
$date1 = strtotime(date("Y-m-d", strtotime($date2)) . " -1 month");
echo "<td  align=\"center\">".
        "<b>De: </b><input class='date_input'  value='".date('d m Y', $date1)."' readonly='readonly' id='from_date'size='10'><b>".
        "  A: </b><input readonly='readonly' value='$date' class='date_input' id='to_date'size='10'>".
        "<br/><button onclick='fil_by_date()'>Filtrar</button>";
echo "</td>";
echo "</tr></table>";


echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

if($paginacion==true)
	echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="900">
    <tr>
        <th>Id Factura</th>
        <th>Fecha</th>
        <th>Ubicación de Entrada</th>
        <th>Proveedor</th>
        <th>Folio</th>
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
$edit="<img src=\"".base_url()."images/edit.png\" width=\"15px\" title=\"Corregir Factura\" border=\"0\">";
$codbar="<img src=\"".base_url()."images/codbar.jpg\" width=\"20px\" title=\"Generar Etiquetas de Códigos de Barras\" border=\"0\">";        
if($entradas!=false){
    foreach($entradas->all as $row) {
        //$edit_r="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_factura_entrada/".$row->pr_facturas_id." \" onclick=\"return confirm('¿Estas seguro que deseas corregir esta Factura?');\">$edit</a>";

        if(substr(decbin($permisos), 1, 1)==1 ){
            $delete="<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_entrada/$row->pr_facturas_id\" onclick=\"return confirm('¿Estas seguro que deseas cancelar la Entrada por Compra?');\">$trash</a>";
        }
        echo "<tr background=\"$img_row\" align=\"center\">".
                "<td>$row->pr_facturas_id</td>".
                "<td>$row->fecha</td>".
                "<td>$row->espacio_fisico</td>".
                "<td>$row->proveedor</td>".
                "<td>$row->folio_factura</td>".            
                "<td align='right'>$". number_format($row->importe_factura, 2,".",",")."</td>".
                "<td>$row->estatus</td>".
                "<td><a href=\"".base_url()."index.php/compras/compras_reportes/rep_pr_factura_pdf/$row->pr_facturas_id\" target=\"_blank\" class='modal_pdf1'>$photo</a>  $delete <a href=\"".base_url()."index.php/compras/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->pr_facturas_id."\" target='_blank'>$codbar</a>  </td>".
            "</tr>";
    }
}
echo "</table></center>";
/* Link codigo de barras <a href=\"".base_url()."index.php/compras/compras_reportes/rep_etiquetas_codigo_barras_pdf/".$row->pr_pedido_id."\" target='_blank'>$codbar</a>*/
if($paginacion==true)
        echo $this->pagination->create_links();
?>
