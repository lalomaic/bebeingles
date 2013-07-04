<script type="text/javascript">
$(document).ready(function() {
	$(".modal_upload").fancybox({
		'width'				: '45%',
		'height'			: '75%',
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});

	$('#estatus').change(function() {
		pag=<? echo $pag;?>;
		location.href="<?php echo base_url(); ?>index.php/contabilidad/contabilidad_c/formulario/list_general_compras/"+pag+"/estatus/"+$(this).val();
	});

	 $('#cfamilia_id').val('');
	  $('#familia_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_familias_ajax/', {
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
		  $("#cfamilia_id").val(""+item.id);
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
  	  $('#csubfamilia_id').val('');
	  $('#csubfamilia_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_subfamilia_ajax/', {
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
		  $("#csubfamilia_id").val(""+item.id);
	  });

});
	function format(r) {
		return r.descripcion;
	}


	function limpiar(){
		window.location="<?=base_url()?>index.php/almacen/productos_c/formulario/list_productos_combo";
	}

</script>
<?php

echo "<h2>$title</h2>";
//Opciones
echo "<div style='text-align:right;margin:auto;width:960px;margin-top:5px'>";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/inicio/acceso/almacen/menu\"  title=\"Regresar al Menu Almacen\"><img src=\"".base_url()."images/menu_back.jpg\" width=\"30px\"> Menu </a>";
echo "<a style='margin-right:5px;' href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_producto\" title=\"Agregar Producto\"><img src=\"".base_url()."images/new_reg.png\" width=\"30px\"> Agregar </a>";
echo "</div>";
//formulario para Buscar
echo "<form method='post' accept-charset='utf-8' action=".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_productos_combo/1/buscar />";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Codigo de barras</b></td>";
echo "<td align=\"center\"><b>Familia</b></td>";
echo "<td align=\"center\"><b>Subfamilia</b></td>";
echo "<td align=\"center\"><b>Marca</b></td>";
echo "<td align=\"center\"><b>Descripcion</b></td><td></td></tr>";

echo "<tr><td align=\"center\"><input id='codigo_barras' name='codigo_barras' class='familia' size='20'></td>";
echo "<td align=\"center\"><input type='hidden' name='cfamilia_id' id='cfamilia_id' value='' ><input id='familia_drop' class='familia' value='' size='20'></td>";
echo "<td align=\"center\"><input type='hidden' name='csubfamilia_id' id='csubfamilia_id' value=''><input id='csubfamilia_drop' class='familia' value='' name='csubfamilia_drop' size='20'></td>";
echo "<td align=\"center\"><input type='hidden' name='cmarca_id' id='cmarca_id' value='' size=\"3\"><input id='marca_drop' class='marca' value='' size='20'></td>";
echo "<td align=\"center\"><input id='descripcion' name='descripcion' class='marca' value='' size='20'></td>";
echo "<td><input type='submit' value='Buscar' /> <button  type='button' onclick = 'javascript:limpiar()'> Limpiar </button></td>";
echo "</tr></table>";
echo "<div style='width:960px;margin:auto;'>";
echo "<input style='margin-left:15px;' type='checkbox' id='cod_bar' name='cod_bar' > Sin Codigo de Barras &nbsp;&nbsp;";
echo "<input type='checkbox' id='img' name='img' > Sin Imagen &nbsp;&nbsp;";
echo "<input type='checkbox' id='descontinuado' name='descontinuado' > Descontinuados &nbsp;&nbsp;";
echo "</div>";
echo "</form><br/>";

echo "<div align=\"center\"><b>Total de registros:".$total_registros."</b></div>";

if($paginacion==true)
    echo $this->pagination->create_links();

    //Botones de Edicion
    $img_row="".base_url()."images/table_row.png";
    $photo="";
    //$photo="<img src=\"".base_url()."images/adobereader.png\" width=\"25px\" title=\"ImpresiÃ³n\" border=\"0\">";
    $trash="<img src=\"".base_url()."images/trash.png\" width=\"25px\" title=\"Deshabilitar Concepto\" border=\"0\">";
    $inactivoImg="<img src=\"".base_url()."images/inactivo.png\" width=\"25px\" title=\"Descontinuar Concepto\" border=\"0\">";
    $confoto="<img src=\"".base_url()."images/fotos.jpg\" width=\"25px\" title=\"Imagen del Concepto\" border=\"0\">";
    $sinfoto="<img src=\"".base_url()."images/image.png\" width=\"25px\" title=\"Imagen del Concepto\" border=\"0\">";
    $codigoImg="<img src=\"".base_url()."images/codbar.jpg\" width=\"25px\" title=\"Con Codigo\" border=\"0\">";
    $desconImg="<img src=\"".base_url()."images/cancelado.png\" width=\"25px\" title=\"Habilitar producto descontinuado\" border=\"0\">";
    $delete="";
    $edit="";
    $linkDelete=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/borrar_producto/";
    $linkDescont=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/descontinuar_producto/";
    $linkActivDesco=base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/activar_producto_descon/";
    $linkEditar =base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_producto";
    $linkSubirFoto = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/subir_foto";
    $numeraciones = new Producto_numeracion();
?>
<table class="listado" border="0">
	<tr>
		<th>#</th>
		<th>Id</th>
		<th>Descripcion</th>
		<th>Familia</th>
		<th>Subfamilia</th>
		<th>Marca</th>
		<th>Color</th>
		<th>Precio Ve.</th>
		<th>Fecha Alta</th>
		<th>Fecha Edición</th>
                <th>Cod.</th>
		<th>Edición</th>
	</tr>
        <?php
	$x=$offset+1;
	if($productos!=false){
		foreach($productos->all as $row) {
			//Permiso de Borrado en el 2 Byte X1X
			if(substr(decbin($permisos), 1, 1)==1 ){
                            $delete="<a href=\"".$linkDelete.$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar el producto: $row->descripcion?');\">$trash</a>";
                            $descontinuar="<a href=\"".$linkDescont.$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas descontinuar el producto: $row->descripcion?');\">$inactivoImg</a>";
                            $edit="<img src=\"".base_url()."images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
			}

			if(strlen($row->ruta_foto)>0)
                            $foto=$confoto;
			else
                            $foto=$sinfoto;

			if($row->fecha_alta!='0000-00-00'){
				$fecha=explode('-', $row->fecha_alta);
				$fechaa=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
			if($row->fecha_cambio!='0000-00-00'){
				$fecha=explode('-', $row->fecha_cambio);
				$fechac=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			}
                        if($row->status=='0'){
                            $descontinuar="<a href=\"".$linkActivDesco.$row->id." \" onclick=\"return confirm('¿Estas seguro que deseas habilitar el producto: $row->descripcion?');\">$desconImg</a>";   
                        }                            
                        $num_con_codigo=$numeraciones->check_cod_bar_by_prod($row->id);
                        if($num_con_codigo)
                            $codigo=$codigoImg;
                        else
                            $codigo="";
			echo "<tr background=\"$img_row\" align=\"left\">".
                            "<td> $x </td>".
                            "<td> $row->id </td>".
                            "<td>$row->descripcion</td>".
                            "<td>$row->familia_producto</td>".
                            "<td>$row->subfamilia_producto</td>".
                            "<td>$row->marca</td>".
                            "<td>$row->color</td>".
                            "<td align='right'>".number_format($row->precio1,2,".",",")."</td>".
                            "<td>$fechaa</td>".
                            "<td>$fechac</td>".
                            "<td>$codigo</td>".
                            "<td><a href=\"".$linkEditar."/$row->id\" >$edit</a>$descontinuar $delete <a href=\"".$linkSubirFoto."/".$row->id."\" class='modal_upload'>$foto</a></td>".
                            "</tr>";
			$x+=1;
		}
	}
echo "</table></center>";

if($paginacion==true)
	echo $this->pagination->create_links();
?>
