<script type="text/javascript">
    $(document).ready(function() {
        $(".modal_upload").fancybox({
            'width'				: '45%',
            'height'			: '75%',
            'autoScale'			: true,
            'transitionIn'		: 'none',
            'transitionOut'		: 'none',
            'type'				: 'iframe'
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

});

function format(r) {
		return r.descripcion;
	}


	function limpiar(){
		window.location="<?=base_url()?>index.php/compras/proveedores_c/formulario/list_proveedor";
	}

</script>

<?php
echo "<h2>$title</h2>";

echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_proveedor\"><img src=\"" . base_url() . "images/new_reg.png\" width=\"30px\" title=\"Agregar Proveedor\">Nuevo Proveedor</a>";

//Formulario del Buscador
echo "<form method='post' accept-charset='utf-8' action=".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_proveedor/1/buscar />";
echo "<table ><tr>";
echo "<td align=\"center\"><b>Proveedor</b></td>";

echo "<td align=\"center\"><b><input type='hidden' name='cproveedor_id' id='cproveedor_id' value='' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='20'></b></br></td>";

echo "<td><input type='submit' value='Buscar' /> <button  type='button' onclick = 'javascript:limpiar()'> Limpiar </button></td>";

echo "</tr></table>";
//Termina Buscador
echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";

echo $this->pagination->create_links();
?>
<table class="listado" border="0" width="90%">
    <tr>
        <th>Id</th>
        <th>Razón Social</th>
<!--		<th>Domicilio</th>-->
        <th>Teléfono</th>
        <th>Email</th>
        <th>Limite de Crédito</th>
        <th>Días <br />Crédito
        </th>
<!--		<th>Marcas</th>-->
        <th>Capturista</th>
        <th widht="70">Edición</th>
    </tr>
    <?php
    //Botones de Edicion
    $img_row = "" . base_url() . "images/table_row.png";
    $trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"20px\" title=\"Deshabilitar Tipo de Entrada\" border=\"0\">";
    $trade = "<img src=\"" . base_url() . "images/stock.png\" width=\"25px\" title=\"Revisar las marcas\" border=\"0\">";
    $delete = "";
    $edit = "";
    $marcas = "";

    if ($proveedores != false) {
        foreach ($proveedores->all as $row) {
            //Permiso de Borrado en el 2 Byte X1X
            if (substr(decbin($permisos), 1, 1) == 1) {
                $delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/borrar_proveedor/" . $row->id . " \" onclick=\"return confirm('¿Estas seguro que deseas deshabilitar al proveedor con sus marcas asociadas?');\">$trash</a>";
            }
            //echo "--".decbin($permisos);
            if (substr(decbin($permisos), 2, 1) == 1) {
                $edit = "<img src=\"" . base_url() . "images/edit.png\" width=\"20px\" title=\"Editar Registro\" border=\"0\">";
            }

            $marcas = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/marcas_proveedor/" . $row->id . " \"  class='modal_upload'>$trade</a>";



            echo "<tr background=\"$img_row\"><td>$row->id</td>
                <td>$row->razon_social</td>
                <td>$row->telefono</td>
                <td>$row->email</td>
                <td align='right'>" . number_format($row->limite_credito, 2, ".", ",") . "</td>
                <td align='right'>$row->dias_credito</td>
                <td>$row->usuario</td>
                <td><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_proveedor/" . $row->id . "\">$edit</a>$delete</td></tr>";
        }
    }
    echo "</table></center>";
    echo $this->pagination->create_links();
    ?>